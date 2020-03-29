<?php
declare(strict_types = 1);

namespace NepadaTests\ConsistenceDoctrine\Fixtures\Types;

use Nepada\ConsistenceDoctrine\StringEnumType;
use NepadaTests\ConsistenceDoctrine\Fixtures\Enums\StringExample;

/**
 * @phpstan-extends StringEnumType<StringExample>
 */
class StringExampleType extends StringEnumType
{

    protected function getEnumClassName(): string
    {
        return StringExample::class;
    }

}
