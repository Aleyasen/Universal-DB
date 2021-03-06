<?php

require 'generatejson.php';
require_once 'utils.php';

// ------------------------------------------------------------------------------------
// only course dataset need to set e_files_min
function graphReaders($loc, $v_files, $e_files, $entities, $e_files_min) {

    $h_VVG = array();  // node_id	=> graph_id
    $h_VGV = array();  // graph_id	=> node_id
    $h_VA = array();  // node_id 	=> node_val
    $h_AV = array(); // node_val => node_id
    $h_L = array();  // label 	=> label_id 	and		label_id	=> label
    $h_EG = array(); // graph_id => graph_id list
    $l_ENT = array(); // list of entity_id
    //extra info
    $h_EG_min = null; // empty iff e_files_min is empty
    // read node files
    $t_id = 0;
    $vg_id = 0;
    foreach ($v_files as $x => $x_val) {
        $x_val = $x_val . ".txt";
        c_log("key=" . $x . ", val=" . $loc . $x_val . "\n");
        $t_id = $t_id + 1;
        $file_arr = explode('.', $x_val);
        $h_L[$file_arr[0]] = $t_id;
        $h_L[$t_id] = $file_arr[0];
        $file = fopen($loc . $x_val, "r") or die("unable to open " . $loc . $x_val . " !");
        while (($line = fgets($file)) !== false) {
//            echo $line."\n";
            $line_arr = preg_split("/\t/", $line);
            if (count($line_arr) < 2) {
                continue;
            }
            $vg_id = $vg_id + 1;
            $n_id = intval(rtrim($line_arr[0])) * 100 + $t_id;
            $n_val = rtrim($line_arr[1]);
            $h_VA[$n_id] = $n_val;
            $h_AV[$n_val] = $n_id;
            $h_VVG[$n_id] = $vg_id;
            $h_VGV[$vg_id] = $n_id;
        }

        fclose($file);
    }

    // read edge files
    foreach ($e_files as $x => $x_val) {
        $x_val = $x_val . ".txt";
        c_log("key=" . $x . ", val=" . $loc . $x_val . "\n");
//        echo "key=" . $x . ", val=" . $loc . $x_val . "<br>";

        $file_arr = preg_split("/[._]/", $x_val);
        $t1 = $h_L[$file_arr[0]];
        $t2 = $h_L[$file_arr[1]];
        $file = fopen($loc . $x_val, "r") or die("unable to open " . $loc . $x_val . " !");
        $line = fgets($file); // read headline
        while (($line = fgets($file)) !== false) {
//            echo $line . "\n";
            $line_arr = preg_split("/\t/", $line);
            $v1 = rtrim($line_arr[0]);
            $v2 = rtrim($line_arr[1]);
            $n1_id = $v1 * 100 + $t1;
            $n2_id = $v2 * 100 + $t2;
//            $vg1 = -1;
//            if (!isset($h_VVG[$n1_id])) {
//                echo "file: ". $loc . $x_val . "<br>";
//                echo $line. "<br>";
//                echo "v1: ". $v1 . " v2: ". $v2. "<br>";
//                echo "n1_id: ". $n1_id . "<br>";
//            }
            // added by amir
            if ((!isset($h_VVG[$n1_id])) || (!isset($h_VVG[$n2_id]))) {
                continue;
            }
            $vg1 = $h_VVG[$n1_id];
            $vg2 = $h_VVG[$n2_id];

            //echo $vg1.":".$n1_id."\t".$vg2.":".$n2_id."\n";
            if (array_key_exists($vg1, $h_EG))
                $eg1 = $h_EG[$vg1];
            else
                $eg1 = array();
            if (!in_array($vg2, $eg1))
                array_push($eg1, $vg2);
            $h_EG[$vg1] = $eg1;
            if (array_key_exists($vg2, $h_EG))
                $eg2 = $h_EG[$vg2];
            else
                $eg2 = array();
            if (!in_array($vg1, $eg2))
                array_push($eg2, $vg1);
            $h_EG[$vg2] = $eg2;
        }
        fclose($file);
    }

    // test print
//    c_log(print_r($h_VA, true));
//    c_log(print_r($h_L, true));
//    c_log(print_r($h_AV, true));
//    c_log(print_r($h_VVG, true));
//    c_log(print_r($h_VGV, true));
//    c_log(print_r($h_EG, true));
    // check comment for each var
    // read entity types
    foreach ($entities as $ent) {
        array_push($l_ENT, $h_L[$ent]);
    }

    if (isset($e_files_min)) {
        $h_EG_min = array();
        // read min set of edge file
        foreach ($e_files_min as $x => $x_val) {
            $x_val = $x_val . ".txt";
            c_log("? key=" . $x . ", val=" . $loc . $x_val . "\n");
            //        echo "key=" . $x . ", val=" . $loc . $x_val . "<br>";

            $file_arr = preg_split("/[._]/", $x_val);
            $t1 = $h_L[$file_arr[0]];
            $t2 = $h_L[$file_arr[1]];
            $file = fopen($loc . $x_val, "r") or die("unable to open " . $loc . $x_val . " !");
            $line = fgets($file); // read headline
            while (($line = fgets($file)) !== false) {
                //            echo $line . "\n";
                $line_arr = preg_split("/\t/", $line);
                $v1 = rtrim($line_arr[0]);
                $v2 = rtrim($line_arr[1]);
                $n1_id = $v1 * 100 + $t1;
                $n2_id = $v2 * 100 + $t2;
                //            $vg1 = -1;
                //            if (!isset($h_VVG[$n1_id])) {
                //                echo "file: ". $loc . $x_val . "<br>";
                //                echo $line. "<br>";
                //                echo "v1: ". $v1 . " v2: ". $v2. "<br>";
                //                echo "n1_id: ". $n1_id . "<br>";
                //            }
                // added by amir
                if ((!isset($h_VVG[$n1_id])) || (!isset($h_VVG[$n2_id]))) {
                    continue;
                }
                $vg1 = $h_VVG[$n1_id];
                $vg2 = $h_VVG[$n2_id];

                //echo $vg1.":".$n1_id."\t".$vg2.":".$n2_id."\n";
                if (array_key_exists($vg1, $h_EG_min))
                    $eg1 = $h_EG_min[$vg1];
                else
                    $eg1 = array();
                if (!in_array($vg2, $eg1))
                    array_push($eg1, $vg2);
                $h_EG_min[$vg1] = $eg1;
                if (array_key_exists($vg2, $h_EG_min))
                    $eg2 = $h_EG_min[$vg2];
                else
                    $eg2 = array();
                if (!in_array($vg1, $eg2))
                    array_push($eg2, $vg1);
                $h_EG_min[$vg2] = $eg2;
            }
            fclose($file);
        }
    }

    return array($h_L, $h_VA, $h_AV, $h_VVG, $h_VGV, $h_EG, $l_ENT, $h_EG_min);
}

