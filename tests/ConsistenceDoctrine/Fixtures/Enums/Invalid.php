<?php
declare(strict_types = 1);

namespace NepadaTests\ConsistenceDoctrine\Fixtures\Enums;

use Consistence\Enum\Enum;

final class Invalid extends Enum
{

    public const INVALID = 'invalid';

    public static function create(): Invalid
    {
        return self::get(self::INVALID);
    }

}
