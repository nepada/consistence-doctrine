<?php
declare(strict_types = 1);

namespace NepadaTests\ConsistenceDoctrine\Fixtures\Types;

use Nepada\ConsistenceDoctrine\StringEnumType;
use NepadaTests\ConsistenceDoctrine\Fixtures\Enums\NullExample;

/**
 * @extends StringEnumType<NullExample>
 */
class NullableStringExampleType extends StringEnumType
{

    protected function getEnumClassName(): string
    {
        return NullExample::class;
    }

}
