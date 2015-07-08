

<?php

/**
 * @author Amirhossein Aleyasen <aleyase2@illinois.edu>
 */
$dir = 'data/uw-courses_small';
$node_files = array("course.txt", "inst.txt", "offer.txt", "subject.txt");
$edge_files = array("course_offer.txt", "course_subject.txt", "offer_inst.txt");


generateGraph($dir, $node_files, $edge_files);

function generateGraph($dir, $node_files, $edge_files) {
    $json = generateJSONfromGraph($dir, $node_files, $edge_files);
    //echo "<PRE>";
    //echo $json;
    //echo "</PRE>";
    $output_file = $dir . "/target3.json";
    saveJsonToFile($json, $output_file);
    echo "Done! Save json data on " . $output_file . "</br>";
}

function saveJsonToFile($json, $file) {
    file_put_contents($file, $json);
}

function generateJSONfromGraph($dir, $node_files, $edge_files) {
    $nodesArr = array();
    $edgesArr = array();
    $group = 1;

    for ($i = 0; $i < count($node_files); $i++) {
        if ($node_files[$i] == "." || $node_files[$i] == "..") {
            continue;
        }
        $dotIndex = strpos($node_files[$i], ".");
        $type = substr($node_files[$i], 0, $dotIndex);
        echo "Import node file " . $node_files[$i] . " , type = " . $type . "</br>";
        parse_node_file($nodesArr, $dir . '/' . $node_files[$i], $type, $group);
        $group++;
    }


    for ($i = 0; $i < count($edge_files); $i++) {
        if ($edge_files[$i] == "." || $edge_files[$i] == "..") {
            continue;
        }
        $dotIndex = strpos($edge_files[$i], ".");
        if (strpos($edge_files[$i], '_') !== false) {
            $underlineIndex = strpos($edge_files[$i], "_");
            $type1 = substr($edge_files[$i], 0, $underlineIndex);
            $type2 = substr($edge_files[$i], $underlineIndex + 1, $dotIndex - $underlineIndex - 1);
            echo "Import edge file " . $node_files[$i] . " , type1 = " . $type1 . " , type2 = " . $type2 . "</br>";
            parse_edge_file($edgesArr, $dir . '/' . $edge_files[$i], $type1, $type2);
        } else {
            echo "file format is not correct. filename = " . $edge_files[$i] . "</br>";
        }
    }
    $result = array();
    $result["nodes"] = $nodesArr;
    $result["links"] = $edgesArr;
    return json_encode($result, JSON_PRETTY_PRINT);
}

function generateUniqueString($type, $id) {
    return $type . "-" . $id;
}

$ids = array();

function addToGlobalIdArray($type, $id) {
    global $ids;
    static $maxId = 0;
    $uniqueStr = generateUniqueString($type, $id);
    if (!isset($ids[$uniqueStr])) {
        $ids[$uniqueStr] = $maxId;
        $maxId = $maxId + 1;
    }
    return $ids[$uniqueStr];
}

function getGlobalId($type, $id) {
    global $ids;
    $uniqueStr = generateUniqueString($type, $id);
    if (isset($ids[$uniqueStr])) {
        return $ids[$uniqueStr];
    } else {
        echo "error. No global Id for ( type = " . $type . " , id = " . $id . " ) <br>";
        return -1;
    }
}

function parse_edge_file(&$edgesArr, $file, $type1, $type2) {
    $content = file_get_contents($file);
    $lines = explode("\n", $content);
    for ($i = 1; $i < count($lines); $i++) { // skip first line (header)
//        echo $lines[$i] . "<br>";
        $splits = explode("\t", $lines[$i]);
        if (sizeof($splits) != 2) {
            continue;
        }
        $edge1 = $splits[0];
        $edge2 = $splits[1];
        $sourceId = getGlobalId($type1, $edge1);
        $targetId = getGlobalId($type2, $edge2);
        if ($sourceId == -1 || $targetId == -1) {
            continue;
        }
        $e = array();
        $e["source"] = $sourceId;
        $e["target"] = $targetId;
        $edgesArr[] = $e;
//        echo "e1:" . $edge1 . " e2:" . $edge2 . "<br>";
    }
}

function parse_node_file(&$nodeArr, $file, $type, $group) {
    $content = file_get_contents($file);
    $lines = explode("\n", $content);
    for ($i = 0; $i < count($lines); $i++) {
        $splits = explode("\t", $lines[$i]);
        if (sizeof($splits) != 2) {
            continue;
        }
//        echo $lines[$i]. "<br>";
        $id = $splits[0];
        $name = $splits[1];
        $gnId = addToGlobalIdArray($type, $id);
//        echo "ID: " . $gnId . "<br>";
//        $nodeArr[$gnId] = array();
        if ($gnId == -1) {
            continue;
        }
        $n = array();
        $n["index"] = $gnId;
        $n["dataId"] = generateUniqueString($type, $id);
        $n["name"] = $name;
        $n["type"] = $type;
        $n["group"] = $group;
        $nodeArr[] = $n;
    }
//    var_dump($nodeArr);
//    return $nodeArr;
}

function getGroup($type, $others) {
    
}
?>