<?php
use PHPUnit\Framework\TestCase;

class BufferTest extends TestCase
{

    /**
     * Create Buffer
     *
     * @return \Arrow\Sudoku\Buffer
     */
    public function getNewEmptyBuffer(){

        return \Arrow\Sudoku\Buffer::create();
    }

    /**
     * Test buffer factory
     */
    public function testBufferFactory(){
        $buffer = $this->getNewEmptyBuffer();
        $this->assertInstanceOf(\Arrow\Sudoku\Buffer::class, $buffer);
        $this->assertFalse($buffer->isFull());
        $this->assertEquals(json_encode([0,0,0,0,0,0,0,0,0]),json_encode($buffer->getBuffer()->toArray()));
    }

    /**
     * Test buffer factory from array
     *
     * @param array $useNumbers
     * @param array $expected
     *
     * @dataProvider useNumberProviders
     */
    public function testBufferFactoryFromArray(array $useNumbers, array $expected){
        $this->assertEquals(
            json_encode($expected),
            json_encode(\Arrow\Sudoku\Buffer::createFromArray($useNumbers)->getBuffer()->toArray())
        );
    }

    /**
     * Test buffer fill
     */
    public function testFillBuffer(){
        $buffer = $this->getNewEmptyBuffer();
        $buffer->fill();
        $this->assertEquals(json_encode([1,2,3,4,5,6,7,8,9]),json_encode($buffer->getBuffer()->toArray()));
    }

    /**
     * Test buffer reset
     */
    public function testResetBuffer(){
        $buffer = $this->getNewEmptyBuffer();
        $buffer->reset();
        $this->assertEquals(json_encode([0,0,0,0,0,0,0,0,0]),json_encode($buffer->getBuffer()->toArray()));
    }

    /**
     * Test reset number
     */
    public function testResetNumber(){
        $buffer = $this->getNewEmptyBuffer();
        $buffer->fill();
        $buffer->resetNumber(1);
        $this->assertEquals(json_encode([0,2,3,4,5,6,7,8,9]),json_encode($buffer->getBuffer()->toArray()));
        $buffer->resetNumber(2);
        $this->assertEquals(json_encode([0,0,3,4,5,6,7,8,9]),json_encode($buffer->getBuffer()->toArray()));
        $buffer->resetNumber(3);
        $this->assertEquals(json_encode([0,0,0,4,5,6,7,8,9]),json_encode($buffer->getBuffer()->toArray()));
        $buffer->resetNumber(8);
        $this->assertEquals(json_encode([0,0,0,4,5,6,7,0,9]),json_encode($buffer->getBuffer()->toArray()));
        $buffer->resetNumber(8);
        $this->assertEquals(json_encode([0,0,0,4,5,6,7,0,9]),json_encode($buffer->getBuffer()->toArray()));
    }

    /**
     * Test buffer fill
     *
     * @dataProvider useNumberProvider
     */
    public function testUseNumber(int $useNumber, array $expected){
        $buffer = $this->getNewEmptyBuffer();
        $buffer->useNumber($useNumber);
        $this->assertEquals(json_encode($expected),json_encode($buffer->getBuffer()->toArray()));
    }

    /**
     * Test data for useNumber method
     *
     * @return array
     */
    public function useNumberProvider(){

        return [
            [1,[1,0,0,0,0,0,0,0,0]],
            [2,[0,2,0,0,0,0,0,0,0]],
            [3,[0,0,3,0,0,0,0,0,0]],
            [4,[0,0,0,4,0,0,0,0,0]],
            [5,[0,0,0,0,5,0,0,0,0]],
            [6,[0,0,0,0,0,6,0,0,0]],
            [7,[0,0,0,0,0,0,7,0,0]],
            [8,[0,0,0,0,0,0,0,8,0]],
            [9,[0,0,0,0,0,0,0,0,9]],
        ];
    }

    /**
     * Test buffer fill
     *
     * @param array $useNumbers
     * @param array $expected
     *
     * @dataProvider useNumberProviders
     */
    public function testUseNumbers(array $useNumbers, array $expected){
        $buffer = $this->getNewEmptyBuffer();
        $buffer->useNumbers($useNumbers);
        $this->assertEquals(json_encode($expected),json_encode($buffer->getBuffer()->toArray()));
    }

    /**
     * Test data for useNumbers method
     *
     * @return array
     */
    public function useNumberProviders(){

        return [
            [[1],[1,0,0,0,0,0,0,0,0]],
            [[1,2,3,4],[1,2,3,4,0,0,0,0,0]],
            [[2,8,3],[0,2,3,0,0,0,0,8,0]],
            [[9,9,9,4,4,4],[0,0,0,4,0,0,0,0,9]],
        ];
    }

    /**
     * Test buffer fill
     *
     * @dataProvider useNumberProviders
     */
    public function testIsNumberUsed(){
        $buffer = \Arrow\Sudoku\Buffer::createFromArray([1,5,9]);
        $this->assertTrue($buffer->isNumberUsed(1));
        $this->assertTrue($buffer->isNumberUsed(5));
        $this->assertTrue($buffer->isNumberUsed(9));

        $this->assertFalse($buffer->isNumberUsed(2));
        $this->assertFalse($buffer->isNumberUsed(3));
        $this->assertFalse($buffer->isNumberUsed(4));
    }

