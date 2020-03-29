<?php
declare(strict_types = 1);

namespace NepadaTests\ConsistenceDoctrine\Fixtures\Enums;

use Consistence\Enum\Enum;

final class FloatExample extends Enum
{

    public const PI = 3.14;
    public const E = 2.72;

    public const INVALID_TYPE = 'invalid';

}
