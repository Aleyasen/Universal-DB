<?php

require 'filtering.php';


// other parameters
$seed_node = $_GET["seed"];
$RADIUS = $_GET["radius"];
$MAX_NODES = $_GET["max_nodes"];
$schema = $_GET["schema"];

// ------------------------------------------------------------------------------------
// make sure "/" is put in the end. i do not check for this char in this code
$loc = "data/yods/";
$loc_out1 = "data/yods/output/";
$loc_out2 = "data/yods/output/";
if (!file_exists($loc_out1))
    mkdir($loc_out1);
if (!file_exists($loc_out2))
    mkdir($loc_out2);

// provide every information: all nodes, all edges here regardless of representation
$v_files = array("a.txt", "b.txt", "x.txt", "y.txt", "c.txt");
$e_files = array("a_b.txt", "a_c.txt", "x_a.txt", "x_b.txt", "y_a.txt", "y_c.txt");

// output: schema 1
$v_output1 = array("a", "b", "c");
$e_output1 = array("a_b", "a_c");

// output: schema 2
$v_output2 = array("a", "b", "x", "y");
$e_output2 = array("x_a", "x_b", "y_a", "y_c");


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
?>