function idInFullGraph($n_val, $h_AV, $h_VVG) {
    $n_id = $h_AV[$n_val];
    $vg = $h_VVG[$n_id];

    return $vg;
}

function filterEntities($vs, $h_VGV, $l_ENT) {
    $vg_ent = array();
    foreach ($vs as $vg) {
        $v = $h_VGV[$vg];
        $vt = $v % 100;
        if (in_array($vt, $l_ENT)) {
            array_push($vg_ent, $vg);
        }
    }
    return $vg_ent;
}

function connectEntities($vg_ent, $h_EG, $h_VGV, $l_ENT) {
    $vs = array();
    foreach ($vg_ent as $vg1) {
        foreach ($vg_ent as $vg2) {
            if ($vg1 == $vg2) {
                continue;
            }
            $eg1 = $h_EG[$vg1];
            $eg2 = $h_EG[$vg2];
            $common_neighbor = array();
            foreach ($eg1 as $n1) {
                if (in_array($n1, $eg2)) {
                    array_push($common_neighbor, $n1);
                }
            }
            foreach ($eg2 as $n2) {
                if (in_array($n2, $eg1) && !in_array($n2, $common_neighbor)) {
                    array_push($common_neighbor, $n2);
                }
            }
            foreach ($common_neighbor as $n) {
                $v = $h_VGV[$n];
                $vt = $v % 100;
                if (!in_array($vt, $l_ENT)) {
                    if (!in_array($n, $vs)) {
                        array_push($vs, $n);
                    }
                }
            }
        }
        if (!in_array($vg1, $vs)) {
            array_push($vs, $vg1);
        }
    }
    return $vs;
}

