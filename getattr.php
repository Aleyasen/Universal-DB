<?php

require_once 'utils.php';
if (isset($_GET["dataset"])) {
    $dataset = $_GET["dataset"];
}
if (isset($_GET["attr"])) {
    $attr = $_GET["attr"];
}

if ($attr == "query_file") {
    echo $dbs[$dataset]["path"] . "/_query.txt";
} else if ($attr == "result_dir") {
    echo $dbs[$dataset]["result"];
} else if ($attr == "entity_nodes") {
    echo json_encode($dbs[$dataset]["entity_nodes"]);
} else if ($attr == "all") {
    echo json_encode($dbs);
}
?>