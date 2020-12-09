<?php

namespace Cleantalk\Variables;

class ServerTest extends \PHPUnit\Framework\TestCase
{
    private $original_values = array();

    public function setUp() : void
    {
        $this->original_values['REQUEST_METHOD']  = $_SERVER['REQUEST_METHOD'];
        $this->original_values['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
        $this->original_values['HTTP_REFERER']    = $_SERVER['HTTP_REFERER'];
        $this->original_values['SERVER_NAME']     = $_SERVER['SERVER_NAME'];
        $this->original_values['REQUEST_URI']     = $_SERVER['REQUEST_URI'];

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['HTTP_USER_AGENT'] = 'user_agent';
        $_SERVER['HTTP_REFERER'] = 'referer';
        $_SERVER['SERVER_NAME'] = 'server_name';
        $_SERVER['REQUEST_URI'] = 'request_uri';
    }

    public function testGet()
    {
        $var = Server::get( 'REQUEST_METHOD' );
        $this->assertEquals($var, $_SERVER['REQUEST_METHOD']);

        $var = Server::get( 'HTTP_USER_AGENT' );
        $this->assertEquals($var, $_SERVER['HTTP_USER_AGENT']);

        $var = Server::get( 'HTTP_REFERER' );
        $this->assertEquals($var, $_SERVER['HTTP_REFERER']);

        $var = Server::get( 'SERVER_NAME' );
        $this->assertEquals($var, $_SERVER['SERVER_NAME']);

        $var = Server::get( 'REQUEST_URI' );
        $this->assertEquals($var, $_SERVER['REQUEST_URI']);

        $wrong_var = Server::get( 'wrong_variable' );
        $this->assertEmpty($wrong_var);
    }

    public function testIn_uri()
    {
        $this->assertTrue( Server::in_uri( 'request_uri' ) );
        $this->assertFalse( Server::in_uri( 'wrong_request_uri' ) );
    }

    public function testIn_referer()
    {
        $this->assertTrue( Server::in_referer( 'referer' ) );
        $this->assertFalse( Server::in_referer( 'wrong_referer' ) );
    }

    protected function tearDown() : void
    {
        $_SERVER['REQUEST_METHOD'] = $this->original_values['REQUEST_METHOD'];
        $_SERVER['HTTP_USER_AGENT'] = $this->original_values['HTTP_USER_AGENT'];
        $_SERVER['HTTP_REFERER'] = $this->original_values['HTTP_REFERER'];
        $_SERVER['SERVER_NAME'] = $this->original_values['SERVER_NAME'];
        $_SERVER['REQUEST_URI'] = $this->original_values['REQUEST_URI'];
    }
}
