<?php

namespace App\Tests;

use App\Service\Utils;
use PHPUnit\Framework\TestCase;

require __DIR__ . "/../src/Service/Utils.php";

class SortArrayTest extends TestCase
{
    /**
     * Test Utils sortArray: return sort array on the given column
     */
    public function testSortArray()
    {
        // Array with random number
        $arr = [
            0 => [
                'name' => 'test',
                'quantity' => 10,
                'val' => 50
            ],
            1 => [
                'name' => 'test1',
                'quantity' => 20,
                'val' => 3
            ],
            2 => [
                'name' => 'test2',
                'quantity' => 30,
                'val' => 42
            ],
            3 => [
                'name' => 'test3',
                'quantity' => 40,
                'val' => 14
            ],
            4 => [
                'name' => 'test4',
                'quantity' => 50,
                'val' => 38
            ]
        ];

        // Sort array on val column
        $arr = Utils::sortArray($arr, 'val', SORT_ASC);

        // Compare two array
        $this->assertEquals([
            0 => [
                'name' => 'test1',
                'quantity' => 20,
                "val" => 3
            ],
            1 => [
                'name' => 'test3',
                'quantity' => 40,
                "val" => 14
            ],
            2 => [
                'name' => 'test4',
                'quantity' => 50,
                "val" => 38
            ],
            3 => [
                'name' => 'test2',
                'quantity' => 30,
                "val" => 42
            ],
            4 => [
                'name' => 'test',
                'quantity' => 10,
                "val" => 50
            ],
        ], $arr);
    }

    /**
     * Test Utils sortArray method with invalide array (require key name)
     */
    public function testSortArrayWithInvalidArray()
    {
        // Array with random number
        $arr = [
            0 => [
                'name' => 'test',
                'quantity' => 10,
                'val' => 50
            ],
            1 => [
                'name' => 'test1',
                'quantity' => 20,
                'val' => 3
            ],
            2 => [
                'name' => 'test2',
                'quantity' => 30,
                'val' => 42
            ],
            3 => [
                'name' => 'test3',
                'quantity' => 40,
                'val' => 14
            ],
            4 => [
                'name' => 'test4',
                'quantity' => 50,
                'val' => 38
            ]
        ];

        // Sort array on val column
        $arr = Utils::sortArray($arr, 'val', SORT_ASC);

        // Compare two array
        $this->assertEquals([
            0 => [
                'name' => 'test1',
                'quantity' => 20,
                "val" => 50
            ],
            1 => [
                'name' => 'test3',
                'quantity' => 40,
                "val" => 42
            ],
            2 => [
                'name' => 'test4',
                'quantity' => 50,
                "val" => 38
            ],
            3 => [
                'name' => 'test2',
                'quantity' => 30,
                "val" => 14
            ],
            4 => [
                'name' => 'test',
                'quantity' => 10,
                "val" => 3
            ],
        ], $arr);
    }
}
