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
            [
                [[['a' => 1], ['b' => 2]]],
                [[['b' => 2], ['a' => 1]]],
                true,
            ],
            [
                [['a' => 1], ['b' => 2]],
                [['b' => 2], ['a' => 1]],
                true,
            ],
            [
                [['a' => 1], ['b' => 2]],
                [['b' => 3], ['a' => 1]],
                false,
            ],
            [
                json_decode('[
    {
        "id": "75f73e23-57d9-4210-9587-ff9579302f06",
        "createdAt": "2020-10-10T00:00:03+00:00",
        "title": "Стейк из тунца",
        "imageUrl": "https:\/\/eda.ru\/img\/eda\/c620x415\/s1.eda.ru\/StaticContent\/Photos\/120131084144\/170919141923\/p_O.jpg",
        "instruction": "Слегка растереть зерна кумина и кориандра. Взбить в миске абрикосовый джем, 1 столовую ложку соевого соуса, кумин и кориандр, соль и перец. Обмазать этим маринадом тунца со всех сторон, чтобы рыба была им полностью покрыта.",
        "ingredients": "\n            Листья кинзы (кориандра)\nИзображение материала\nСтейк\nЭНЦИКЛОПЕДИЯ\nЛИСТЬЯ КИНЗЫ (КОРИАНДРА)\nЛИСТЬЯ КИНЗЫ НЕЖНЕЕ ЛИСТЬЕВ ПЕТРУШКИ И АРОМАТНЕЕ — ЕСЛИ ТОЛЬКО «КЛОПОВЫЙ» ЗАПАХ НЕ ВЫЗЫВАЕТ НЕНУЖНЫХ АЛЛЮЗИЙ. ЛИСТЬЯМИ КИНЗЫ ЧАСТО ЗАМЕНЯЮТ ЛИСТЬЯ СВЕЖЕГО БАЗИЛИКА В ПЕСТО — ИХ ТАКЖЕ ЛЕГКО МОЖНО РАСТЕРЕТЬ В СТУПКЕ, И ТЕКСТУРА СОУСА НЕ ПРОИГРАЕТ КЛАССИЧЕСКОМУ РЕЦЕПТУ.\n253 РЕЦЕПТА\n3 столовые ложки сахара\nСемена кумина (зира)\n1 чайная ложка\nСемена кориандра\nсоль 10г\n            ",
        "isVegetarian": false,
        "isVegan": false,
        "isPescetarian": true,
        "isMeatEater": true,
        "isForBreakfast": true,
        "isForLunch": true,
        "isForDinner": false,
        "isForSecondDinner": false
    },
    {
        "id": "89009efd-5b22-45d3-9717-dc615b0b8ce0",
        "createdAt": "2020-10-10T00:00:03+00:00",
        "title": "Классическая шарлотка",
        "imageUrl": "https:\/\/eda.ru\/img\/eda\/c620x415\/s1.eda.ru\/StaticContent\/Photos\/120131084144\/170919141923\/p_O.jpg",
        "instruction": "Классическая шарлотка. Важное сладкое блюдо советской и постсоветской истории. Легкое, пышное тесто, максимум яблочной начинки — у шарлотки всегда был образ приятного, простого и при этом лакомого и диетического блюда. Ябло",
        "ingredients": "\n13719 РЕЦЕПТОВ\n1 стакан\nКуриное яйцо\n5 штук\nПшеничная мука\n1 стакан\nЯблоко\n7 штук\nРастительное масло\n1 столовая ложка\nСода\n1\/2чайной ложки\nсоль 10г\n            ",
        "isVegetarian": false,
        "isVegan": false,
        "isPescetarian": true,
        "isMeatEater": true,
        "isForBreakfast": false,
        "isForLunch": false,
        "isForDinner": true,
        "isForSecondDinner": false
    },
    {
        "id": "103b098f-809b-44ae-b71b-4f389a32054e",
        "createdAt": "2020-10-10T00:00:03+00:00",
        "title": "Бутерброд",
        "imageUrl": "https:\/\/eda.ru\/img\/eda\/c620x415\/s1.eda.ru\/StaticContent\/Photos\/120131084144\/170919141923\/p_O.jpg",
        "instruction": "Бутерброд инструкция",
        "ingredients": "Всё что нужно для бутерброда",
        "isVegetarian": false,
        "isVegan": false,
        "isPescetarian": false,
        "isMeatEater": false,
        "isForBreakfast": false,
        "isForLunch": false,
        "isForDinner": false,
        "isForSecondDinner": true
    }
]', true),
                json_decode('[
                    {
        "id": "103b098f-809b-44ae-b71b-4f389a32054e",
        "createdAt": "2020-10-10T00:00:03+00:00",
        "title": "Бутерброд",
        "imageUrl": "https:\/\/eda.ru\/img\/eda\/c620x415\/s1.eda.ru\/StaticContent\/Photos\/120131084144\/170919141923\/p_O.jpg",
        "instruction": "Бутерброд инструкция",
        "ingredients": "Всё что нужно для бутерброда",
        "isVegetarian": false,
        "isVegan": false,
        "isPescetarian": false,
        "isMeatEater": false,
        "isForBreakfast": false,
        "isForLunch": false,
        "isForDinner": false,
        "isForSecondDinner": true
    },
    {
        "id": "75f73e23-57d9-4210-9587-ff9579302f06",
        "createdAt": "2020-10-10T00:00:03+00:00",
        "title": "Стейк из тунца",
        "imageUrl": "https:\/\/eda.ru\/img\/eda\/c620x415\/s1.eda.ru\/StaticContent\/Photos\/120131084144\/170919141923\/p_O.jpg",
        "instruction": "Слегка растереть зерна кумина и кориандра. Взбить в миске абрикосовый джем, 1 столовую ложку соевого соуса, кумин и кориандр, соль и перец. Обмазать этим маринадом тунца со всех сторон, чтобы рыба была им полностью покрыта.",
        "ingredients": "\n            Листья кинзы (кориандра)\nИзображение материала\nСтейк\nЭНЦИКЛОПЕДИЯ\nЛИСТЬЯ КИНЗЫ (КОРИАНДРА)\nЛИСТЬЯ КИНЗЫ НЕЖНЕЕ ЛИСТЬЕВ ПЕТРУШКИ И АРОМАТНЕЕ — ЕСЛИ ТОЛЬКО «КЛОПОВЫЙ» ЗАПАХ НЕ ВЫЗЫВАЕТ НЕНУЖНЫХ АЛЛЮЗИЙ. ЛИСТЬЯМИ КИНЗЫ ЧАСТО ЗАМЕНЯЮТ ЛИСТЬЯ СВЕЖЕГО БАЗИЛИКА В ПЕСТО — ИХ ТАКЖЕ ЛЕГКО МОЖНО РАСТЕРЕТЬ В СТУПКЕ, И ТЕКСТУРА СОУСА НЕ ПРОИГРАЕТ КЛАССИЧЕСКОМУ РЕЦЕПТУ.\n253 РЕЦЕПТА\n3 столовые ложки сахара\nСемена кумина (зира)\n1 чайная ложка\nСемена кориандра\nсоль 10г\n            ",
        "isVegetarian": false,
        "isVegan": false,
        "isPescetarian": true,
        "isMeatEater": true,
        "isForBreakfast": true,
        "isForLunch": true,
        "isForDinner": false,
        "isForSecondDinner": false
    },
    {
        "id": "89009efd-5b22-45d3-9717-dc615b0b8ce0",
        "createdAt": "2020-10-10T00:00:03+00:00",
        "title": "Классическая шарлотка",
        "imageUrl": "https:\/\/eda.ru\/img\/eda\/c620x415\/s1.eda.ru\/StaticContent\/Photos\/120131084144\/170919141923\/p_O.jpg",
        "instruction": "Классическая шарлотка. Важное сладкое блюдо советской и постсоветской истории. Легкое, пышное тесто, максимум яблочной начинки — у шарлотки всегда был образ приятного, простого и при этом лакомого и диетического блюда. Ябло",
        "ingredients": "\n13719 РЕЦЕПТОВ\n1 стакан\nКуриное яйцо\n5 штук\nПшеничная мука\n1 стакан\nЯблоко\n7 штук\nРастительное масло\n1 столовая ложка\nСода\n1\/2чайной ложки\nсоль 10г\n            ",
        "isVegetarian": false,
        "isVegan": false,
        "isPescetarian": true,
        "isMeatEater": true,
        "isForBreakfast": false,
        "isForLunch": false,
        "isForDinner": true,
        "isForSecondDinner": false
    }
]', true),
                true,
            ]
        ];
    }
}
