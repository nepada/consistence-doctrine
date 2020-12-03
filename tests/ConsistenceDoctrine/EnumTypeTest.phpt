<?php
declare(strict_types = 1);

namespace NepadaTests\ConsistenceDoctrine;

use Consistence\Enum\Enum;
use Nepada\ConsistenceDoctrine\EnumType;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @testCase
 */
class EnumTypeTest extends EnumTypeTestCase
{

    /**
     * @dataProvider getInvalidClassNames
     * @param class-string<Enum> $className
     * @param string $exceptionMessage
     */
    public function testInvalidClassName(string $className, string $exceptionMessage): void
    {
        Assert::exception(
            function () use ($className): void {
                EnumType::fromClassName($className);
            },
            \InvalidArgumentException::class,
            $exceptionMessage,
        );
    }

    /**
     * @return mixed[]
     */
    protected function getInvalidClassNames(): array
    {
        return [
            [
                'className' => 'NotFound',
                'exceptionMessage' => 'Enum class "NotFound" not found.',
            ],
            [
                'className' => self::class,
                'exceptionMessage' => 'Type "NepadaTests\ConsistenceDoctrine\EnumTypeTest" must be a subtype of Consistence\Enum\Enum.',
            ],
        ];
    }

}


(new EnumTypeTest())->run();
