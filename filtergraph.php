<?php

require 'filtering.php';
require_once 'utils.php';

$schema = $_GET["schema"];
$dataset = $_GET["dataset"];

// make sure "/" is put in the end. i do not check for this char in this code
if (isset($_GET["compare"])) {
    $loc = $dbs[$dataset]["path"] . "/";
} else {
    $loc = $dbs[$dataset]["sample_path"] . "/";
}
$loc = substr($loc, 1);

// provide every information: all nodes, all edges here regardless of representation
//$v_files = array("a.txt", "b.txt", "x.txt", "y.txt", "c.txt");
//$e_files = array("a_b.txt", "a_c.txt", "x_a.txt", "x_b.txt", "y_a.txt", "y_c.txt");

$v_files = $dbs[$dataset]["v_files"];
$e_files = $dbs[$dataset]["e_files"];


// output: schema 1
//$v_output1 = array("a", "b", "c");
//$e_output1 = array("a_b", "a_c");

$v_output1 = $dbs[$dataset]["schema"]["source"]["v_files"];
$e_output1 = $dbs[$dataset]["schema"]["source"]["e_files"];


// output: schema 2
//$v_output2 = array("a", "b", "x", "y");
//$e_output2 = array("x_a", "x_b", "y_a", "y_c");

$v_output2 = $dbs[$dataset]["schema"]["target"]["v_files"];
$e_output2 = $dbs[$dataset]["schema"]["target"]["e_files"];


if (isset($_GET["nofilter"])) {
    if ($schema == "src") {

        $outfile_name1 = "schema-src.json";
//        $outfile_name1 = "schema-src.sample.json";
//        generateGraph($loc, $v_output1, $e_output1, $loc, $outfile_name1);
        echo $loc . $outfile_name1;
    } else if ($schema == "target") {

        $outfile_name2 = "schema-target.json";
//        $outfile_name2 = "schema-target.sample.json";
//        generateGraph($loc, $v_output2, $e_output2, $loc, $outfile_name2);
        echo $loc . $outfile_name2;
    }
} else {

// other parameters
    if (isset($_GET["seed"])) {
        $seed_node = $_GET["seed"];
    } else {
        $result_node = $_GET["result_node"];
        $seed_node = $_GET["query_node"];
    }
    $RADIUS = $_GET["radius"];
    $MAX_NODES = $_GET["max_nodes"];


// ------------------------------------------------------------------------------------

    $loc_out1 = $loc . "temp/";
    $loc_out2 = $loc . "temp/";
//    echo $loc_out1;
    if (!file_exists($loc_out1)) {
        mkdir($loc_out1);
//        deleteFilesInDir($loc_out1);
    }
//    if (!file_exists($loc_out2)) {
//        mkdir($loc_out2);
//    }
// read graph data
    $graphData = graphReaders($loc, $v_files, $e_files);

// locate query node from node type+value
    $v = idInFullGraph($seed_node, $graphData[2], $graphData[3]);

// bfs search
    $vs = bfsTraversal($v, $graphData[5], $RADIUS, $MAX_NODES);

// schema 1 filter
    if ($schema == "src") {
        $out_dir1 = printToFile($loc_out1, $v_output1, $e_output1, $vs, $graphData[4], $graphData[3], $graphData[1], $graphData[0], $graphData[5]);
        $outfile_name1 = "schema-src.json";
        generateGraph($out_dir1, $v_output1, $e_output1, $out_dir1, $outfile_name1);
        echo $out_dir1 . $outfile_name1;
    } else if ($schema == "target") {
// schema 2 filter
        $out_dir2 = printToFile($loc_out2, $v_output2, $e_output2, $vs, $graphData [4], $graphData[3], $graphData[1], $graphData[0], $graphData[5]);
        $outfile_name2 = "schema-target.json";
        generateGraph($out_dir2, $v_output2, $e_output2, $out_dir2, $outfile_name2);
        echo $out_dir2 . $outfile_name2;
    }
}
?>