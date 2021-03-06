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

    /**
     * @var AbstractPlatform|MockInterface
     */
    protected AbstractPlatform $platform;

    protected function setUp(): void
    {
        parent::setUp();
        $this->platform = \Mockery::mock(AbstractPlatform::class);
        $this->platform->makePartial();
    }

    /**
     * @template TType of Type
     * @param string $name
     * @param class-string<TType> $typeClassName
     * @return TType
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
