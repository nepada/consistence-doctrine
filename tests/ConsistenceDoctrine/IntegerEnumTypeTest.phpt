<?php
declare(strict_types = 1);

namespace NepadaTests\ConsistenceDoctrine;

use Consistence\Enum\InvalidEnumValueException;
use Consistence\InvalidArgumentTypeException;
use NepadaTests\ConsistenceDoctrine\Fixtures\Enums\IntegerExample;
use NepadaTests\ConsistenceDoctrine\Fixtures\Enums\Invalid;
use NepadaTests\ConsistenceDoctrine\Fixtures\Enums\NullExample;
use NepadaTests\ConsistenceDoctrine\Fixtures\Types\IntegerExampleType;
use NepadaTests\ConsistenceDoctrine\Fixtures\Types\NullableIntegerExampleType;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class IntegerEnumTypeTest extends EnumTypeTestCase
{

    public function testGetName(): void
    {
        Assert::same(IntegerExample::class, $this->getIntegerExampleType()->getName());
    }

    public function testRequiresSQLCommentHint(): void
    {
        Assert::true($this->getIntegerExampleType()->requiresSQLCommentHint($this->platform));
    }

    public function testConvertToDatabaseValueFailsOnInvalidInput(): void
    {
        Assert::exception(
            function (): void {
                $this->getIntegerExampleType()->convertToDatabaseValue(Invalid::create(), $this->platform);
            },
            \Throwable::class,
        );
    }

    public function testConvertToDatabaseValueFailsOnInvalidEnumValueType(): void
    {
        Assert::exception(
            function (): void {
                $this->getIntegerExampleType()->convertToDatabaseValue(IntegerExample::get(IntegerExample::INVALID_TYPE), $this->platform);
            },
            InvalidArgumentTypeException::class,
        );
    }

    /**
     * @dataProvider getDataForConvertToDatabaseValue
     * @param IntegerExample|int|null $value
     * @param int|null $expected
     */
    public function testConvertToDatabaseValueSucceeds($value, ?int $expected): void
    {
        Assert::same($expected, $this->getIntegerExampleType()->convertToDatabaseValue($value, $this->platform));
    }

    /**
     * @return mixed[]
     */
    protected function getDataForConvertToDatabaseValue(): array
    {
        return [
            [
                'value' => null,
                'expected' => null,
            ],
            [
                'value' => IntegerExample::get(IntegerExample::ZERO),
                'expected' => 0,
            ],
            [
                'value' => IntegerExample::ONE,
                'expected' => 1,
            ],
        ];
    }

    public function testConvertToPHPValueFailsOnUnknownEnumValue(): void
    {
        Assert::exception(
            function (): void {
                $this->getIntegerExampleType()->convertToPHPValue(42, $this->platform);
            },
            InvalidEnumValueException::class,
        );
    }

    /**
     * @dataProvider getDataForConvertToPHPValue
     * @param IntegerExample|int|null $value
     * @param IntegerExample|null $expected
     */
    public function testConvertToPHPValueSucceeds($value, ?IntegerExample $expected): void
    {
        Assert::same($expected, $this->getIntegerExampleType()->convertToPHPValue($value, $this->platform));
    }

    /**
     * @return mixed[]
     */
    protected function getDataForConvertToPHPValue(): array
    {
        return [
            [
                'value' => null,
                'expected' => null,
            ],
            [
                'value' => IntegerExample::get(IntegerExample::ZERO),
                'expected' => IntegerExample::get(IntegerExample::ZERO),
            ],
            [
                'value' => 1,
                'expected' => IntegerExample::get(IntegerExample::ONE),
            ],
        ];
    }

    public function testGetSQLDeclaration(): void
    {
        $this->platform->shouldReceive('getIntegerTypeDeclarationSQL')->with([])->andReturn('MOCKINT');
        $declaration = $this->getIntegerExampleType()->getSQLDeclaration([], $this->platform);
        Assert::same('MOCKINT', $declaration);
    }

    public function testNullableEnum(): void
    {
        $type = $this->registerType(NullExample::class, NullableIntegerExampleType::class);
        $enum = NullExample::get(NullExample::EMPTY);
        Assert::null($type->convertToDatabaseValue($enum, $this->platform));
        Assert::same($enum, $type->convertToPHPValue(null, $this->platform));
    }

    private function getIntegerExampleType(): IntegerExampleType
    {
        return $this->registerType(IntegerExample::class, IntegerExampleType::class);
    }

}


(new IntegerEnumTypeTest())->run();
