<?php
declare(strict_types = 1);

namespace NepadaTests\ConsistenceDoctrine\Fixtures\Types;

use Nepada\ConsistenceDoctrine\FloatEnumType;
use NepadaTests\ConsistenceDoctrine\Fixtures\Enums\FloatExample;

/**
 * @phpstan-extends FloatEnumType<FloatExample>
 */
class FloatExampleType extends FloatEnumType
{

    protected function getEnumClassName(): string
    {
        return FloatExample::class;
    }

}
