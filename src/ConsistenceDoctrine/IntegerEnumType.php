<?php
declare(strict_types = 1);

namespace Nepada\ConsistenceDoctrine;

use Consistence\Enum\Enum;
use Consistence\Type\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;

/**
 * @phpstan-template TEnum of Enum
 */
abstract class IntegerEnumType extends IntegerType
{

    /**
     * @var EnumType<TEnum>|null
     */
    private ?EnumType $enumType = null;

    /**
     * @phpstan-return class-string<TEnum>
     * @return string
     */
    abstract protected function getEnumClassName(): string;

    public function getName(): string
    {
        return $this->getEnumType()->getClassName();
    }

    /**
     * @phpstan-param TEnum|int|null $value
     * @param Enum|int|null $value
     * @param AbstractPlatform $platform
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $enumValue = $this->getEnumType()->convertToDatabaseValue($value);
        if ($enumValue === null) {
            return $enumValue;
        }
        Type::checkType($enumValue, 'int');
        return parent::convertToDatabaseValue($enumValue, $platform);
    }

    /**
     * @phpstan-return TEnum|null
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return Enum|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?Enum
    {
        $enumValue = is_scalar($value) ? parent::convertToPHPValue($value, $platform) : $value;
        return $this->getEnumType()->convertToPHPValue($enumValue);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * @return EnumType<TEnum>
     */
    final protected function getEnumType(): EnumType
    {
        if ($this->enumType === null) {
            $this->enumType = EnumType::fromClassName($this->getEnumClassName());
        }

        return $this->enumType;
    }

}
