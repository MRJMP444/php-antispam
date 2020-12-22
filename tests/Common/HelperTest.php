<?php

use Cleantalk\Common\Helper;

require_once "../vendor/autoload.php"; //Composer

class HelperTest extends \PHPUnit\Framework\TestCase
{
    protected $api_key;

    public function setUp() : void
    {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $this->api_key = getenv( 'CLEANTALK_TEST_API_KEY');
    }

    public function testIp__get() {
        $ips = Helper::ip__get();
        $this->assertEquals( '127.0.0.1', $ips );
    }

    public function testIp__is_private_network() {
        $this->assertTrue(
            Helper::ip__is_private_network( $_SERVER['REMOTE_ADDR'] )
        );
    }

    public function testIp__mask_match() {
        $this->assertTrue(
            Helper::ip__mask_match( $_SERVER['REMOTE_ADDR'], array(
                '127.0.0.1/32',
                '127.0.0.0/24'
            ) )
        );
    }

    public function testIp__mask__long_to_number() {
        $mask = 4294967295;
        $this->assertEquals(
            32,
            Helper::ip__mask__long_to_number( $mask )
        );
        $mask = 4294966272;
        $this->assertEquals(
            22,
            Helper::ip__mask__long_to_number( $mask )
        );
    }

    public function testIp__validate() {
        $ipv4 = '168.14.15.12';
        $ipv6 = '2002:0:0:0:0:0:a80e:f0c';
        $this->assertEquals(
            'v4',
            Helper::ip__validate( $ipv4 )
        );
        $this->assertEquals(
            'v6',
            Helper::ip__validate( $ipv6 )
        );
        $this->assertFalse( Helper::ip__validate( 'not_ip' ) );
    }

    public function testIp__is_cleantalks() {
        $this->assertTrue( Helper::ip__is_cleantalks( '18.206.49.217' ) );
        $this->assertFalse( Helper::ip__is_cleantalks( '10.10.10.10' ) );
    }

    public function testHttp__request() {

    }
}
