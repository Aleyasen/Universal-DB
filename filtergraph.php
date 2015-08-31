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

$e_files_min = $dbs[$dataset]["e_files_min"];


// output: schema 1
//$v_output1 = array("a", "b", "c");
//$e_output1 = array("a_b", "a_c");

$v_output1 = $dbs[$dataset]["schema"]["source"]["v_files"];
$e_output1 = $dbs[$dataset]["schema"]["source"]["e_files"];

$expansion_factor = $dbs[$dataset]["expansion_factor"];

// output: schema 2
//$v_output2 = array("a", "b", "x", "y");
//$e_output2 = array("x_a", "x_b", "y_a", "y_c");

$v_output2 = $dbs[$dataset]["schema"]["target"]["v_files"];
$e_output2 = $dbs[$dataset]["schema"]["target"]["e_files"];


$entities = $dbs[$dataset]["entity_nodes"];

if (isset($_GET["nofilter"])) {
    if ($schema == "src") {

        $outfile_name1 = "schema-src.json";
//        $outfile_name1 = "schema-src.json";
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
    $graphData = graphReaders($loc, $v_files, $e_files, $entities, $e_files_min);

    if (isset($_GET["seed"])) {

        $seed_node = $_GET["seed"];
//        $seed_node = "Implementation of integrity constraints and views by query modification";
// locate query node from node type+value
        $v = idInFullGraph($seed_node, $graphData[2], $graphData[3]);
//        c_log(" >>>>>>>>>>>>>>>>>>>>> H-EG-MIN\n");
//        c_log(print_r($graphData[7], true));
//        c_log("DONE DONE H-EG-MIN");
// bfs search
        if (sizeof($e_files_min) == 0) {
            $vs = bfsTraversal($v, $graphData[5], $RADIUS, $MAX_NODES, $graphData[4]);
        } else {
            $vs = bfsTraversal($v, $graphData[7], $RADIUS, $MAX_NODES, $graphData[4]);
        }
    } else {

        $result_node = $_GET["result_node"];
        $query_node = $_GET["query_node"];


        //DEBUG PURPOSE:: uncomment these lines:
//        $result_node = "Captain America: The Winter Soldier";
//            $result_node = "Now You See Me"; //911
//        $result_node = "Mac Radner";
//            $result_node= "Dave Franco"; //339
//        $query_node = "Neighbors"; //922
//        $query_node = "Demetrice Nguyen";
//        $result_node = "Neighbors";
//        $result_node = "The Spy Next Door";
//        $query_node = "The Dilemma";
// locate query node from node type+value
        $v_result = idInFullGraph($result_node, $graphData[2], $graphData[3]);
        $v_query = idInFullGraph($query_node, $graphData[2], $graphData[3]);


//        echo "result node: " . $v_result . "<br>";
//
//        echo "query node: " . $v_query . "<br>";
//        echo "<br>now you see me<br>";
//        print_r($graphData[5][911]);
//        echo "<br>dave franco<br>";
//        print_r($graphData[5][339]);
//        echo "<br>neighbour<br>";
//        print_r($graphData[5][922]);
// bfs search
        $dis_result_query = findShortestPath($v_result, $v_query, $graphData[5]);
        $RADIUS_ = $dis_result_query + 4;
        $MAX_NODES_ = 100000;
        if (sizeof($e_files_min) == 0) {
            $vs_from_result_node = bfsTraversalWithDistance($v_result, $graphData[5], $RADIUS_, $MAX_NODES_);
            $vs_from_query_node = bfsTraversalWithDistance($v_query, $graphData[5], $RADIUS_, $MAX_NODES_);
        } else {
            $vs_from_result_node = bfsTraversalWithDistance($v_result, $graphData[7], $RADIUS_, $MAX_NODES_);
            $vs_from_query_node = bfsTraversalWithDistance($v_query, $graphData[7], $RADIUS_, $MAX_NODES_);
        }
//        echo "<br>nodes in result graph: <br>";
//        print_r($vs_from_result_node);
//        echo "<br>nodes in query graph: <br>";
//        print_r($vs_from_query_node);
//        echo "dis: " . $dis . "<br>";
//        echo "sizeof arr query: " . sizeof($vs_from_query_node) . "<br>";
//        echo "sizeof arr result: " . sizeof($vs_from_result_node) . "<br>";
        $vs_nodes_result_node = array();
        $vs_nodes_query_node = array();

        $dist_result_node = array();
        $dist_query_node = array();

        for ($i = 0; $i < sizeof($vs_from_result_node); $i++) {
            $dist_result_node[$vs_from_result_node[$i][0]] = $vs_from_result_node[$i][1];
        }

        for ($i = 0; $i < sizeof($vs_from_query_node); $i++) {
            $dist_query_node[$vs_from_query_node[$i][0]] = $vs_from_query_node[$i][1];
        }


        for ($i = 0; $i < sizeof($vs_from_result_node); $i++) {
            array_push($vs_nodes_result_node, $vs_from_result_node[$i][0]);
        }

        for ($i = 0; $i < sizeof($vs_from_query_node); $i++) {
            array_push($vs_nodes_query_node, $vs_from_query_node[$i][0]);
        }

        if (isset($_GET["other_nodes"])) {
            $other_nodes = json_decode($_GET["other_nodes"]);
            $vs_nodes_other_nodes_all = array();
            $dist_other_node = array();
            $dis_other_query = array();
            for ($i = 0; $i < sizeof($other_nodes); $i++) {
                $v_other = idInFullGraph($other_nodes[$i], $graphData[2], $graphData[3]);
                $vs_from_other_node = array();
                $dis_other_query[$i] = 1000;
                $dis_other_query[$i] = findShortestPath($v_other, $v_query, $graphData[5]);
                $vs_from_other_node = bfsTraversalWithDistance($v_other, $graphData[5], $RADIUS_, $MAX_NODES_);
                $vs_nodes_other_node = array();

                for ($j = 0; $j < sizeof($vs_from_other_node); $j++) {
                    $dist_other_node[$i][$vs_from_other_node[$j][0]] = $vs_from_other_node[$j][1];
                }
                for ($j = 0; $j < sizeof($vs_from_other_node); $j++) {
                    array_push($vs_nodes_other_node, $vs_from_other_node[$j][0]);
                    array_push($vs_nodes_other_nodes_all, $vs_from_other_node[$j][0]);
                }
            }
        }

//        example:
//        http://localhost:8080/Universal-DB/filtergraph.php?schema=target&dataset=citation&query_node=1&compare=1&radius=10&max_nodes=300000&result_node=1
//        $vs_nodes_result_node = array_values($vs_nodes_result_node);
//        $vs_nodes_result_node = array_unique($vs_nodes_result_node);
//        $vs_nodes_query_node = array_values($vs_nodes_query_node);
//        $vs_nodes_query_node = array_unique($vs_nodes_query_node);
//        $vs1 = array_intersect($vs_nodes_result_node, $vs_nodes_query_node);
//        echo "<br>nodes in result graph 2: <br>";
//        print_r($vs_nodes_result_node);
//        echo "<br>nodes in query graph 2: <br>";
//        print_r($vs_nodes_query_node);

        $vs1 = array_merge($vs_nodes_result_node, $vs_nodes_query_node);
        if (isset($vs_nodes_other_nodes_all)) {
            $vs1 = array_merge($vs1, $vs_nodes_other_nodes_all);
        }
//        echo "<br>nodes in vs1 <br>";
//        print_r($vs1);
        $vs1 = array_unique($vs1);
        $vs1 = array_values($vs1);
        $vs = array();
//        echo "size of vs_nodes_result_node = " . sizeof($vs_nodes_result_node) . "<br>";
//        echo "size of vs_nodes_query_node = " . sizeof($vs_nodes_query_node) . "<br>";
//        echo "size of vs1 = " . sizeof($vs1) . "<br>";
//        print_r($dist_query_node);
        for ($i = 0; $i < sizeof($vs1); $i++) {
            if (isset($dist_query_node[$vs1[$i]]) && isset($dist_result_node[$vs1[$i]])) {
                $d = $dist_query_node[$vs1[$i]] + $dist_result_node[$vs1[$i]];
                if ($d <= ($dis_result_query + $expansion_factor )) { //change the expansion factor here ( + 1 )
                    array_push($vs, $vs1[$i]);
                }
            }
        }

        if (isset($other_nodes)) {
            for ($i = 0; $i < sizeof($other_nodes); $i++) {
                for ($j = 0; $j < sizeof($vs1); $j++) {
                    if (isset($dist_query_node[$vs1[$j]]) && isset($dist_other_node[$i][$vs1[$j]])) {
                        $d = $dist_query_node[$vs1[$j]] + $dist_other_node[$i][$vs1[$j]];
                        if ($d <= ($dis_other_query[$i] + $expansion_factor )) { //change the expansion factor here ( + 1 )
                            array_push($vs, $vs1[$j]);
                        }
                    }
                }
            }
        }

        $vs = array_unique($vs);
        $vs = array_values($vs);
//        $vs = $vs1;
//        echo "<br>nodes in vs <br>";
//        print_r($vs);
//        echo "size of vs = " . sizeof($vs) . "<br>";
//        print_r($vs);
//        for ($i = 0; $i < sizeof($vs_from_query_node); $i++) {
//            for ($j = 0; $j < sizeof($vs_from_result_node); $j++) {
//                if ($vs_from_query_node[$i][0] == $vs_from_result_node[$j][0]) {
//                    if ($vs_from_query_node[$i][1] + $vs_from_result_node[$j][1] <= ($dis + 3)) {
////                    echo ($vs_from_query_node[$i][1] + $vs_from_result_node[$j][1]). "<br>";
//                        $vv = $vs_from_query_node[$i][0];
//                        if (!in_array($vv, $vs)) {
//                            array_push($vs, $vv);
//                        }
//                    }
//                }
//            }
//        }
    }
    $vs_ent = filterEntities($vs, $graphData[4], $graphData[6]);

    $vs = connectEntities($vs_ent, $graphData[5], $graphData[4], $graphData[6]);
// schema 1 filter
    if ($schema == "src") {
        $out_dir1 = printToFile($loc_out1, $v_output1, $e_output1, $vs, $graphData[4], $graphData[3], $graphData[1], $graphData[0], $graphData[5]);
        $outfile_name1 = "schema-src.json";
        generateGraph($out_dir1, $v_output1, $e_output1, $out_dir1, $outfile_name1);
        echo $out_dir1 . $outfile_name1;
    } else if ($schema == "target") {
// schema 2 filter
        $out_dir2 = printToFile($loc_out2, $v_output2, $e_output2, $vs, $graphData[4], $graphData[3], $graphData[1], $graphData[0], $graphData[5]);
        $outfile_name2 = "schema-target.json";
        generateGraph($out_dir2, $v_output2, $e_output2, $out_dir2, $outfile_name2);
        echo $out_dir2 . $outfile_name2;
    }
}
?>