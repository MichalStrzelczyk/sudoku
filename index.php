<?php

require_once './vendor/autoload.php';

$sudoku = [
  [8,9,6,null,null,null,null,null,null],
  [4,1,2,7,5,8,null,9,3],
  [3,null,7,6,2,9,1,4,8],
  [2,3,4,9,6,5,null,1,7],
  [7,8,5,null,3,1,4,6,9],
  [9,6,1,4,8,7,2,3,5],
  [6,null,9,null,7,2,3,5,1],
  [1,null,null,5,9,4,7,null,6],
  [null,7,8,3,1,6,9,2,4]
];

$sudoku = [
    [null,null,null,null,null,null,null,null,null],
    [null,null,null,null,null,null,null,null,null],
    [null,null,null,null,null,null,null,null,null],
    [null,null,null,null,null,null,null,null,null],
    [null,null,null,null,null,null,null,null,null],
    [null,null,null,null,null,null,null,null,null],
    [null,null,null,null,null,null,null,null,null],
    [null,null,null,null,null,null,null,null,null],
    [null,null,null,null,null,null,null,null,null],
];

/*$input = [
    [null, 1, 6, 4, null, null, null, null, 8],
    [null, 4, null, 9, null, 6, 1, null, null],
    [9, 3, null, null, null, null, null, 7, 6],

    [null, 8, null, null, null, 4, 6, 1, null],
    [null, null, null, 6, null, 2, null, null, null],
    [null, 5, 4, 3, null, null, null, 2, null],

    [5, 9, null, null, null, null, null, 8, 4],
    [null, null, 7, 5, null, 3, null, 9, null],
    [4, null, null, null, null, 7, 3, 6, null],
];*/

/*$input = [
    [null, 1, null, 4, null, null, null, null, 8],
    [null, 4, null, 9, null, null, 1, null, null],
    [9, 3, null, null, null, null, null, 7, 6],

    [null, 8, null, null, null, 4, 6, 1, null],
    [null, null, null, 6, null, 2, null, null, null],
    [null, 5, 4, 3, null, null, null, 2, null],

    [5, 9, null, null, null, null, null, 8, 4],
    [null, null, 7, 5, null, 3, null, 9, null],
    [4, null, null, null, null, 7, 3, 6, null],
];*/


$start = microtime(true);
$sudokuSolver = new \Arrow\Sudoku\SudokuSolver($sudoku);
$result = $sudokuSolver->solve();
$end = microtime(true);

\Arrow\Sudoku\Printer::show($result);
echo sprintf('Solved in: %s',$end-$start);

