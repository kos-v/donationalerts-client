<?php

declare(strict_types=1);

namespace Kosv\DonationalertsClient\Tests\API;

use Kosv\DonationalertsClient\API\RawResourceExtractor;
use Kosv\DonationalertsClient\Exceptions\ValidateException;
use PHPUnit\Framework\TestCase;

final class RawResourceExtractorTest extends TestCase
{
    public function testExtractContent(): void
    {
        $this->assertEquals(
            (new RawResourceExtractor([
                'data' => [
                    'foo' => 1,
                    'bar' => 'val'
                ]
            ]))->extractContent(),
            [
                'foo' => 1,
                'bar' => 'val'
            ]
        );

        $this->assertEquals(
            (new RawResourceExtractor([
                'content' => [
                    'foo' => 1,
                    'bar' => 'val'
                ]
            ]))->extractContent('content'),
            [
                'foo' => 1,
                'bar' => 'val'
            ]
        );

        $this->assertEquals(
            (new RawResourceExtractor([
                'content' => [
                    [
                        'foo' => 1,
                        'bar' => 'val'
                    ]
                ]
            ]))->extractContent('content', false),
            [
                [
                    'foo' => 1,
                    'bar' => 'val'
                ]
            ]
        );
    }

    public function testExtractContentWithoutContentKey(): void
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('Body is not valid. Error: "[*]":"Required fields [data] are not set"');

        $this->assertEquals(
            (new RawResourceExtractor([
                'other' => [
                    'foo' => 1,
                    'bar' => 'val'
                ]
            ]))->extractContent(),
            [
                'foo' => 1,
                'bar' => 'val'
            ]
        );
    }

    public function testExtractMetadata(): void
    {
        $this->assertEquals(
            (new RawResourceExtractor([
                'meta' => [
                    'foo' => 1,
                    'bar' => 'val'
                ]
            ]))->extractMetadata(),
            [
                'foo' => 1,
                'bar' => 'val'
            ]
        );

        $this->assertEquals(
            (new RawResourceExtractor([
                'metadata' => [
                    'foo' => 1,
                    'bar' => 'val'
                ]
            ]))->extractMetadata('metadata'),
            [
                'foo' => 1,
                'bar' => 'val'
            ]
        );
    }

    public function testExtractMetadataWithoutMetadataKey(): void
    {
        $this->expectException(ValidateException::class);
        $this->expectExceptionMessage('Body is not valid. Error: "[*]":"Required fields [meta] are not set"');

        $this->assertEquals(
            (new RawResourceExtractor([
                'other' => [
                    'foo' => 1,
                    'bar' => 'val'
                ]
            ]))->extractMetadata(),
            [
                'foo' => 1,
                'bar' => 'val'
            ]
        );
    }
}
