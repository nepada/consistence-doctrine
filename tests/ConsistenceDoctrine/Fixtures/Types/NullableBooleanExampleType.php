<?php
declare(strict_types = 1);

namespace NepadaTests\ConsistenceDoctrine\Fixtures\Types;

use Nepada\ConsistenceDoctrine\BooleanEnumType;
use NepadaTests\ConsistenceDoctrine\Fixtures\Enums\NullExample;

/**
 * @extends BooleanEnumType<NullExample>
 */
class NullableBooleanExampleType extends BooleanEnumType
{

    protected function getEnumClassName(): string
    {
        return NullExample::class;
    }

}
