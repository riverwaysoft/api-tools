<?php


namespace Riverwaysoft\ApiTools\PhoneNumber;


use libphonenumber\NumberParseException;
use App\Lib\PhoneNumber\TelephoneObject;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class ParseTelephoneException extends \RuntimeException
{

}