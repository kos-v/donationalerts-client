<?php

declare(strict_types=1);

namespace DonationalertsClient\Tests\API\Resources\V1\Merchandises;

use function array_map;
use Kosv\DonationalertsClient\API\Enums\LangEnum;
use Kosv\DonationalertsClient\API\Resources\V1\Merchandises\Title;
use Kosv\DonationalertsClient\Collection\ImmutableArrayObject;
use Kosv\DonationalertsClient\Exceptions\ValidateException;
use PHPUnit\Framework\TestCase;
use function sprintf;

final class TitleTest extends TestCase
{
    public function testGetValues(): void
    {
        $resource1 = new Title([
            LangEnum::ENGLISH_USA => 'Title 1',
            LangEnum::BELARUSIAN => 'Title 2',
            LangEnum::GERMAN => 'Title 3',
            LangEnum::SPANISH => 'Title 4',
            LangEnum::SPANISH_USA => 'Title 5',
            LangEnum::ESTONIAN => 'Title 6',
            LangEnum::FRENCH => 'Title 7',
            LangEnum::HEBREW => 'Title 8',
            LangEnum::ITALIAN => 'Title 9',
            LangEnum::GEORGIAN => 'Title 10',
            LangEnum::KAZAKH => 'Title 11',
            LangEnum::KOREAN => 'Title 12',
            LangEnum::LATVIAN => 'Title 13',
            LangEnum::POLISH => 'Title 14',
            LangEnum::PORTUGUESE_BRAZIL => 'Title 15',
            LangEnum::RUSSIAN => 'Title 16',
            LangEnum::SWEDISH => 'Title 17',
            LangEnum::TURKISH => 'Title 18',
            LangEnum::UKRAINIAN => 'Title 19',
            LangEnum::CHINESE => 'Title 20',
        ]);

        $this->assertEquals('Title 1', $resource1->getEnglishUsa());
        $this->assertEquals('Title 2', $resource1->getBelarusian());
        $this->assertEquals('Title 3', $resource1->getGerman());
        $this->assertEquals('Title 4', $resource1->getSpanish());
        $this->assertEquals('Title 5', $resource1->getSpanishUsa());
        $this->assertEquals('Title 6', $resource1->getEstonian());
        $this->assertEquals('Title 7', $resource1->getFrench());
        $this->assertEquals('Title 8', $resource1->getHebrew());
        $this->assertEquals('Title 9', $resource1->getItalian());
        $this->assertEquals('Title 10', $resource1->getGeorgian());
        $this->assertEquals('Title 11', $resource1->getKazakh());
        $this->assertEquals('Title 12', $resource1->getKorean());
        $this->assertEquals('Title 13', $resource1->getLatvian());
        $this->assertEquals('Title 14', $resource1->getPolish());
        $this->assertEquals('Title 15', $resource1->getPortugueseBrazil());
        $this->assertEquals('Title 16', $resource1->getRussian());
        $this->assertEquals('Title 17', $resource1->getSwedish());
        $this->assertEquals('Title 18', $resource1->getTurkish());
        $this->assertEquals('Title 19', $resource1->getUkrainian());
        $this->assertEquals('Title 20', $resource1->getChinese());

        $resource2 = new Title([
            LangEnum::ENGLISH_USA => 'Title 1',
            LangEnum::BELARUSIAN => null,
            LangEnum::GERMAN => null,
            LangEnum::SPANISH => null,
            LangEnum::SPANISH_USA => null,
            LangEnum::ESTONIAN => null,
            LangEnum::FRENCH => null,
            LangEnum::HEBREW => null,
            LangEnum::ITALIAN => null,
            LangEnum::GEORGIAN => null,
            LangEnum::KAZAKH => null,
            LangEnum::KOREAN => null,
            LangEnum::LATVIAN => null,
            LangEnum::POLISH => null,
            LangEnum::PORTUGUESE_BRAZIL => null,
            LangEnum::RUSSIAN => null,
            LangEnum::SWEDISH => null,
            LangEnum::TURKISH => null,
            LangEnum::UKRAINIAN => null,
            LangEnum::CHINESE => null,
        ]);

        $this->assertNull($resource2->getBelarusian());
        $this->assertNull($resource2->getGerman());
        $this->assertNull($resource2->getSpanish());
        $this->assertNull($resource2->getSpanishUsa());
        $this->assertNull($resource2->getEstonian());
        $this->assertNull($resource2->getFrench());
        $this->assertNull($resource2->getHebrew());
        $this->assertNull($resource2->getItalian());
        $this->assertNull($resource2->getGeorgian());
        $this->assertNull($resource2->getKazakh());
        $this->assertNull($resource2->getKorean());
        $this->assertNull($resource2->getLatvian());
        $this->assertNull($resource2->getPolish());
        $this->assertNull($resource2->getPortugueseBrazil());
        $this->assertNull($resource2->getRussian());
        $this->assertNull($resource2->getSwedish());
        $this->assertNull($resource2->getTurkish());
        $this->assertNull($resource2->getUkrainian());
        $this->assertNull($resource2->getChinese());

        $resource3 = new Title([
            LangEnum::ENGLISH_USA => 'Title 1',
        ]);

        $this->assertNull($resource3->getBelarusian());
        $this->assertNull($resource3->getGerman());
        $this->assertNull($resource3->getSpanish());
        $this->assertNull($resource3->getSpanishUsa());
        $this->assertNull($resource3->getEstonian());
        $this->assertNull($resource3->getFrench());
        $this->assertNull($resource3->getHebrew());
        $this->assertNull($resource3->getItalian());
        $this->assertNull($resource3->getGeorgian());
        $this->assertNull($resource3->getKazakh());
        $this->assertNull($resource3->getKorean());
        $this->assertNull($resource3->getLatvian());
        $this->assertNull($resource3->getPolish());
        $this->assertNull($resource3->getPortugueseBrazil());
        $this->assertNull($resource3->getRussian());
        $this->assertNull($resource3->getSwedish());
        $this->assertNull($resource3->getTurkish());
        $this->assertNull($resource3->getUkrainian());
        $this->assertNull($resource3->getChinese());
    }

