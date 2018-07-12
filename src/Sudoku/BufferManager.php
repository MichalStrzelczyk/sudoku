<?php

declare(strict_types=1);

namespace Arrow\Sudoku;

class BufferManager
{

    private $buffer;

    public function __construct()
    {
        $this->buffer = Buffer::create();
    }

    /**
     * @param Buffer $bufferA
     * @param Buffer $bufferB
     *
     * @return Buffer
     */
    public static function product(Buffer $bufferA, Buffer $bufferB): Buffer {
        var_dump(123);exit;
        $result = Buffer::create();
        $resultA = $bufferA->getUsedNumbers();
        $resultB = $bufferB->getUsedNumbers();

        foreach($resultA as $numberA){
            if(in_array($numberA, $resultB)){
                $result->useNumber($numberA);
            }
        }

        return $result;
    }

    /**
     * @param Buffer $bufferA
     * @param Buffer $bufferB
     *
     * @return Buffer
     */
    public function sum(Buffer $bufferA, Buffer $bufferB): Buffer {
        //$result = clone $this->buffer;
        $result = Buffer::create();
        $resultA = $bufferA->getUsedNumbers();
        foreach($resultA as $numberA){
            $result->useNumber($numberA);
        }

        $resultB = $bufferB->getUsedNumbers();
        foreach($resultB as $numberB){
            $result->useNumber($numberB);
        }

        return $result;
    }

}