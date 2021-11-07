<?php
declare(strict_types = 1);

namespace NepadaTests\ConsistenceDoctrine;

use Consistence\Enum\InvalidEnumValueException;
use Consistence\InvalidArgumentTypeException;
use NepadaTests\ConsistenceDoctrine\Fixtures\Enums\BooleanExample;
use NepadaTests\ConsistenceDoctrine\Fixtures\Enums\Invalid;
use NepadaTests\ConsistenceDoctrine\Fixtures\Enums\NullExample;
use NepadaTests\ConsistenceDoctrine\Fixtures\Types\BooleanExampleType;
use NepadaTests\ConsistenceDoctrine\Fixtures\Types\NullableBooleanExampleType;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class BooleanEnumTypeTest extends EnumTypeTestCase
{

    public function testGetName(): void
    {
        Assert::same(BooleanExample::class, $this->getBooleanExampleType()->getName());
    }

    public function testRequiresSQLCommentHint(): void
    {
        Assert::true($this->getBooleanExampleType()->requiresSQLCommentHint($this->platform));
    }

    public function testConvertToDatabaseValueFailsOnInvalidInput(): void
    {
        Assert::exception(
            function (): void {
                $this->getBooleanExampleType()->convertToDatabaseValue(Invalid::create(), $this->platform);
            },
            \Throwable::class,
        );
    }

    public function testConvertToDatabaseValueFailsOnInvalidEnumValueType(): void
    {
        Assert::exception(
            function (): void {
                $this->getBooleanExampleType()->convertToDatabaseValue(BooleanExample::get(BooleanExample::INVALID_TYPE), $this->platform);
            },
            InvalidArgumentTypeException::class,
        );
    }

    /**
     * @dataProvider getDataForConvertToDatabaseValue
     * @param BooleanExample|bool|null $value
     * @param int|null $expected
     */
    public function testConvertToDatabaseValueSucceeds($value, ?int $expected): void
    {
        Assert::same($expected, $this->getBooleanExampleType()->convertToDatabaseValue($value, $this->platform));
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
                'value' => BooleanExample::get(BooleanExample::TRUE),
                'expected' => 1,
            ],
            [
                'value' => BooleanExample::TRUE,
                'expected' => 1,
            ],
        ];
    }

    public function testConvertToPHPValueFailsOnUnknownEnumValue(): void
    {
        Assert::exception(
            function (): void {
                $this->getBooleanExampleType()->convertToPHPValue(0, $this->platform);
            },
            InvalidEnumValueException::class,
        );
    }

    /**
     * @dataProvider getDataForConvertToPHPValue
     * @param mixed $value
     * @param BooleanExample|null $expected
     */
    public function testConvertToPHPValueSucceeds($value, ?BooleanExample $expected): void
    {
        Assert::same($expected, $this->getBooleanExampleType()->convertToPHPValue($value, $this->platform));
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
                'value' => BooleanExample::get(BooleanExample::TRUE),
                'expected' => BooleanExample::get(BooleanExample::TRUE),
            ],
            [
                'value' => 1,
                'expected' => BooleanExample::get(BooleanExample::TRUE),
            ],
        ];
    }

    public function testGetSQLDeclaration(): void
    {
        $this->platform->shouldReceive('getBooleanTypeDeclarationSQL')->with([])->andReturn('MOCKBOOL');
        $declaration = $this->getBooleanExampleType()->getSQLDeclaration([], $this->platform);
        Assert::same('MOCKBOOL', $declaration);
    }

    public function testNullableEnum(): void
    {
        $type = $this->registerType(NullExample::class, NullableBooleanExampleType::class);
        $enum = NullExample::get(NullExample::EMPTY);
        Assert::null($type->convertToDatabaseValue($enum, $this->platform));
        Assert::same($enum, $type->convertToPHPValue(null, $this->platform));
    }

    private function getBooleanExampleType(): BooleanExampleType
    {
        return $this->registerType(BooleanExample::class, BooleanExampleType::class);
    }

}


(new BooleanEnumTypeTest())->run();
