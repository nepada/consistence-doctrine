<?php
declare(strict_types = 1);

namespace NepadaTests\ConsistenceDoctrine\Fixtures\Types;

use Nepada\ConsistenceDoctrine\IntegerEnumType;
use NepadaTests\ConsistenceDoctrine\Fixtures\Enums\NullExample;

/**
 * @phpstan-extends IntegerEnumType<NullExample>
 */
class NullableIntegerExampleType extends IntegerEnumType
{

    protected function getEnumClassName(): string
    {
        return NullExample::class;
    }

}
