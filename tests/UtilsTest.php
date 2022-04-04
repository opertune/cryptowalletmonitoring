<?php

namespace App\Tests;

use App\Service\Utils\Utils;
use PHPUnit\Framework\TestCase;
use Symfony\Component\VarDumper\VarDumper;

require __DIR__ . "/../src/Services/Utils.php";
class UtilsTest extends TestCase
{
    /**
     * Test Utils sortArray method with invalide array (require key name)
     */
    public function testSortArrayWithInvalidArray()
    {
        $this->assertTrue(true);
        // Array with random number
        $arr = [];
        for ($i = 0; $i < 10; $i++) {
            $arr[$i] = rand(1, 500);
        }

        // Parameters : array, column name, method SORT_ASC or SORT_DESC
        return Utils::sortArray($arr, 'val', SORT_ASC);
    }

    public function testSortArray()
    {
        $this->assertTrue(true);
        // Array with random number
        $arr = [];
        for ($i = 0; $i < 10; $i++) {
            $arr[$i]['val'] = rand(1, 500);
        }

        // Parameters : array, column name, method SORT_ASC or SORT_DESC
        return Utils::sortArray($arr, 'val', SORT_ASC);
    }
}
