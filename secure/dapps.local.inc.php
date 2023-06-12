<?php
/**
 * Internal node file which executed dapp function on node host
 */


$dbFile = ROOT . "/labels.json";


/**
 *
 * Function retrieves label for address
 * Labels are stored in json file on the node root
 *
 * @param $address
 * @return void
 *
 */
function getAddressLabel($address) {
    // Read and decode content from json file
    global $dbFile;
    $content = @file_get_contents($dbFile);
    $labels = @json_decode($content, true);
    // Return API response with label for address
    api_echo($labels[$address]);
}

/**
 * Function saves label for address in JSON file
 *
 * @param $address
 * @param $label
 * @return void
 */
function saveAddressLabel($address, $label) {
    // Read and decode content from json file
    global $dbFile;
    $content = @file_get_contents($dbFile);
    $labels = @json_decode($content, true);
    // Assign label to address
    $labels[$address]=$label;
    // Encode and store map of labels back to json file
    @file_put_contents($dbFile, json_encode($labels));
    // Return API response with label for address
    api_echo($labels[$address]);
}

/**
 * Function deletes label for address from JSON file
 *
 * @param $address
 * @return void
 */
function removeAddressLabel($address) {
    // Read and decode content from json file
    global $dbFile;
    $content = @file_get_contents($dbFile);
    $labels = @json_decode($content, true);
    // delete label for address
    unset($labels[$address]);
    // Encode and store map of labels back to json file
    @file_put_contents($dbFile, json_encode($labels));
    // Return API response with label for address
    api_echo($labels[$address]);
}
