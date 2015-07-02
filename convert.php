<?php

$dir = 'data/uw-courses_small';
//$files = scandir($dir);

//Course
//main_courses_uw_target_new_schema_rwr.m
$files = array("course.txt", "inst.txt", "offer.txt", "subject.txt",
    "course_offer.txt", "course_subject.txt", "offer_inst.txt");

//main_courses_uw_src_new_schema_rwr.m
//$files = array("course.txt", "inst.txt", "offer.txt", "subject.txt",
//    "course_offer.txt", "offer_subject.txt", "offer_inst.txt");


// Moive
//new_main_q100_movie_imdb_to_niagra_2_source_wo_dirby_sr.m
//$files = array("movie.txt", "actor.txt", "character.txt", "director.txt",
//    "actor_character.txt", "movie_character.txt", "movie_actor.txt", "movie_director.txt");

//new_main_q100_movie_imdb_to_niagra_2_target_wo_dirby_sr.m
//$files = array("movie.txt", "actor.txt", "character.txt", "director.txt", "cast.txt",
//    "actor_cast.txt", "movie_cast.txt", "actor_character.txt", "character_cast.txt", "movie_director");



$json = generateJSONfromGraph($dir, $files);
echo "<PRE>";
echo $json;
echo "</PRE>";
saveJsonToFile($json, $dir . "/target.json");

//print_r($files);

function saveJsonToFile($json, $file) {
    file_put_contents($file, $json);
}

function generateJSONfromGraph($dir, $files) {
    $nodesArr = array();
    $edgesArr = array();
    $group = 1;

    for ($i = 0; $i < count($files); $i++) {
        if ($files[$i] == "." || $files[$i] == "..") {
            continue;
        }
        $dotIndex = strpos($files[$i], ".");
        if (strpos($files[$i], '_') !== false) {
            $underlineIndex = strpos($files[$i], "_");
            $type1 = substr($files[$i], 0, $underlineIndex);
            $type2 = substr($files[$i], $underlineIndex + 1, $dotIndex - $underlineIndex - 1);
            echo "Type1 = " . $type1 . "<br>";
            echo"Type2 = " . $type2 . "<br>";
            parse_edge_file($edgesArr, $dir . '/' . $files[$i], $type1, $type2);
        } else {
            $type = substr($files[$i], 0, $dotIndex);
            echo "Node Type = " . $type . "<br>";
            parse_node_file($nodesArr, $dir . '/' . $files[$i], $type, $group);
            $group++;
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
        echo "error. No global Id for " . $type . " and " . $id . "<br>";
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