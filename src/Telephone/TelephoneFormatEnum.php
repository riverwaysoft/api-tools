<?php

namespace Riverwaysoft\ApiTools\Telephone;

use MyCLabs\Enum\Enum;
/**
 * @method static TelephoneFormatEnum FORMAT_E164()
 * @method static TelephoneFormatEnum FORMAT_INTERNATIONAL()
 * @method static TelephoneFormatEnum FORMAT_NATIONAL()
 * @method static TelephoneFormatEnum FORMAT_RFC3966()
 */
class TelephoneFormatEnum extends Enum
{
    public const FORMAT_E164 = 0;
    public const FORMAT_INTERNATIONAL = 1;
    public const FORMAT_NATIONAL = 2;
    public const FORMAT_RFC3966 = 3;
}