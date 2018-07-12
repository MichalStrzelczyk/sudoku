<?php

declare(strict_types=1);

namespace Arrow\Sudoku;

class Buffer
{

    /**
     * @var SplFixedArray
     */
    protected $buffer;

    /**
     * Buffer constructor.
     */
    public function __construct(){
        $this->buffer = [0,0,0,0,0,0,0,0,0];
    }

    public function __clone(){
        $this->reset();
    }

    /**
     * @param array $numberLists
     *
     * @return Buffer
     */
    public static function createFromArray(array $numberLists){

        return (new Buffer())->useNumbers($numberLists);
    }

    /**
     * @return Buffer
     */
    public static function create(){

        return new Buffer();
    }

    /**
     * @return Buffer
     */
    public function reset(): void {
        $this->buffer[0] = 0;
        $this->buffer[1] = 0;
        $this->buffer[2] = 0;
        $this->buffer[3] = 0;
        $this->buffer[4] = 0;
        $this->buffer[5] = 0;
        $this->buffer[6] = 0;
        $this->buffer[7] = 0;
        $this->buffer[8] = 0;

        //return $this;
    }

    /**
     * @return Buffer
     */
    public function fill(): self {
        $this->buffer[0] = 1;
        $this->buffer[1] = 2;
        $this->buffer[2] = 3;
        $this->buffer[3] = 4;
        $this->buffer[4] = 5;
        $this->buffer[5] = 6;
        $this->buffer[6] = 7;
        $this->buffer[7] = 8;
        $this->buffer[8] = 9;

        return $this;
    }

    /**
     * @param int $number
     *
     * @return bool
     */
    public function isNumberUsed(int $number): bool {

        return $this->buffer[$number - 1] === $number;
    }

    /**
     * @param int $number
     *
     * @return bool
     */
    public function isNumberAvailable(int $number): bool {

        return !$this->isNumberUsed($number);
    }

    /**
     * @param int $number
     *
     * @return Buffer
     */
    public function useNumber(int $number): self {
        $this->buffer[$number -1] = (int) $number;

        return $this;
    }

    /**
     * @param int $number
     *
     * @return Buffer
     */
    public function resetNumber(int $number): self {
        $this->buffer[$number -1] = 0;

        return $this;
    }

    /**
     * @param array $numberList
     *
     * @return Buffer
     */
    public function useNumbers(array $numberList): self {
        foreach($numberList as $number){
            $this->useNumber($number);
        }

        return $this;
    }

    /**
     * @return SplFixedArray
     */
    public function getBuffer(): array {

        return $this->buffer;
    }

    /**
     * @return bool
     */
    public function isFull(): bool {

        return 45 === array_sum($this->buffer);
    }

    /**
     * @return array
     */
    public function getAvailableNumbers(): array {
        $result = [];
        for($i=0;$i<9;$i++){
            if($this->buffer[$i] === 0){
                $result[] = $i + 1;
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getUsedNumbers(): array {
        $result = [];
        for($i=0;$i<9;$i++){
            if($this->buffer[$i] !== 0){
                $result[] = $i + 1;
            }
        }

        return $result;
    }

    /**
     * @return int|null
     */
    public function getRandomAvailableNumber(): ?int {
        $result = $this->getAvailableNumbers();
        if(count($result) === 0){

            return null;
        }

        shuffle($result);

        return $result[0];
    }

    /**
     * @return int|null
     */
    public function getFirstAvailableNumber(): ?int {
        for($i=0;$i<9;$i++){
            if($this->buffer[$i] === 0){

                return $i + 1;
            }
        }

        return null;
    }

    /**
     * @return int
     */
    public function countPossibilities(): int {

        return count($this->getAvailableNumbers());
    }
}