    /**
     * Test buffer fill
     *
     * @dataProvider useNumberProviders
     */
    public function testIsNumberAvaible(){
        $buffer = \Arrow\Sudoku\Buffer::createFromArray([1,5,8,9]);
        $this->assertTrue($buffer->isNumberAvailable(2));
        $this->assertTrue($buffer->isNumberAvailable(3));
        $this->assertTrue($buffer->isNumberAvailable(4));
        $this->assertTrue($buffer->isNumberAvailable(6));
        $this->assertTrue($buffer->isNumberAvailable(7));

        $this->assertFalse($buffer->isNumberAvailable(1));
        $this->assertFalse($buffer->isNumberAvailable(5));
        $this->assertFalse($buffer->isNumberAvailable(8));
        $this->assertFalse($buffer->isNumberAvailable(9));
    }

    /**
     * Test getAvailableNumbersProviders method
     *
     * @param array $useNumbers
     * @param array $expected
     *
     * @dataProvider getAvailableNumbersProviders
     */
    public function testGetAvailableNumbers(array $useNumbers, array $expected){
        $buffer = \Arrow\Sudoku\Buffer::createFromArray($useNumbers);
        $this->assertEquals(json_encode($expected),json_encode($buffer->getAvailableNumbers()));
    }

    /**
     * Test data for getAvailableNumbersProviders method
     *
     * @return array
     */
    public function getAvailableNumbersProviders(){

        return [
            [[1,2,3,4],[5,6,7,8,9]],
            [[1,1,2,2],[3,4,5,6,7,8,9]],
            [[1,2,3,4,5,6,7,8],[9]],
            [[1,2,3,4,5,6,7,8,9],[]],
            [[], [1,2,3,4,5,6,7,8,9]],
        ];
    }

    /**
     * Test getAvailableNumbersProviders method
     *
     * @param array $useNumbers
     * @param array $expected
     *
     * @dataProvider getUsedNumbersProviders
     */
    public function testGetUsedNumbersProviders(array $useNumbers, array $expected){
        $buffer = \Arrow\Sudoku\Buffer::createFromArray($useNumbers);
        $this->assertEquals(json_encode($expected),json_encode($buffer->getUsedNumbers()));
    }

    /**
     * Test data for getAvailableNumbersProviders method
     *
     * @return array
     */
    public function getUsedNumbersProviders(){

        return [
            [[1,2,3,4],[1,2,3,4],
            [[1,1,2,2],[1,1,2,2]],
            [[1,2,3,4,5,6,7,8],[1,2,3,4,5,6,7,8]],
            [[1,2,3,4,5,6,7,8,9],[1,2,3,4,5,6,7,8,9]],
            [[], []]],
        ];
    }

    /**
     * Test getRandomAvailableNumber method
     *
     * @param array $useNumbers
     * @param array $expected
     *
     * @dataProvider getRandomAvailableNumberProviders
     */
    public function testGetRandomAvailableNumber(array $useNumbers, array $expected){
        $buffer = \Arrow\Sudoku\Buffer::createFromArray($useNumbers);
        $this->assertTrue(in_array($buffer->getRandomAvailableNumber(), $expected));
        // When buffer is full.
        $buffer->fill();
        $this->assertNull($buffer->getRandomAvailableNumber());
    }

    /**
     * Test data for getAvailableNumbersProviders method
     *
     * @return array
     */
    public function getRandomAvailableNumberProviders(){

        return [
            [[1,2,3,4],[5,6,7,8,9]],
            [[1,1,2,2],[3,4,5,6,7,8,9]],
            [[1,2,3,4,5,6,7,8],[9]],
            [[], [1,2,3,4,5,6,7,8,9]],
        ];
    }

    /**
     * Test getRandomAvailableNumber method
     *
     * @param array $useNumbers
     * @param int $expected
     *
     * @dataProvider getFirstAvailableNumberProviders
     */
    public function testGetFirstAvailableNumber(array $useNumbers, int $expected){
        $buffer = \Arrow\Sudoku\Buffer::createFromArray($useNumbers);
        $this->assertEquals($expected, $buffer->getFirstAvailableNumber());

        // When buffer is full.
        $buffer->fill();
        $this->assertNull($buffer->getFirstAvailableNumber());

        // When buffer is empty.
        $buffer->reset();
        $this->assertEquals(1, $buffer->getFirstAvailableNumber());
    }

    /**
     * Test data for getAvailableNumbersProviders method
     *
     * @return array
     */
    public function getFirstAvailableNumberProviders(){

        return [
            [[1,2,3,4,5,6,7,8],9],
            [[1,2,3,4],5],
        ];
    }

    /**
     * @param array $useNumbers
     * @param int $expected
     *
     * @dataProvider countPossibilitiesProviders
     */
    public function testCountPossibilities(array $useNumbers, int $expected){
        $buffer = \Arrow\Sudoku\Buffer::createFromArray($useNumbers);
        $this->assertEquals($expected, $buffer->countPossibilities());
    }

    /**
     * Test data for getAvailableNumbersProviders method
     *
     * @return array
     */
    public function countPossibilitiesProviders(){

        return [
            [[],9],
            [[1],8],
            [[1,2],7],
            [[1,2,3],6],
            [[1,2,3,4],5],
            [[1,2,3,4,5],4],
            [[1,2,3,4,5,6],3],
            [[1,2,3,4,5,6,7],2],
            [[1,2,3,4,5,6,7,8],1],
            [[1,2,3,4,5,6,7,8,9],0],
        ];
    }
}