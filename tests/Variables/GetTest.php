<?php

namespace Cleantalk\Variables;

class GetTest extends \PHPUnit\Framework\TestCase
{
    public function setUp() : void
    {
        $_GET = array( 'variable' => 'value' );
    }

    public function testGet()
    {
        $var = Get::get( 'variable' );
        $this->assertEquals($var, $_GET['variable']);
        $wrong_var = Get::get( 'wrong_variable' );
        $this->assertEmpty($wrong_var);
    }

    protected function tearDown() : void
    {
        unset( $_GET['variable'] );
    }
}
