<?php
declare(strict_types = 1);

namespace NepadaTests\ConsistenceDoctrine\Fixtures\Enums;

use Consistence\Enum\Enum;

final class BooleanExample extends Enum
{

    public const TRUE = true;

    public const INVALID_TYPE = 'invalid';

}
