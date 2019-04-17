<?php namespace App\Tests;

Use App\Tests\unit\RemoteConnect;

class UnitTest extends \Codeception\Test\Unit
{
    /**
     * @var \App\Tests\UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testOnePlusOneEqualsTwo()
    {
        // Arrange
                $num1 = 1;
                $num2 = 1;
                $expectedResult = 2;
        // Act
                $result = $num1 + $num2;
        // Assert
                $this->assertEquals($expectedResult, $result);
    }

    public function testConnectionIsValid()
    {
        // test to ensure that the object from an fsockopen is valid
        $connObj = new RemoteConnect();
        //Checking domain working
        $serverName = 'www.tudpolling.ie';
        $this->assertTrue($connObj->connectToServer($serverName) !== false);
    }
}