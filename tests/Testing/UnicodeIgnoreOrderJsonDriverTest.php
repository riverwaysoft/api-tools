<?php

namespace Riverwaysoft\ApiTools\Tests\Testing;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Riverwaysoft\ApiTools\Testing\UnicodeIgnoreOrderJsonDriver;
use Spatie\Snapshots\MatchesSnapshots;

class UnicodeIgnoreOrderJsonDriverTest extends TestCase
{
    use MatchesSnapshots;

    /**
     * @dataProvider jsonProvider
     */
    public function testCompare(mixed $json1, mixed $json2, bool $areEqual): void
    {
        $expected = UnicodeIgnoreOrderJsonDriver::sortJsonRecursively($json1);
        $actual = UnicodeIgnoreOrderJsonDriver::sortJsonRecursively($json2);

        if ($areEqual) {
            Assert::assertJsonStringEqualsJsonString(json_encode($expected), json_encode($actual));
        } else {
            Assert::assertJsonStringNotEqualsJsonString(json_encode($expected), json_encode($actual));
        }
    }

    public function jsonProvider(): mixed
    {
        return [
            [
                ['b' => 1, 'a' => 1, 'c' => [['a' => 1], ['b' => 2]]],
                ['b' => 1, 'a' => 1, 'c' => [['b' => 2], ['a' => 1]]],
                true,
            ],
            [
                ['b' => 1, 'a' => 1],
                ['a' => 1],
                false,
            ],
            [
                ['b' => 1, 'a' => 1],
                ['a' => 1, 'b' => 1, 'c' => 2],
                false,
            ],
            [
                ['b' => 2, 'a' => 1],
                ['a' => 1, 'b' => 2],
                true,
            ],
            [
                json_decode('{"key": [{"a": 1}, {"b": 3}]}', true),
                json_decode('{"key": [{"b": 2}, {"a": 1}]}', true),
                false,
            ],
            [
                json_decode('{"key": [{"a": 1}, {"b": {"c": 2, "d": 3}}, {"e": 4}]}', true),
                json_decode('{"key": [{"b": {"d": 3, "c": 2}}, {"e": 4}, {"a": 1}]}', true),
                true
            ],
            [
                json_decode('{"key": [{"a": 1}, {"b": {"c": 2, "d": 3}}, {"e": 5}]}', true),
                json_decode('{"key": [{"b": {"d": 3, "c": 2}}, {"e": 4}, {"a": 1}]}', true),
                false
            ],
        ];
    }
}
