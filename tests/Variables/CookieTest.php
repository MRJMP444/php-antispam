<?php

namespace Cleantalk\Variables;

class CookieTest extends \PHPUnit\Framework\TestCase
{
    public function setUp() : void
    {
        $_COOKIE = array( 'variable' => 'value' );
    }

    public function testGet()
    {
        $var = Cookie::get( 'variable' );
        $this->assertEquals($var, $_COOKIE['variable']);
        $wrong_var = Cookie::get( 'wrong_variable' );
        $this->assertEmpty($wrong_var);
    }

    protected function tearDown() : void
    {
        unset( $_COOKIE['variable'] );
    }
}
