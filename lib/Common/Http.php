<?php


namespace Cleantalk\Common;


use Cleantalk\Exceptions\CleantalkHttpException;

class Http
{
    /**
     * Default timeout for HTTP requests
     */
    const SERVER_TIMEOUT = 5000;

    /**
     * Default user agent for HTTP requests
     */
    const AGENT = 'Cleantalk-Http/1.0';

    /**
     * @param $data
     * @return false|mixed|string
     */
    public static function prepareData( $data )
    {
        // Prepare json request for moderate servers
        if( $data instanceof Request ) {
            // Convert to array
            $data = (array) json_decode( json_encode( $data ), true );

            //Cleaning from 'null' values
            $tmp_data = array();
            foreach( $data as $key => $value ){
                if( $value !== null )
                    $tmp_data[$key] = $value;
            }
            $data = $tmp_data;
            unset( $key, $value, $tmp_data );

            // Convert to JSON
            return json_encode( $data );
        }
        // Prepare any other requests
        if( ! empty( $data ) ){
            // If $data scalar converting it to array
            $data = is_string( $data ) || is_int( $data ) ? array( $data => 1 ) : $data;
            // Make URL string
            $data_string = http_build_query( $data );
            return str_replace( "&amp;", "&", $data_string );
        }
    }

    /**
     * Try to do CURL request
     *
     * @param $url
     * @param array $data
     * @param null $presets
     * @param array $opts
     *
     * @return array
     */
    public static function doCurl( $url, $data = array(), $presets = null, $opts = array() )
    {
        $result = array();

        if( function_exists( 'curl_init' ) ){
            $ch = curl_init();
            // Merging OBLIGATORY options with GIVEN options
            $opts = Helper::array_merge__save_numeric_keys(
                array(
                    CURLOPT_URL => $url,
                    CURLOPT_POSTFIELDS => $data,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CONNECTTIMEOUT_MS => self::SERVER_TIMEOUT,
                    CURLOPT_TIMEOUT_MS => self::SERVER_TIMEOUT,
                    CURLOPT_FORBID_REUSE => true,
                    CURLOPT_USERAGENT => self::AGENT . '; ' . ( isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : 'UNKNOWN_HOST' ),
                    CURLOPT_POST => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_HTTPHEADER => array('Expect:'), // Fix for large data and old servers http://php.net/manual/ru/function.curl-setopt.php#82418
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_MAXREDIRS => 5,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0 // see http://stackoverflow.com/a/23322368
                ),
                $opts
            );

            // Use presets
            $presets = is_array($presets) ? $presets : explode(' ', $presets);
            foreach($presets as $preset){

                switch($preset){

                    // Do not follow redirects
                    case 'dont_follow_redirects':
                        $opts[CURLOPT_FOLLOWLOCATION] = false;
                        $opts[CURLOPT_MAXREDIRS] = 0;
                        break;

                    // Get headers only
                    case 'get_code':
                        $opts[CURLOPT_HEADER] = true;
                        $opts[CURLOPT_NOBODY] = true;
                        break;

                    // Make a request, don't wait for an answer
                    case 'async':
                        $opts[CURLOPT_CONNECTTIMEOUT_MS] = 1000;
                        $opts[CURLOPT_TIMEOUT_MS] = 1000;
                        break;

                    case 'get':
                        $opts[CURLOPT_URL] .= $data;
                        $opts[CURLOPT_CUSTOMREQUEST] = 'GET';
                        $opts[CURLOPT_POST] = false;
                        $opts[CURLOPT_POSTFIELDS] = null;
                        break;

                    case 'ssl':
                        $opts[CURLOPT_SSL_VERIFYPEER] = true;
                        $opts[CURLOPT_SSL_VERIFYHOST] = 2;
                        if(defined('CLEANTALK_CASERT_PATH') && CLEANTALK_CASERT_PATH)
                            $opts[CURLOPT_CAINFO] = CLEANTALK_CASERT_PATH;
                        break;

                    default:

                        break;
                }

            }
            unset($preset);

            curl_setopt_array($ch, $opts);
            $curl_result = curl_exec($ch);

            if( $curl_result ){

                $result['result'] = $curl_result;

                // RETURN if async request
                if( in_array( 'async', $presets ) )
                    return $result['result'];

                if( strpos( $curl_result, PHP_EOL ) !== false && ! in_array( 'dont_split_to_array', $presets ) )
                    $result['result'] = explode( PHP_EOL, $curl_result );

                // Get code crossPHP method
                if( in_array('get_code', $presets ) ){
                    $curl_info = curl_getinfo( $ch );
                    $result['result'] = $curl_info['http_code'];
                }
                curl_close($ch);

            } else {
                $result['error'] = curl_error( $ch );
            }

        } else {
            $result['error'] = 'CURL_NOT_INSTALLED';
        }

        return $result;

    }

    /**
     * Try to do file_get_contents request
     *
     * @param $url
     * @param array $data
     *
     * @return array
     */
    public static function doUrlFopen( $url, $data = array() )
    {
        $result = array();
        $allow_url_fopen = ini_get('allow_url_fopen');

        if ( function_exists('file_get_contents') && isset( $allow_url_fopen ) && $allow_url_fopen == '1' ) {
            $opts = array('http' =>
                array(
                    'method'  => 'POST',
                    'header'  => "Content-Type: text/html\r\n",
                    'content' => $data,
                    'timeout' => self::SERVER_TIMEOUT / 1000
                )
            );

            $context  = stream_context_create($opts);
            $result['result'] = @file_get_contents($url, false, $context);
        } else {
            $result['error'] = 'ALLOW_URL_FOPEN_DISABLED';
        }

        return $result;
    }
}