<?php

namespace Cleantalk;

/**
 * The Helper class moved to \Cleantalk\Common
 *
 * @deprecated
 */
class CleantalkHelper extends \Cleantalk\Common\Helper
{
    /**
     * @deprecated
     * Use \Cleantalk\Common\Helper::ip__get()
     */
    static public function ip_get( $ips_input = array('real', 'remote_addr', 'x_forwarded_for', 'x_real_ip', 'cloud_flare'), $v4_only = true )
	{
	    return parent::ip__get( $ips_input, $v4_only );
	}

    /**
     * @deprecated
     * Use \Cleantalk\Common\Helper::ip__mask_match()
     */
	static public function ip_mask_match( $ip, $cidr )
    {
        return parent::ip__mask_match( $ip, $cidr );
	}

    /**
     * @deprecated
     * Use \Cleantalk\Common\Helper::ip__validate()
     */
	static public function ip_validate( $ip )
	{
	    return parent::ip__validate( $ip );
	}

    /**
     * @deprecated
     * Use \Cleantalk\Common\Helper::http__request()
     */
	static public function http__request( $url, $data = array(), $presets = null, $opts = array() )
	{
	    return parent::http__request( $url, $data, $presets, $opts );
	}

    /**
     * @deprecated
     * Use \Cleantalk\Common\Helper::is_json()
     */
	static public function is_json( $string )
	{
		return parent::is_json( $string );
	}

    /**
     * @deprecated
     * Use \Cleantalk\Common\Helper::removeNonUTF8()
     */
	static public function removeNonUTF8FromArray( $data )
	{
	    return parent::removeNonUTF8( $data );
	}

    /**
     * @deprecated
     * Use \Cleantalk\Common\Helper::removeNonUTF8()
     */
	public static function removeNonUTF8FromString( $data )
	{
        return parent::removeNonUTF8( $data );
	}

    /**
     * @deprecated
     * Use \Cleantalk\Common\Helper::toUTF8()
     */
	public static function arrayToUTF8( $array, $data_codepage = null )
	{
	    return parent::toUTF8( $array, $data_codepage );
	}

    /**
     * @deprecated
     * Use \Cleantalk\Common\Helper::toUTF8()
     */
    public static function stringToUTF8( $str, $data_codepage = null )
	{
        return parent::toUTF8( $str, $data_codepage );
    }

    /**
     * @deprecated
     * Use \Cleantalk\Common\Helper::fromUTF8()
     */
    public static function stringFromUTF8 ($str, $data_codepage = null )
	{
        return parent::fromUTF8( $str, $data_codepage );
	}
}