function findShortestPath($vfrom, $vto, $h_EG) {
//    print_r($h_EG);

    $mark = array();
    $queue = new SplQueue();
    $queue_rad = new SplQueue();
    $parent = array();
    $queue->enqueue($vfrom);
    $queue_rad->enqueue(0);
    $parent[$vfrom] = -1;
    $mark[$vfrom] = 1;

    while (!$queue->isEmpty()) {
        $v = $queue->dequeue();
        $n = $queue_rad->dequeue();
//        echo "dequeue: " . $v . " d: " . $n . "<br>";
        if ($v == $vto) {
//            $it = $v;
//            echo "<br>";
//            while (true) {
//                echo $it . " -> ";
//                $it = $parent[$it];
//                if ($it == -1) {
//                    break;
//                }
//            }
//            echo "<br>";
            return $n;
        }
        $eg = $h_EG[$v];
        foreach ($eg as $v2) {
            if (!isset($mark[$v2])) {
                $mark[$v2] = 1;
                $queue->enqueue($v2);
                $queue_rad->enqueue($n + 1);
                $parent[$v2] = $v;
//                echo "enqueue: " . $v2 . " d: " . $n . "parent: " . $v . "<br>";
            }
        }
    }

    // unreachable node
    return 9999;
}

function bfsTraversalWithDistance($vg, $h_EG, $RAD, $MAX_NODES) {
    $vs = array();
    $mark = array();
    $queue = new SplQueue();
    $queue_rad = new SplQueue();
    $queue->enqueue($vg);
    $queue_rad->enqueue(0);
    $mark[$vg] = 1;


    while (!$queue->isEmpty() && count($vs) < $MAX_NODES) {
        $v = $queue->dequeue();
        $n = $queue_rad->dequeue();
        array_push($vs, array($v, $n));
        if ($n >= $RAD) {
            continue;
        }
//        if ($n > ($RAD + 2)) {
//            break;
//        }
        $eg = $h_EG[$v];
        foreach ($eg as $v2) {
            if (!isset($mark[$v2])) {
                $mark[$v2] = 1;
                $queue->enqueue($v2);
                $queue_rad->enqueue($n + 1);
//                echo "enqueue: " . $v . " d: " . $n . "<br>";
            }
        }
    }

    // test print
//    c_log(print_r($vs, true));

    return $vs;
}

function bfsTraversal($vg, $h_EG, $RAD, $MAX_NODES, $h_VGV) {
//    c_log("hello bfs from " . $h_VGV[$vg] . " Rad: " . $RAD . " MaxNodes: " . $MAX_NODES . "\n");
//    c_log(print_r($h_EG[$vg], true));
    $vs = array();
    $mark = array();
    $queue = new SplQueue();
    $queue_rad = new SplQueue();
    $queue->enqueue($vg);
    $queue_rad->enqueue(0);
    $mark[$vg] = 1;

    while (!$queue->isEmpty() && count($vs) < $MAX_NODES) {

        $v = $queue->dequeue();
        $n = $queue_rad->dequeue();
//        c_log("dequeue: " . $h_VGV[$v] . " d: " . $n . "\n");
        array_push($vs, $v);
        if ($n >= $RAD) {
//            c_log("n: " . $n . " RAD:" . $RAD);
//            c_log("bfsTraversal - exceed max radius >>>>>>>>>>>>>>>>>>>>>>>");
//            c_log(print_r($vs, true));
            continue;
        }
//        if ($n > ($RAD + 2)) {
//            break;
//        }
        $eg = $h_EG[$v];
//        foreach ($eg as $vv) {
//            c_log("\t\t" . $h_VGV[$v] . " >>>>>>>t> " . $h_VGV[$vv] . "\n");
//        }
        foreach ($eg as $v2) {
            if (!isset($mark[$v2])) {
                $mark[$v2] = 1;
                $queue->enqueue($v2);
                $queue_rad->enqueue($n + 1);
//                c_log("enqueue: " . $h_VGV[$v2] . " d: " . $n . "parent: " . $h_VGV[$v] . "\n");
//                echo "enqueue: " . $v . " d: " . $n . "<br>";
            }
        }
    }

    // test print
    c_log("bfsTraversal done >>>>>>>>>>>>>>>>>>>>>>>");
//    c_log(print_r($vs, true));

    return $vs;
}

