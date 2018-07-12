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
     * @var int
     */
    private $rowIterator = 0;

    private $solve = 0;
    private $bufferManager;

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
        //$this->horizontalBuffer = clone $this->verticalBuffer;
        //$this->squareBuffer = clone $this->verticalBuffer;
        $this->bufferManager = new BufferManager();
    }

    public function getSudoku(){
        return $this->sudoku;
    }


    /**
     * @return array
     */
    public function solve() {

        try{
            $this->solve++;
            for($y=0; $y<9; $y++){
                for($x=0; $x<9; $x++){
                    if($this->sudoku[$y][$x] === null){
                        $this->sudoku[$y][$x] = $this->fillPosition($y,$x);
                    }
                }
            }

        }catch(\Exception $e){
            echo PHP_EOL;
            echo 'Solve '. $this->solve . ' ';
            echo PHP_EOL;
            echo (memory_get_usage(true) / 1024) . 'kb';
            echo PHP_EOL;
            echo (memory_get_usage(true) / 1024 / 1024) . 'MB';
            echo PHP_EOL;

            if($y>0 && $this->rowIterator === $y){
                $this->sudoku[$y-1] = $this->sudokuOriginal[$y-1];
                $this->rowIterator--;
            }else{
                $this->rowIterator = $y;
            }

            $this->sudoku[$y] = $this->sudokuOriginal[$y];

            unset($y, $x);
            $this->solve();
        }
    }


    private function resetRow($y){

            $this->sudoku[$y] = $this->sudokuOriginal[$y];

    }

    /**
     * @param int $y
     * @param int $x
     *
     * @return array|int|null
     */
    private function fillPosition(int $y, int $x){

        // Horizont
        $horizonalPossibilities = $this->getHorizontalPossibilities($y);
        $possibilitiesNumber = $horizonalPossibilities->countPossibilities();
        //if($possibilitiesNumber === 1){
            //return $horizonalPossibilities->getRandomAvailableNumber();
        //}

        if($possibilitiesNumber === 0){
                throw new \Exception('Result is not possible to found');
        }

        // Vertical
        $verticalPossibilities = $this->getVerticalPossibilities($x);
        //if($verticalPossibilities->countPossibilities() === 1){
            //return $verticalPossibilities->getRandomAvailableNumber();
        //}

        if($verticalPossibilities->countPossibilities() === 0){
            throw new \Exception('Result is not possible to found');
        }

        // Horizontal + Vertical
        $result1 = $this->bufferManager->sum($horizonalPossibilities, $verticalPossibilities);
        unset($horizonalPossibilities,$verticalPossibilities);
        if($result1->isFull()){
            throw new \Exception('Result is not possible to found');
        }
        //if($result->countPossibilities() === 1){
           // return $result->getRandomAvailableNumber();
        //}

        // Square
        $squerePossibilities = $this->getSquerePossibilities($y,$x);
        //if($squerePossibilities->countPossibilities() === 1){
        //    return $squerePossibilities->getRandomAvailableNumber();
        //}

        // Horiszntal + Vertical + Square
        $result = $this->bufferManager->sum($result1, $squerePossibilities);
        unset($result1,$squerePossibilities);
        if($result->isFull()){
            throw new \Exception('Result is not possible to found');
        }

        return $result->getRandomAvailableNumber();
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

        unset($horizontalArea, $verticalArea, $i, $j, $realX, $realY);


        return $this->squareBuffer;
    }


}