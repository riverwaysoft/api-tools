<?php

declare(strict_types=1);

namespace Riverwaysoft\ApiTools\Testing;
use PHPUnit\Framework\Assert;
use Spatie\Snapshots\Driver;
use Spatie\Snapshots\Exceptions\CantBeSerialized;

/**
 * This driver is responsible for 3 main things:
 *
 * 1) Show unicode characters unescaped in json, so you'll see Привет instead of \u041F\u0440\u0438\u0432\u0435\u0442
 * 2) Ignore property order. Example equal json {a: 1, b: 2} and {b: 2, a: 1}
 * 3) Ignore order of array elements in json. Example equal json arrays [{a: 1}, {b: 2}] and [{b: 2}, {a: 1}]
 */
class UnicodeIgnoreOrderJsonDriver implements Driver
{
    public function serialize($data): string
    {
        if (is_string($data)) {
            $data = json_decode($data);
        }

        if (is_resource($data)) {
            throw new CantBeSerialized('Resources can not be serialized to json');
        }

        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)."\n";
    }

    public function extension(): string
    {
        return 'json';
    }

    public static function sortJsonRecursively(mixed $array): mixed
    {
        ksort($array);

        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                if (count($value) > 0 && isset($value[0]) && is_array($value[0])) {
                    foreach ($value as &$v) {
                        $v = self::sortJsonRecursively($v);
                    }
                    usort($value, function ($a, $b) {
                        return strcmp(json_encode($a), json_encode($b));
                    });
                } else {
                    $value = self::sortJsonRecursively($value);
                }
            }
        }

        return $array;
    }

    public function match($expected, $actual): void
    {
        if (is_string($actual)) {
            $actual = json_decode($actual, true, 512, JSON_THROW_ON_ERROR);
        }
        $expected = json_decode($expected, true, 512, JSON_THROW_ON_ERROR);

        $actual = self::sortJsonRecursively($actual);
        $expected = self::sortJsonRecursively($expected);

        Assert::assertJsonStringEqualsJsonString(json_encode($expected), json_encode($actual));
    }
}