function printToFile($loc, $v_out, $e_out, $vs, $h_VGV, $h_VVG, $h_VA, $h_L, $h_EG) {
//    print_r("v<br>");
//    print_r($v_out);
//    print_r("e<br>");
//    print_r($e_out);
    $r_number = rand(10000, 99999);
    $loc = $loc . "out-" . $r_number . "/";
    mkdir($loc, 0775);
    $v_l = array();
    foreach ($v_out as $v) {
        $l = $h_L[$v];
        array_push($v_l, $l);
    }

    $e_l = array();
    foreach ($e_out as $e) {
        $e_arr = preg_split("/[_]/", $e);
//        echo "edges<br>";
//        print_r($e_arr);
        $l1 = $e_arr[0];
        $l2 = $e_arr[1];
        $l = array($l1, $l2);
        array_push($e_l, $l);
    }

    foreach ($v_l as $l) {
        $file = fopen($loc . $h_L[$l] . ".txt", "w") or die("unable to create " . $loc . $h_L[$l] . ".txt" . " !");
        foreach ($vs as $vg) {
            if (!isset($h_VGV[$vg])) {
                continue;
            }
            $v_id = $h_VGV[$vg];
            $l_id = $v_id % 100;
            if ($l_id == $l) {
                c_log($l . " " . $v_id . "\n");
                $txt = intval($v_id / 100) . "\t" . $h_VA[$v_id] . "\n";
                fwrite($file, $txt);
            }
        }
        fclose($file);
    }

    foreach ($e_l as $l) {
        $l1 = $h_L[$l[0]];
        $l2 = $h_L[$l[1]];
        $file = fopen($loc . $h_L[$l1] . "_" . $h_L[$l2] . ".txt", "w") or die("unable to create " . $loc . $h_L[$l1] . "_" . $h_L[$l2] . ".txt" . " !");
        fwrite($file, $h_L[$l1] . "\t" . $h_L[$l2] . "\n");
        c_log($h_L[$l1] . " " . $h_L[$l2] . "\n");
        foreach ($vs as $vg) {
            $eg = $h_EG[$vg];
            if (!isset($h_VGV[$vg])) {
                continue;
            }
            $v_id = $h_VGV[$vg];
            $l_id = $v_id % 100;
            if ($l_id == $l1) {
                $v1 = intval($v_id) / 100;
                foreach ($eg as $vg2) {
                    if (!isset($h_VGV[$vg2])) {
                        continue;
                    }
                    $v2_id = $h_VGV[$vg2];
                    $l2_id = $v2_id % 100;
                    if ($l2_id == $l2 && in_array($h_VVG[$v2_id], $vs)) {
                        $txt = intval($v_id / 100) . "\t" . intval($v2_id / 100) . "\n";
                        fwrite($file, $txt);
                    }
                }
            }
        }
        fclose($file);
    }
    return $loc;
}

function printInfo() {
    
}

// ------------------------------------------------------------------------------------
// make sure "/" is put in the end. i do not check for this char in this code
//$loc = "data/yods/";
//$loc_out1 = "data/yods/output/";
//$loc_out2 = "data/yods/output/";
//if (!file_exists($loc_out1))
//    mkdir($loc_out1);
//if (!file_exists($loc_out2))
//    mkdir($loc_out2);
// provide every information: all nodes, all edges here regardless of representation
//$v_files = array("a.txt", "b.txt", "x.txt", "y.txt", "c.txt");
//$e_files = array("a_b.txt", "a_c.txt", "x_a.txt", "x_b.txt", "y_a.txt", "y_c.txt");
//
//// output: schema 1
//$v_output1 = array("a", "b", "c");
//$e_output1 = array("a_b", "a_c");
//
//// output: schema 2
//$v_output2 = array("a", "b", "x", "y");
//$e_output2 = array("x_a", "x_b", "y_a", "y_c");
//
//// other parameters
//$seed_node = "a1";
//$RADIUS = 3;
//$MAX_NODES = 100;
//
//// read graph data
//$graphData = graphReaders($loc, $v_files, $e_files);
//
//// locate query node from node type+value
//$v = idInFullGraph($seed_node, $graphData[2], $graphData[3]);
//
//// bfs search
//$vs = bfsTraversal($v, $graphData[5], $RADIUS, $MAX_NODES);
//
//// schema 1 filter
//$out_dir1 = printToFile($loc_out1, $v_output1, $e_output1, $vs, $graphData[4], $graphData[3], $graphData[1], $graphData[0], $graphData[5]);
//generateGraph($loc, $v_output1, $e_output1, $out_dir1, "schema-src.json");
//
//// schema 2 filter
//$out_dir2 = printToFile($loc_out2, $v_output2, $e_output2, $vs, $graphData[4], $graphData[3], $graphData[1], $graphData[0], $graphData[5]);
//generateGraph($loc, $v_output2, $e_output2, $out_dir2, "schema-target.json");
?>