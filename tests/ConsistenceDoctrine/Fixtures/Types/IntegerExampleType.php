<?php
declare(strict_types = 1);

namespace NepadaTests\ConsistenceDoctrine\Fixtures\Types;

use Nepada\ConsistenceDoctrine\IntegerEnumType;
use NepadaTests\ConsistenceDoctrine\Fixtures\Enums\IntegerExample;

/**
 * @extends IntegerEnumType<IntegerExample>
 */
class IntegerExampleType extends IntegerEnumType
{

    protected function getEnumClassName(): string
    {
        return IntegerExample::class;
    }

}
