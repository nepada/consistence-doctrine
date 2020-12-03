<?php
declare(strict_types = 1);

namespace NepadaTests\ConsistenceDoctrine\Fixtures\Types;

use Nepada\ConsistenceDoctrine\FloatEnumType;
use NepadaTests\ConsistenceDoctrine\Fixtures\Enums\NullExample;

/**
 * @extends FloatEnumType<NullExample>
 */
class NullableFloatExampleType extends FloatEnumType
{

    protected function getEnumClassName(): string
    {
        return NullExample::class;
    }

}
