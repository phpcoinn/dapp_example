<?php
/**
 * This is an example of API file for Dapp.
 * API file is mostly used for communication between frontend and backend or from other nodes
 */

// CAll common function for work with dapp
dapps_init();

// This code prevents that only node owner executes functions on server, because this API contains private functions
if(!dapps_is_local()) {
    // So if request is not from local node it will be redirected to node owner
    dapps_request(dapps_get_id(), $_SERVER['DAPPS_URL'], true);
}

// Similar to PHPCoin API structure in "q" query parameter we will hold name of API function
// If not is set API function name access is aborted with error status
if(!isset($_GET['q'])) {
    // Dapp sends response with error information
    dapps_json_response(["status"=>"error", "data"=>"Invalid request"]);
}

// Extract API function name from request
$q = $_GET['q'];

// *** Only for private API
// Here we read protected (owner only) configuration
// Config file is stored on node in file: config/dapps.config.inc.php
$dapps_config = dapps_config();

// We check for correct API call
// Here based on API call we will call specific private function on node
// Functions must be defined in file: /include/dapps.local.inc.php
if($q == "getAddressLabel") {
    // This function get label for specified address
    dapps_exec_fn("getAddressLabel", $_GET['address']);
} else if ($q == "saveAddressLabel") {
    // This function saves label for specified address
    dapps_exec_fn("saveAddressLabel", $_GET['address'], $_GET['label']);
} else if ($q == "removeAddressLabel") {
    // This function removes label for specified address
    dapps_exec_fn("removeAddressLabel", $_GET['address']);
} else {
    // In case of other request we will send error response
    dapps_json_response(["status"=>"error", "data"=>"Invalid request $q"]);
}
