<?php
declare(strict_types = 1);

namespace NepadaTests\ConsistenceDoctrine;

use Consistence\Enum\InvalidEnumValueException;
use Consistence\InvalidArgumentTypeException;
use NepadaTests\ConsistenceDoctrine\Fixtures\Enums\Invalid;
use NepadaTests\ConsistenceDoctrine\Fixtures\Enums\NullExample;
use NepadaTests\ConsistenceDoctrine\Fixtures\Enums\StringExample;
use NepadaTests\ConsistenceDoctrine\Fixtures\Types\NullableStringExampleType;
use NepadaTests\ConsistenceDoctrine\Fixtures\Types\StringExampleType;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class StringEnumTypeTest extends EnumTypeTestCase
{

    public function testGetName(): void
    {
        Assert::same(StringExample::class, $this->getStringExampleType()->getName());
    }

    public function testRequiresSQLCommentHint(): void
    {
        Assert::true($this->getStringExampleType()->requiresSQLCommentHint($this->platform));
    }

    public function testConvertToDatabaseValueFailsOnInvalidInput(): void
    {
        Assert::exception(
            function (): void {
                $this->getStringExampleType()->convertToDatabaseValue(Invalid::create(), $this->platform);
            },
            \Throwable::class,
        );
    }

    public function testConvertToDatabaseValueFailsOnInvalidEnumValueType(): void
    {
        Assert::exception(
            function (): void {
                $this->getStringExampleType()->convertToDatabaseValue(StringExample::get(StringExample::INVALID_TYPE), $this->platform);
            },
            InvalidArgumentTypeException::class,
        );
    }

    /**
     * @dataProvider getDataForConvertToDatabaseValue
     * @param mixed $value
     * @param string|null $expected
     */
    public function testConvertToDatabaseValueSucceeds($value, ?string $expected): void
    {
        Assert::same($expected, $this->getStringExampleType()->convertToDatabaseValue($value, $this->platform));
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
                'value' => StringExample::get(StringExample::FOO),
                'expected' => 'foo',
            ],
            [
                'value' => StringExample::BAR,
                'expected' => 'bar',
            ],
        ];
    }

    public function testConvertToPHPValueFailsOnUnknownEnumValue(): void
    {
        Assert::exception(
            function (): void {
                $this->getStringExampleType()->convertToPHPValue('unknown', $this->platform);
            },
            InvalidEnumValueException::class,
        );
    }

    /**
     * @dataProvider getDataForConvertToPHPValue
     * @param mixed $value
     * @param StringExample|null $expected
     */
    public function testConvertToPHPValueSucceeds($value, ?StringExample $expected): void
    {
        Assert::same($expected, $this->getStringExampleType()->convertToPHPValue($value, $this->platform));
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
                'value' => StringExample::get(StringExample::FOO),
                'expected' => StringExample::get(StringExample::FOO),
            ],
            [
                'value' => 'bar',
                'expected' => StringExample::get(StringExample::BAR),
            ],
        ];
    }

    public function testGetSQLDeclaration(): void
    {
        $this->platform->shouldReceive('getVarcharTypeDeclarationSQL')->with(['length' => 255])->andReturn('MOCKVARCHAR');
        $declaration = $this->getStringExampleType()->getSQLDeclaration(['length' => 255], $this->platform);
        Assert::same('MOCKVARCHAR', $declaration);
    }

    public function testNullableEnum(): void
    {
        $type = $this->registerType(NullExample::class, NullableStringExampleType::class);
        $enum = NullExample::get(NullExample::EMPTY);
        Assert::null($type->convertToDatabaseValue($enum, $this->platform));
        Assert::same($enum, $type->convertToPHPValue(null, $this->platform));
    }

    private function getStringExampleType(): StringExampleType
    {
        return $this->registerType(StringExample::class, StringExampleType::class);
    }

}


(new StringEnumTypeTest())->run();