    /**
     * @dataProvider constructWithIncorrectArgumentDataProvider
     */
    public function testConstructWithIncorrectArgument(array $rawData, string $expectedExceptionMsg): void
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage($expectedExceptionMsg);

        new Title($rawData);
    }

    public function constructWithIncorrectArgumentDataProvider(): iterable
    {
        $correctSample = new ImmutableArrayObject([
            LangEnum::ENGLISH_USA => 'Title 1',
            LangEnum::BELARUSIAN => 'Title 2',
            LangEnum::GERMAN => 'Title 3',
            LangEnum::SPANISH => 'Title 4',
            LangEnum::SPANISH_USA => 'Title 5',
            LangEnum::ESTONIAN => 'Title 6',
            LangEnum::FRENCH => 'Title 7',
            LangEnum::HEBREW => 'Title 8',
            LangEnum::ITALIAN => 'Title 9',
            LangEnum::GEORGIAN => 'Title 10',
            LangEnum::KAZAKH => 'Title 11',
            LangEnum::KOREAN => 'Title 12',
            LangEnum::LATVIAN => 'Title 13',
            LangEnum::POLISH => 'Title 14',
            LangEnum::PORTUGUESE_BRAZIL => 'Title 15',
            LangEnum::RUSSIAN => 'Title 16',
            LangEnum::SWEDISH => 'Title 17',
            LangEnum::TURKISH => 'Title 18',
            LangEnum::UKRAINIAN => 'Title 19',
            LangEnum::CHINESE => 'Title 20',
        ]);

        yield from [
            [
                $correctSample->set([1])->toArray(),
                'Content of Title resource is not valid. Error: "[*]":"The value must be keyable array type"'
            ],
            [
                $correctSample->unset(LangEnum::ENGLISH_USA)->toArray(),
                sprintf(
                    'Content of Title resource is not valid. Error: "[*]":"Required fields [%s] are not set"',
                    LangEnum::ENGLISH_USA
                )
            ],
        ];

        yield from array_map(static fn ($langCode) => [
            $correctSample->set([$langCode => 1])->toArray(),
            sprintf(
                'Content of Title resource is not valid. Error: "%s":"The value does not match the string type"',
                $langCode
            )
        ], LangEnum::getAll());
    }
}
