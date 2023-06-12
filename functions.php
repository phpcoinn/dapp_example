<?php
/**
 * This is helper file with functions that can be called from other pages
 */

/**
 * Function calls API to retrieve label for address
 * @param $address
 * @return mixed
 */
function getAddressLabel($address) {
    return labelerApi("getAddressLabel&address=".$address);
}

/**
 * Function calls API to save label for address
 * @param $address
 * @return mixed
 */
function saveAddressLabel($address, $label) {
    return labelerApi("saveAddressLabel&address=".$address."&label=$label");
}

/**
 * Function calls API to delete label for address
 * @param $address
 * @return mixed
 */
function removeAddressLabel($address) {
    return labelerApi("removeAddressLabel&address=".$address);
}

/**
 * Internal function that performs API call
 *
 * @param $q name of API function
 * @return mixed
 */
function labelerApi($q) {
    $url = dapps_get_url("/labeler/api.php?q=$q", true);
    $res = file_get_contents($url);
    $res = json_decode($res, true);
    return $res['data'];
}
