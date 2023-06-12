<?php
/**
 * Frontend file that is executed in browser
 */

// First we include common functions
require_once __DIR__ . "/functions.php";

// Next we initialize dapp
dapps_init();

// Set some common constants
const PATH = "/labeler";
const APP_NAME = "Labeler";
const GATEWAY = "PeC85pqFgRxmevonG6diUwT4AfF7YUPSm3";

/**
 * Handling response from gateway app after login
 */
if(isset($_GET['auth_data'])) {
    // auth data received from gateway
    $auth_data = json_decode(base64_decode($_GET['auth_data']), true);

    // if request is valid we will store account data in session
    if($auth_data['request_code']==$_SESSION['request_code']) {
        $_SESSION['account']=$auth_data['account'];
    }
    // redirect to main page
    dapps_redirect($auth_data['redirect']);
}

/**
 * Handling response from gateway app after signing data
 */
if(isset($_GET['signature_data'])) {
    // siganture data received from gateway
    $signature_data = json_decode(base64_decode($_GET['signature_data']), true);
    // we extract signature data
    $address = $signature_data['address'];
    $public_key = $signature_data['public_key'];
    $signature = $signature_data['signature'];
    $message = $signature_data['message'];
    $message = urlencode($message);
    // Check if data is same as logged in account
    if($address == $_SESSION['account']['address']) {
        // We call standard API to check signature
        $res = dapps_api("checkSignature&public_key=$public_key&signature=$signature&data=$message");
        if($res) {
            // If signature is verified then based on action we call functions
            $action = $_GET['action'];
            if($action == "exec_create_label") {
                saveAddressLabel($address, $message);
            }
            if($action == "exec_remove_label") {
                removeAddressLabel($address);
            }
        }
    }
    // redirect to main page
    dapps_redirect(dapps_get_url(PATH));
}

/**
 * This section handles frontend actions
 */
$action = null;
if(isset($_GET['action'])) {
    $action = $_GET['action'];
    if($action == "logout") {
        // For logout we destroy session and redirect to main page
        dapps_session_destroy();
        dapps_redirect(dapps_get_url(PATH));
    }
    if($action == "create_label") {
        // For creating label we extract data and build url to gateway signing interface
        $message=trim($_POST['label']);
        $address = $_SESSION['account']['address'];
        $redirectUrl=dapps_get_url(PATH . "/?action=exec_create_label");
        $message = urlencode($message);
        $url = "/dapps.php?url=".GATEWAY."/gateway/sign.php?app=".APP_NAME."&message=$message&address=$address&redirect=$redirectUrl";
        dapps_redirect($url);
    }
    if($action == "remove_label") {
        // For creating label we extract data and build url to gateway signing interface
        $message="REMOVE";
        $address = $_SESSION['account']['address'];
        $redirectUrl=dapps_get_url(PATH . "/?action=exec_remove_label");
        $message = urlencode($message);
        $url = "/dapps.php?url=".GATEWAY."/gateway/sign.php?app=".APP_NAME."&message=$message&address=$address&redirect=$redirectUrl";
        dapps_redirect($url);
    }
}

// generate and store unique id for request
$requestCode = uniqid();
$_SESSION['request_code']=$requestCode;

// Build login link to gateway app
$redirectUrl=dapps_get_url(PATH);
$loginLink="/dapps.php?url=".GATEWAY."/gateway/auth.php?app=".APP_NAME."&request_code=$requestCode&redirect=$redirectUrl";

// User is logged in if account is in session
$loggedIn = isset($_SESSION['account']);

if($loggedIn) {
    // If user is logged in then we use dapp api to retrieve label
    $addressLabel = getAddressLabel($_SESSION['account']['address']);
    // And also use standard API to retrieve address balance
    $balance = dapps_api("getBalance&address=".$_SESSION['account']['address']);
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PHPCoin Dapp Tutorial</title>
    <link href="/apps/common/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="/apps/common/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
</head>
<body>
    <div class="container">
        <h1>Dapps Tutorial - Labeler</h1>
        <hr/>
<!--        If user is logged in we will display address with balance-->
        <?php if ($loggedIn) { ?>
            <div class="row">
                <div class="col-2 h4">Address:</div>
                <div class="col-6 h4"><?php echo $_SESSION['account']['address'] ?></div>
                <div class="col-2 h4"><?php echo $balance ?></div>
                <div class="col-2 text-end"><a href="<?php echo dapps_get_url(PATH . "/?action=logout") ?>" class="btn btn-primary">Logout</a></div>
            </div>
<!--            Otherwise we will display login link to gateway app -->
        <?php } else { ?>
            <a href="<?php echo $loginLink ?>" class="btn btn-primary">Login</a>
        <?php } ?>
<!--If user is logged in then check and display label -->
        <?php if ($loggedIn) { ?>
            <hr/>
            <div class="row">
                <div class="col-2 h4">Label:</div>
                <div class="col-5 h4"><?php echo $addressLabel ?></div>
                <div class="col-5 text-end">
<!--                    If there is no label add button to create one -->
                    <?php if (empty($addressLabel)) { ?>
                        <?php if ($action == "open_create_label") { ?>
<!--                                Form for entering label -->
                            <form method="post" class="d-flex" action="<?php echo dapps_get_url(PATH . "/?action=create_label") ?>">
                                <input type="text" class="form-control me-2" required name="label" placeholder="Enter label"/>
                                <button type="submit" class="btn btn-primary me-2">Create</button>
                                <a href="<?php echo dapps_get_url(PATH) ?>" class="btn btn-outline-primary">Cancel</a>
                            </form>
                        <?php } else { ?>
                            <a class="btn btn-primary" href="<?php echo dapps_get_url(PATH . "/?action=open_create_label") ?>">Create label</a>
                        <?php } ?>
                    <?php } else { ?>
                        <a href="<?php echo dapps_get_url(PATH . "/?action=remove_label") ?>" class="btn btn-danger">Remove label</a>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</body>
</html>

