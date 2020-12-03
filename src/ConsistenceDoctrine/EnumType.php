<?php
declare(strict_types = 1);

namespace Nepada\ConsistenceDoctrine;

use Consistence\Enum\Enum;

/**
 * @template TEnum of Enum
 */
final class EnumType
{

    /**
     * @var class-string<TEnum>
     */
    private string $type;

    /**
     * @param class-string<TEnum> $type
     */
    private function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @template T of Enum
     * @param class-string<T> $type
     * @return EnumType<T>
     */
    public static function fromClassName(string $type): EnumType
    {
        if (! class_exists($type)) {
            throw new \InvalidArgumentException(sprintf('Enum class "%s" not found.', $type));
        }

        if (! is_subclass_of($type, Enum::class)) {
            throw new \InvalidArgumentException(sprintf('Type "%s" must be a subtype of %s.', $type, Enum::class));
        }

        return new self($type);
    }

    public function getClassName(): string
    {
        return $this->type;
    }

    /**
     * @param TEnum|string|int|float|bool|null $value
     * @return string|int|float|bool|null
     */
    public function convertToDatabaseValue($value)
    {
        if ($value === null) {
            return null;
        }

        if (! $value instanceof $this->type) {
            $value = $this->type::get($value);
        }

        return $value->getValue();
    }

    /**
     * @param TEnum|string|int|float|bool|null $value
     * @return TEnum|null
     */
    public function convertToPHPValue($value): ?Enum
    {
        if ($value === null && ! $this->type::isValidValue($value)) {
            return $value;
        }

        if ($value instanceof $this->type) {
            return $value;
        }

        return $this->type::get($value);
    }

}
