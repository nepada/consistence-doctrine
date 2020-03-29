<?php
declare(strict_types = 1);

namespace NepadaTests\ConsistenceDoctrine\Fixtures\Enums;

use Consistence\Enum\Enum;

final class StringExample extends Enum
{

    public const FOO = 'foo';
    public const BAR = 'bar';

    public const INVALID_TYPE = 7;

}
