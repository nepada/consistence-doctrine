<?php
declare(strict_types = 1);

namespace Nepada\ConsistenceDoctrine;

use Consistence\Enum\Enum;
use Consistence\Type\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\FloatType;

/**
 * @template TEnum of Enum
 */
abstract class FloatEnumType extends FloatType
{

    /**
     * @var EnumType<TEnum>|null
     */
    private ?EnumType $enumType = null;

    /**
     * @return class-string<TEnum>
     */
    abstract protected function getEnumClassName(): string;

    public function getName(): string
    {
        return $this->getEnumType()->getClassName();
    }

    /**
     * @param TEnum|float|null $value
     * @param AbstractPlatform $platform
     * @return mixed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $enumValue = $this->getEnumType()->convertToDatabaseValue($value);
        if ($enumValue === null) {
            return $enumValue;
        }
        Type::checkType($enumValue, 'float');
        return parent::convertToDatabaseValue($enumValue, $platform);
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return TEnum|null
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
