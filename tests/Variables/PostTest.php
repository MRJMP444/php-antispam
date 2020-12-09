<?php

namespace Cleantalk\Variables;

class PostTest extends \PHPUnit\Framework\TestCase
{
    public function setUp() : void
    {
        $_POST = array( 'variable' => 'value' );
    }

    public function testGet()
    {
        $var = Post::get( 'variable' );
        $this->assertEquals($var, $_POST['variable']);
        $wrong_var = Post::get( 'wrong_variable' );
        $this->assertEmpty($wrong_var);
    }

    protected function tearDown() : void
    {
        unset( $_POST['variable'] );
    }
}
