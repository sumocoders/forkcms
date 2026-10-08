<?php

namespace Common\Tests\Core;

use Common\Core\RequestParameter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\InputBag;

class RequestParameterTest extends TestCase
{
    public function testMissingKeyFallsBackToTheDefault(): void
    {
        $bag = new InputBag([]);

        self::assertSame(0, RequestParameter::getInt($bag, 'id'));
        self::assertSame(-1, RequestParameter::getInt($bag, 'dropped_on', -1));
        self::assertSame(50, RequestParameter::getInt($bag, 'length', 50));
    }

    /**
     * Symfony 6 throws on these, where Symfony 5 returned the default. The values are the ones the
     * backend actually produces: an unselected filter dropdown sends "", and the MediaLibrary
     * javascript concatenates an uninitialised variable into "folder_id=undefined".
     *
     * @dataProvider malformedIntegerProvider
     */
    public function testMalformedIntegerFallsBackToTheDefault(string $value): void
    {
        $bag = new InputBag(['id' => $value]);

        self::assertSame(0, RequestParameter::getInt($bag, 'id'));
        self::assertSame(-1, RequestParameter::getInt($bag, 'id', -1));
    }

    public function malformedIntegerProvider(): array
    {
        return [
            'empty' => [''],
            'word' => ['abc'],
            'float' => ['1.5'],
            'undefined' => ['undefined'],
        ];
    }

    public function testValidIntegerIsReturned(): void
    {
        self::assertSame(42, RequestParameter::getInt(new InputBag(['id' => '42']), 'id'));
        self::assertSame(0, RequestParameter::getInt(new InputBag(['id' => '0']), 'id'));
        self::assertSame(-7, RequestParameter::getInt(new InputBag(['id' => '-7']), 'id'));
    }

    public function testArrayIsStillRejected(): void
    {
        $this->expectException(BadRequestException::class);

        RequestParameter::getInt(new InputBag(['id' => ['1']]), 'id');
    }

    /**
     * @dataProvider booleanProvider
     */
    public function testGetBoolean(string $value, bool $expected): void
    {
        self::assertSame($expected, RequestParameter::getBoolean(new InputBag(['visible' => $value]), 'visible'));
    }

    public function booleanProvider(): array
    {
        return [
            'one' => ['1', true],
            'true' => ['true', true],
            'on' => ['on', true],
            'zero' => ['0', false],
            'false' => ['false', false],
            'empty' => ['', false],
        ];
    }

    public function testMalformedBooleanFallsBackToTheDefault(): void
    {
        $bag = new InputBag(['visible' => 'maybe']);

        self::assertFalse(RequestParameter::getBoolean($bag, 'visible'));
        self::assertTrue(RequestParameter::getBoolean($bag, 'visible', true));
    }

    public function testMissingBooleanFallsBackToTheDefault(): void
    {
        $bag = new InputBag([]);

        self::assertFalse(RequestParameter::getBoolean($bag, 'visible'));
        self::assertTrue(RequestParameter::getBoolean($bag, 'visible', true));
    }
}
