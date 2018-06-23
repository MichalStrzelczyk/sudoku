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
        for($i=0; $i<9;$i++){
            for($j=0; $j<9;$j++){
                if($this->sudoku[$i][$j] === null){
                    $this->sudoku[$i][$j] = $this->fillPosition($i,$j);
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

    /**
     * @param int $x
     * @param int $y
     *
     * @return array|int|null
     */
    private function fillPosition(int $x, int $y){

        // Horizont
        $horizonalPossibilities = $this->getHorizontalPossibilities($y);
        if($horizonalPossibilities->countPossibilities() === 1){
            return $horizonalPossibilities->getRandomAvailableNumber();
        }

        // Vertical
        $verticalPossibilities = $this->getVerticalPossibilities($x);
        if($verticalPossibilities->countPossibilities() === 1){
            return $verticalPossibilities->getRandomAvailableNumber();
        }

        // Horiszntal + Vertical
        $result = BufferManager::sum($horizonalPossibilities, $this->verticalBuffer);
        if($result->isFull()){
            return $this->reset();
        }
        if($result->countPossibilities() === 1){
            return $result->getRandomAvailableNumber();
        }

        // Square
        $squerePossibilities = $this->getSquerePossibilities($x,$y);
        if($squerePossibilities->countPossibilities() === 1){
            return $squerePossibilities->getRandomAvailableNumber();
        }

        // Horiszntal + Vertical + Square
        $result = BufferManager::sum($result, $squerePossibilities);
        if($result->isFull()){
            return $this->reset();
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
            if($this->sudoku[$i][$y] !== null){
                $this->horizontalBuffer->useNumber($this->sudoku[$i][$y]);
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
            if($this->sudoku[$x][$i] !== null){
                $this->verticalBuffer->useNumber($this->sudoku[$x][$i]);
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
    private function getSquerePossibilities(int $x, int $y): Buffer {
        $this->squareBuffer->reset();
        $horizontalArea = floor($y / 3);
        $verticalArea = floor($x / 3);

        for($i=0; $i<3;$i++) {
            for ($j = 0; $j < 3; $j++) {
                $realY = ($horizontalArea * 3) + $i;
                $realX = ($verticalArea * 3) + $j;

                if ($this->sudoku[$realX][$realY] !== null) {
                    $this->squareBuffer->useNumber($this->sudoku[$realX][$realY]);
                }
            }
        }

        return $this->squareBuffer;
    }


}