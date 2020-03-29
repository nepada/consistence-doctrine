<?php
declare(strict_types = 1);

namespace NepadaTests\ConsistenceDoctrine;

use Consistence\Enum\InvalidEnumValueException;
use Consistence\InvalidArgumentTypeException;
use NepadaTests\ConsistenceDoctrine\Fixtures\Enums\FloatExample;
use NepadaTests\ConsistenceDoctrine\Fixtures\Enums\Invalid;
use NepadaTests\ConsistenceDoctrine\Fixtures\Enums\NullExample;
use NepadaTests\ConsistenceDoctrine\Fixtures\Types\FloatExampleType;
use NepadaTests\ConsistenceDoctrine\Fixtures\Types\NullableFloatExampleType;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class FloatEnumTypeTest extends EnumTypeTestCase
{

    public function testGetName(): void
    {
        Assert::same(FloatExample::class, $this->getFloatExampleType()->getName());
    }

    public function testRequiresSQLCommentHint(): void
    {
        Assert::true($this->getFloatExampleType()->requiresSQLCommentHint($this->platform));
    }

    public function testConvertToDatabaseValueFailsOnInvalidInput(): void
    {
        Assert::exception(
            function (): void {
                $this->getFloatExampleType()->convertToDatabaseValue(Invalid::create(), $this->platform);
            },
            \Throwable::class,
        );
    }

    public function testConvertToDatabaseValueFailsOnInvalidEnumValueType(): void
    {
        Assert::exception(
            function (): void {
                $this->getFloatExampleType()->convertToDatabaseValue(FloatExample::get(FloatExample::INVALID_TYPE), $this->platform);
            },
            InvalidArgumentTypeException::class,
        );
    }

    /**
     * @dataProvider getDataForConvertToDatabaseValue
     * @param mixed $value
     * @param float|null $expected
     */
    public function testConvertToDatabaseValueSucceeds($value, ?float $expected): void
    {
        Assert::same($expected, $this->getFloatExampleType()->convertToDatabaseValue($value, $this->platform));
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
                'value' => FloatExample::get(FloatExample::PI),
                'expected' => 3.14,
            ],
            [
                'value' => FloatExample::E,
                'expected' => 2.72,
            ],
        ];
    }

    public function testConvertToPHPValueFailsOnUnknownEnumValue(): void
    {
        Assert::exception(
            function (): void {
                $this->getFloatExampleType()->convertToPHPValue(1.0, $this->platform);
            },
            InvalidEnumValueException::class,
        );
    }

    /**
     * @dataProvider getDataForConvertToPHPValue
     * @param mixed $value
     * @param FloatExample|null $expected
     */
    public function testConvertToPHPValueSucceeds($value, ?FloatExample $expected): void
    {
        Assert::same($expected, $this->getFloatExampleType()->convertToPHPValue($value, $this->platform));
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
                'value' => FloatExample::get(FloatExample::PI),
                'expected' => FloatExample::get(FloatExample::PI),
            ],
            [
                'value' => 2.72,
                'expected' => FloatExample::get(FloatExample::E),
            ],
        ];
    }

    public function testGetSQLDeclaration(): void
    {
        $this->platform->shouldReceive('getFloatDeclarationSQL')->with([])->andReturn('MOCKFLOAT');
        $declaration = $this->getFloatExampleType()->getSQLDeclaration([], $this->platform);
        Assert::same('MOCKFLOAT', $declaration);
    }

    public function testNullableEnum(): void
    {
        $type = $this->registerType(NullExample::class, NullableFloatExampleType::class);
        $enum = NullExample::get(NullExample::EMPTY);
        Assert::null($type->convertToDatabaseValue($enum, $this->platform));
        Assert::same($enum, $type->convertToPHPValue(null, $this->platform));
    }

    private function getFloatExampleType(): FloatExampleType
    {
        return $this->registerType(FloatExample::class, FloatExampleType::class);
    }

}


(new FloatEnumTypeTest())->run();
