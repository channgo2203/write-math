<?php

require_once('preprocessing.php');

function get_point_count($data) {
    // TODO: Probably data should get scaled before
    $sum = 0;
    if (!is_array($data)) {
        # TODO: This should not happen
        error_log($data);
        return -1;
    }
    foreach ($data as $line) {
        $sum += count($line);
    }
    return $sum;
}

?>