<?php

declare(strict_types=1);

namespace Arrow\Sudoku;

class Printer
{
    /**
     * @param array $sudoku
     */
    public static function show(array $sudoku): void {
        echo PHP_EOL;
        for($i=0;$i<9;$i++){
            for($j=0;$j<9;$j++){
                echo (int) $sudoku[$i][$j] . ' ';
            }

            echo PHP_EOL;
        }


        echo PHP_EOL;
    }
}