<?php
declare(strict_types = 1);

namespace NepadaTests\ConsistenceDoctrine\Fixtures\Types;

use Nepada\ConsistenceDoctrine\BooleanEnumType;
use NepadaTests\ConsistenceDoctrine\Fixtures\Enums\BooleanExample;

/**
 * @extends BooleanEnumType<BooleanExample>
 */
class BooleanExampleType extends BooleanEnumType
{

    protected function getEnumClassName(): string
    {
        return BooleanExample::class;
    }

}
