<?php

namespace Cleantalk\Variables;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    public function setUp() : void
    {
        $_REQUEST = array( 'variable' => 'value' );
    }

    public function testGet()
    {
        $var = Request::get( 'variable' );
        $this->assertEquals($var, $_REQUEST['variable']);
        $wrong_var = Request::get( 'wrong_variable' );
        $this->assertEmpty($wrong_var);
    }

    protected function tearDown() : void
    {
        unset( $_REQUEST['variable'] );
    }
}
