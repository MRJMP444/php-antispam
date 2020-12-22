<?php

session_start();

require_once "../vendor/autoload.php"; //Composer

use Cleantalk\Cleantalk;
use Cleantalk\Common\Request;

// Take params from config
$config_url = 'http://moderate.cleantalk.org/api2.0/';
$auth_key = 'enter key'; // Set Cleantalk auth key

if (count($_POST)) {
    $sender_nickname = 'John Dow';
    if (isset($_POST['login']) && $_POST['login'] != '')
        $sender_nickname = $_POST['login'];

    $sender_email = 'stop_email@example.com';
    if (isset($_POST['email']) && $_POST['email'] != '')
        $sender_email = $_POST['email'];

    $sender_ip = null;
    if (isset($_SERVER['REMOTE_ADDR']))
        $sender_ip = $_SERVER['REMOTE_ADDR'];

    $js_on = 0;
    if (isset($_POST['js_on']) && $_POST['js_on'] == date("Y"))
        $js_on = 1;

    $message = null;
    if (isset($_POST['message']) && $_POST['message'] != '')
        $message = $_POST['message'];

    // The facility in which to store the query parameters
    $ct_request = new Request();

    $ct_request->auth_key = $auth_key;
    $ct_request->agent = 'php-api';
    $ct_request->sender_email = $sender_email;
    $ct_request->sender_ip = $sender_ip;
    $ct_request->sender_nickname = $sender_nickname;
    $ct_request->js_on = $js_on;
    $ct_request->message = $message;
    $ct_request->submit_time = time() - (int) $_SESSION['ct_submit_time'];

    $ct = new Cleantalk();
    $ct->server_url = $config_url;

    // Check
    $ct_result = $ct->isAllowMessage($ct_request);

    if ($ct_result->allow == 1) {
        echo 'Message allowed. Reason ' . $ct_result->comment;
    } else {
        echo 'Message forbidden. Reason ' . $ct_result->comment;
    }
    echo '<br /><br />';
}
else
{
    $_SESSION['ct_submit_time'] = time();
}
?>

<form method="post">
    <label for="login">Login:<label>
            <input type="text" name="login" id="login" />
            <br />
            <label for="email">Email:<label>
                    <input type="text" name="email" id="email" value="" />
                    <br />
                    <label for="message">Message:<label>
                            <textarea name="message" id="message"></textarea>
                            <br />
                            <input type="hidden" name="js_on" id="js_on" value="0" />
                            <input type="submit" />
</form>

<script type="text/javascript">
    var date = new Date();

    document.getElementById("js_on").value = date.getFullYear();
</script>