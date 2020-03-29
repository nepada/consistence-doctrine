<?php
declare(strict_types = 1);

namespace NepadaTests\ConsistenceDoctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Mockery\MockInterface;
use NepadaTests\TestCase;
use Tester\Assert;

abstract class EnumTypeTestCase extends TestCase
{

    /** @var AbstractPlatform|MockInterface */
    protected AbstractPlatform $platform;

    protected function setUp(): void
    {
        parent::setUp();
        $this->platform = \Mockery::mock(AbstractPlatform::class);
        $this->platform->makePartial();
    }

    /**
     * @phpstan-template TType of Type
     * @phpstan-param class-string<TType> $typeClassName
     * @phpstan-return TType
     * @param string $name
     * @param string $typeClassName
     * @return Type
     */
    protected function registerType(string $name, string $typeClassName): Type
    {
        if (Type::hasType($name)) {
            Type::overrideType($name, $typeClassName);

        } else {
            Type::addType($name, $typeClassName);
        }

        $type = Type::getType($name);
        Assert::type($typeClassName, $type);

        return $type;
    }

}
