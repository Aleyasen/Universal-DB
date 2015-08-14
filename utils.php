<?php

$dbs = array();
readDatasetFiles();

function c_log($msg) {
    error_log($msg, 3, "data\debug.log");
}

function readDatasetFiles() {
    Global $dbs;
    $dbs = array();
    $json = file_get_contents("dbs.json");
    $json_content = json_decode($json, true);
    $dbs_ids = $json_content["datasets"];
//    c_log("dbs.json content");
//    c_log(print_r($dbs_ids, true));
    $datasets_dir = "datasets/";
    $files = scandir($datasets_dir);
    foreach ($files as $f) {
//        c_log("files>>>> " . $f . "\n");
        if ($f == "." || $f == "..") {
            continue;
        }
        $json = file_get_contents($datasets_dir . $f);
        $dbs_element = json_decode($json, true);
//        c_log("id:::::::: " . $dbs_element["id"] . "\n");
//        c_log(print_r($dbs_ids, true));
        if (in_array($dbs_element["id"], $dbs_ids)) {
//            c_log(print_r($dbs_element, true));
//            c_log("true");
            $dbs[$dbs_element["id"]] = $dbs_element;
        } else {
            c_log("error. unavailable dataset " . $dbs_element["id"]);
        }
    }
}

?>