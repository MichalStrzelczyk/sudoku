<?php

declare(strict_types=1);

namespace Arrow\Sudoku;

class SudokuSolver
{
    /**
     * @var array
     */
    private $sudoku;

    /**
     * @var array
     */
    private $sudokuOriginal;

    /**
     * @var Buffer
     */
    private $verticalBuffer;

    /**
     * @var Buffer
     */
    private $horizontalBuffer;

    /**
     * @var Buffer
     */
    private $squareBuffer;

    /**
     * SudokuSolver constructor.
     *
     * @param array $sudoku
     */
    public function __construct(array $sudoku){
        $this->sudoku = $this->sudokuOriginal = $sudoku;
        $this->verticalBuffer = Buffer::create();
        $this->horizontalBuffer = Buffer::create();
        $this->squareBuffer = Buffer::create();
    }

    /**
     * @return array
     */
    public function solve(): array {
        for($y=0; $y<9; $y++){
            for($x=0; $x<9; $x++){
                if($this->sudoku[$y][$x] === null){

                    $this->sudoku[$y][$x] = $a = $this->fillPosition($y,$x);

                    \Arrow\Sudoku\Printer::show($this->sudoku);
                    if(!is_integer($a)){
                        var_dump('aaaaaaaa',$a);exit;
                    }
//
//                    \Arrow\Sudoku\Printer::show($this->sudoku);
//                        print_r($a);
//                        echo 'square';
//                        print_r($this->squareBuffer->getBuffer()->toArray());
//                        echo 'verical';
//                        print_r($this->verticalBuffer->getBuffer()->toArray());
//                        echo 'horizontal';
//                        print_r($this->horizontalBuffer->getBuffer()->toArray());

                    //sleep(1);
                }
            }
        }

        return $this->sudoku;
    }

    /**
     * @return array
     */
    private function reset(){
        $this->sudoku = $this->sudokuOriginal;

        return $this->solve();
    }

    private function resetRow($y){
        for($i=0;$i<9;$i++){
            if($this->sudoku[$y][$i] !== null){
                $this->verticalBuffer->useNumber($this->sudoku[$i][$x]);
            }
        }
    }

    /**
     * @param int $x
     * @param int $y
     *
     * @return array|int|null
     */
    private function fillPosition(int $y, int $x){

        // Horizont
        $horizonalPossibilities = $this->getHorizontalPossibilities($y);
        /*if($horizonalPossibilities->countPossibilities() === 1){
            return $horizonalPossibilities->getRandomAvailableNumber();
        }

        if($horizonalPossibilities->countPossibilities() === 0){
            return $this->reset();
        }*/

        // Vertical
        $verticalPossibilities = $this->getVerticalPossibilities($x);
        /*if($verticalPossibilities->countPossibilities() === 1){
            return $verticalPossibilities->getRandomAvailableNumber();
        }

        if($horizonalPossibilities->countPossibilities() === 0){
            return $this->reset();
        }*/

        // Horizontal + Vertical
        $result = BufferManager::sum($horizonalPossibilities, $verticalPossibilities);
        /*if($result->isFull()){
            return $this->reset();
        }
        if($result->countPossibilities() === 1){
            return $result->getRandomAvailableNumber();
        }*/

        // Square
        $squerePossibilities = $this->getSquerePossibilities($y,$x);
        /*if($squerePossibilities->countPossibilities() === 1){
            return $squerePossibilities->getRandomAvailableNumber();
        }*/

        // Horiszntal + Vertical + Square
        $result = BufferManager::sum($result, $squerePossibilities);
        if($result->isFull()){
            return $this->reset();
        }

        $r =  $result->getRandomAvailableNumber();

        if(!is_int($r)){
            var_dump($r,$result);
        }

        return $r;
    }

    /**
     * @param int $y
     *
     * @return Buffer
     */
    private function getHorizontalPossibilities(int $y): Buffer {
        $this->horizontalBuffer->reset();
        for($i=0;$i<9;$i++){
            if($this->sudoku[$y][$i] !== null){
                $this->horizontalBuffer->useNumber($this->sudoku[$y][$i]);
            }
        }

        return $this->horizontalBuffer;
    }

    /**
     * @param int $y
     *
     * @return Buffer
     */
    private function getVerticalPossibilities(int $x): Buffer {
        $this->verticalBuffer->reset();
        for($i=0;$i<9;$i++){
            if($this->sudoku[$i][$x] !== null){
                $this->verticalBuffer->useNumber($this->sudoku[$i][$x]);
            }
        }

        return $this->verticalBuffer;
    }

    /**
     * @param int $x
     * @param int $y
     *
     * @return Buffer
     */
    private function getSquerePossibilities(int $y, int $x): Buffer {
        $this->squareBuffer->reset();
        $horizontalArea = floor($x / 3);
        $verticalArea = floor($y / 3);

        for($i=0; $i<3;$i++) {
            for ($j = 0; $j < 3; $j++) {
                $realY = ($verticalArea * 3) + $i;
                $realX = ($horizontalArea * 3) + $j;

                if ($this->sudoku[$realY][$realX] !== null) {
                    $this->squareBuffer->useNumber($this->sudoku[$realY][$realX]);
                }
            }
        }

        return $this->squareBuffer;
    }


}