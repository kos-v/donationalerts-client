<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\OAuth;

use InvalidArgumentException;
use Kosv\DonationalertsClient\Contracts\OAuthClientAuthorizeRequest;
use Kosv\DonationalertsClient\Contracts\TransportClient;
use Kosv\DonationalertsClient\Contracts\TransportClientError;
use Kosv\DonationalertsClient\Contracts\TransportResponse;
use Kosv\DonationalertsClient\Exceptions\OAuth\AccessDeniedException;
use Kosv\DonationalertsClient\Exceptions\OAuth\Exception;
use Kosv\DonationalertsClient\Exceptions\OAuth\InvalidAccessTokenRequestException;
use Kosv\DonationalertsClient\Exceptions\OAuth\ServerException;
use Kosv\DonationalertsClient\Exceptions\ValidateException;
use Kosv\DonationalertsClient\OAuth\Enums\GrantTypeEnum;
use Kosv\DonationalertsClient\OAuth\Enums\ResponseErrorEnum;
use Kosv\DonationalertsClient\Transport\CurlClient;
use Kosv\DonationalertsClient\Validator\KeysEnum;
use Kosv\DonationalertsClient\Validator\Rules\IsTypeRule;
use Kosv\DonationalertsClient\Validator\Rules\RequiredFieldRule;
use Kosv\DonationalertsClient\Validator\Validator;
use Kosv\DonationalertsClient\ValueObjects\AccessToken;
use function sprintf;
use UnexpectedValueException;

final class OAuth
{
    private const ACCESS_TOKEN_ENDPOINT = 'https://www.donationalerts.com/oauth/token';

    private Config $config;
    private TransportClient $httpClient;

    public function __construct(Config $config, ?TransportClient $httpClient = null)
    {
        $this->config = $config;
        $this->httpClient = $httpClient ?? new CurlClient();
    }

    /**
     * @throws AccessDeniedException
     * @throws ValidateException
     * @throws InvalidAccessTokenRequestException
     * @throws Exception
     */
    public function completeAuthorize(OAuthClientAuthorizeRequest $request): AccessToken
    {
        if ($err = $request->getError()) {
            if ($err === ResponseErrorEnum::ACCESS_DENIED) {
                throw new AccessDeniedException();
            }
            throw new Exception("Request contains error. Error: {$err}");
        }

        if ($request->isAuthorizeCodeGrant()) {
            return $this->requestAccessToken($request->getAuthorizeCode());
        }

        if ($request->isImplicitGrant()) {
            $this->validateAccessTokenPayload([
                'access_token' => $request->getAccessToken(),
                'expires_in' => $request->getAccessTokenExpirationTime(),
                'token_type' => $request->getAccessTokenType(),
            ], GrantTypeEnum::IMPLICIT);

            return new AccessToken(
                $request->getAccessToken(),
                $request->getAccessTokenExpirationTime(),
                $request->getAccessTokenType()
            );
        }

        throw new Exception('Bad request. Not specified grant type');
    }

    public function makeAuthorizeUrl(string $grantType, array $scopes = []): string
    {
        return (new AuthorizeUrlBuilder(
            $grantType,
            $this->config->clientId,
            $this->config->redirectUri,
            $scopes
        ))->build();
    }

    public function refreshAccessToken(AccessToken $oldToken, array $scopes = []): AccessToken
    {
        if (!$oldToken->getRefreshToken()) {
            throw new UnexpectedValueException(sprintf(
                '%s object not contains refresh token',
                AccessToken::class
            ));
        }

        return $this->handleAccessTokenResponse(
            $this->httpClient->post(self::ACCESS_TOKEN_ENDPOINT, [
                'grant_type' => GrantTypeEnum::REFRESH_TOKEN,
                'refresh_token' => $oldToken->getRefreshToken(),
                'client_id' => $this->config->clientId,
                'client_secret' => $this->config->clientSecret,
                'scope' => $scopes,
            ]),
            GrantTypeEnum::REFRESH_TOKEN
        );
    }

    /**
     * @throws InvalidAccessTokenRequestException
     * @throws ValidateException
     * @throws TransportClientError
     */
    public function requestAccessToken(string $authorizeCode): AccessToken
    {
        return $this->handleAccessTokenResponse(
            $this->httpClient->post(self::ACCESS_TOKEN_ENDPOINT, [
                'grant_type' => GrantTypeEnum::AUTHORIZATION_CODE,
                'client_id' => $this->config->clientId,
                'client_secret' => $this->config->clientSecret,
                'redirect_uri' => $this->config->redirectUri,
                'code' => $authorizeCode,
            ]),
            GrantTypeEnum::AUTHORIZATION_CODE
        );
    }

    /**
     * @throws InvalidAccessTokenRequestException
     * @throws ValidateException
     * @throws ServerException
     */
    private function handleAccessTokenResponse(TransportResponse $response, string $grantType): AccessToken
    {
        if (!in_array($grantType, [
            GrantTypeEnum::AUTHORIZATION_CODE,
            GrantTypeEnum::REFRESH_TOKEN
        ], true)) {
            throw new InvalidArgumentException(sprintf(
                '$grantType contains unsupported value "%s". Expected: "%s" or "%s"',
                $grantType,
                GrantTypeEnum::AUTHORIZATION_CODE,
                GrantTypeEnum::REFRESH_TOKEN
            ));
        }

        if ($response->getStatusCode() !== 200 && $response->isJson()) {
            if (isset($response->toArray()['error'])
                && $response->toArray()['error'] === 'invalid_request') {
                // TODO: Переработать и избавиться от InvalidAccessTokenRequestException
                throw new InvalidAccessTokenRequestException(
                    $response->toArray(),
                    $response->getStatusCode()
                );
            }
            throw new ServerException(
                'Unexpected response code',
                $response->getStatusCode(),
                (string)$response
            );
        }

        if (!$response->isJson()) {
            throw new ServerException(
                'The server returned response in not json format',
                $response->getStatusCode(),
                (string)$response
            );
        }

        $this->validateAccessTokenPayload($response->toArray(), $grantType);

        return new AccessToken(
            $response->toArray()['access_token'],
            $response->toArray()['expires_in'],
            $response->toArray()['token_type'],
            $response->toArray()['refresh_token']
        );
    }

    /**
     * @throws ValidateException
     */
    private function validateAccessTokenPayload(array $payload, string $grantType): void
    {
        $firsPartErr = $grantType === GrantTypeEnum::IMPLICIT ? 'Request' : 'Response';

        $requiredFields = ['access_token', 'expires_in', 'token_type'];
        if ($grantType !== GrantTypeEnum::IMPLICIT) {
            $requiredFields[] = 'refresh_token';
        }

        $errors = (new Validator([
            new RequiredFieldRule(
                KeysEnum::WHOLE_TARGET,
                $requiredFields,
                false,
                sprintf(
                    '%s not contains required fields. No fields: {{notFoundFields}}',
                    $firsPartErr
                )
            ),
            new IsTypeRule(
                'expires_in',
                'integer',
                false,
                sprintf(
                    '%s contains not valid data type of expires_in field. Must be an integer',
                    $firsPartErr
                )
            ),
        ]))->validate($payload);

        if (!$errors->isEmpty()) {
            throw new ValidateException((string)$errors->getFirstError());
        }
    }
}
