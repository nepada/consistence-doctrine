<?php
declare(strict_types = 1);

namespace NepadaTests\ConsistenceDoctrine\Fixtures\Enums;

use Consistence\Enum\Enum;

final class IntegerExample extends Enum
{

    public const ZERO = 0;
    public const ONE = 1;

    public const INVALID_TYPE = 'invalid';

}
