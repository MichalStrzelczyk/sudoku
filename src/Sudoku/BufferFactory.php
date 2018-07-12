<?php

declare(strict_types=1);

namespace Arrow\Sudoku;

class BufferFactory
{
    public $buffer;

    /**
     * Buffer constructor.
     */
    public function __construct(){
        $this->buffer = new Buffer();
    }

    /**
     * @return Buffer
     */
    public function create(){

        return clone $this->buffer;
    }



}