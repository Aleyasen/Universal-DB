$(function() {
    var dialog;
    var dHeight = $(window).height() * 0.9;
    dialog = $("#dialog_2").dialog({
        autoOpen: false,
        height: dHeight,
        width: "98%",
        modal: true
    });

//    $("#hi").on("click", function() {
//        console.log("open it!");
//        dialog.dialog("open");
//    });

    $("#increase-nodes-button").click(function() {
        others_count++;
        showModal(last_clicked_element);
    });

    $("#decrease-nodes-button").click(function() {
        others_count--;
        others_count = Math.max(0, others_count);
        showModal(last_clicked_element);
    });
});

//$(document).ready(function() {
var top_k = 10;
var rect_width = 120;
var rect_height = 30;
var truncate_limit = 18;
var others_count = 2;
var lists_arr = Array();
var last_clicked_element;

function generateAllLists(result_dir, query_index, tk, query_text) {
//    alert(query_index);
    top_k = tk;
//        var query = "5";
    var root_dir = result_dir;
//    var root_dir = "data\\ranking\\dblp_c50_top2000";
//    console.log("query_index" + query_index);
    var selectedDataset = $(".datasetpicker").select().val();
    if (selectedDataset == "citation") {
        extension = "";
    } else {
        extension = ".txt";
    }
    var src_file1 = root_dir + "\\source\\rwr\\answer.query" + (query_index + 1) + extension;
    var target_file1 = root_dir + "\\target\\rwr\\answer.query" + (query_index + 1) + extension;
    var src_file2 = root_dir + "\\source\\simrank\\answer.query" + (query_index + 1) + extension;
    var target_file2 = root_dir + "\\target\\simrank\\answer.query" + (query_index + 1) + extension;
    var src_file3 = root_dir + "\\source\\pathsim\\answer.query" + (query_index + 1) + extension;
    var target_file3 = root_dir + "\\target\\pathsim\\answer.query" + (query_index + 1) + extension;
    var src_file4 = root_dir + "\\source\\udb\\answer.query" + (query_index + 1) + extension;
    var target_file4 = root_dir + "\\target\\udb\\answer.query" + (query_index + 1) + extension;

    generateList("#ranking1", src_file1, target_file1, 0);
    generateList("#ranking2", src_file2, target_file2, 1);
    generateList("#ranking3", src_file3, target_file3, 2);
    generateList("#ranking4", src_file4, target_file4, 3);
}

function generateList(selector, src_query_file, target_query_file, listIndex) {
    //    d3.select(selector).remove();
    var list_size = top_k;

    d3.select(selector).selectAll("*").remove();
    var svgContainer = d3.select(selector).append("svg")
            .attr("width", 280)
            .attr("height", list_size * 35);

    readFileWithoutHeader(src_query_file, function(src_list) {
        readFileWithoutHeader(target_query_file, function(target_list) {
//            console.log(src_list);
//            console.log(src_list);
//                console.log(target_list[1]);
//                console.log(src_list[5]);
//                console.log(svgContainer);
            lists_arr[listIndex] = Array();
            lists_arr[listIndex]["src"] = src_list;
            lists_arr[listIndex]["target"] = target_list;
            console.log("initialized the list_arr");
            list_size = Math.min(list_size, src_list.length);
            createAll(svgContainer, list_size, src_list, target_list, listIndex);
        });
    });
}




function createAll(svg, top_k, src_list, target_list, listIndex) {
    var srcRects = createList(svg, 5, 4, top_k, src_list, src_list, target_list, listIndex, 1);
    var destRects = createList(svg, 155, 4, top_k, target_list, src_list, target_list, listIndex, 0);
    var connect_src = [];
    var connect_target = [];
    for (var i = 0; i < top_k; i++) {
        for (var j = 0; j < top_k; j++) {
            if (src_list[i][0] == target_list[j][0]) {
                createLine(svg, srcRects[i], destRects[j]);
                connect_src[i] = true;
                connect_target[j] = true;
            }
        }
    }

    for (var i = 0; i < top_k; i++) {
        if (!connect_src[i]) {
            createLineSrcToEnd(svg, srcRects[i], srcRects[top_k - 1]);
        }
    }

    for (var i = 0; i < top_k; i++) {
        if (!connect_target[i]) {
            createLineTargetToEnd(svg, destRects[i], srcRects[top_k - 1]);
        }
    }
}
function createList(svg, x_init, y_init, count, labels, srcLabels, targetLabels, listIndex, isSource) {
    var rects = [];
    for (var i = 0; i < count; i++) {
        var rectangle = svg.append("rect")
                .attr("x", x_init)
                .attr("y", y_init + (rect_height * (i)))
                .attr("width", rect_width)
                .attr("height", rect_height)
                .attr("fill", "white")
                .attr("stroke", "black")
                .attr("stroke-width", 1);

        rects[i] = rectangle;
//            console.log(labels[i]);
        var lb = labels[i][0];
        var original_text = labels[i][0];
        if (lb != null) {
            if (lb.length > truncate_limit) {
                lb = lb.substring(0, truncate_limit) + "...";
            }
        }
        var tooltip = rectangle.append("svg:title")
                .text(original_text);

        var text = svg.append("text")
                .attr("x", x_init + 5)
                .attr("y", y_init + (rect_height * (i)) + 20)
                .text(lb)
                .attr("svg:title", original_text)
                .attr("id", listIndex + "-" + isSource + "-" + i)
                .attr("data-isSource", isSource)
                .attr("font-family", "sans-serif")
                .attr("font-size", "11px")
                .attr("fill", "black");
        text.on("click", function(d) {
            last_clicked_element = $(this);
            var selectedDataset = $(".datasetpicker").select().val();
            others_count = dbs[selectedDataset]["other_nodes_count"];
            showModal($(this));

            d3.event.stopPropagation();
        });
        rectangle.on("mouseover", function() {
//            console.log("over");
//            console.log($(this).attr("x"));
            $(this).attr("fill", "#ADD8E6");
//            d3.event.stopPropagation();
        });

        rectangle.on("mouseout", function() {
//            console.log("over");
//            console.log($(this).attr("x"));
            $(this).attr("fill", "white");
//            d3.event.stopPropagation();
        });
    }
    return rects;
}

function createLine(svg, src, dest) {
    var line = svg.append("line")
            .attr("x1", (+src.attr("x")) + (+src.attr("width")))
            .attr("y1", (+src.attr("y")) + (+src.attr("height") / 2))
            .attr("x2", (+dest.attr("x")))
            .attr("y2", (+dest.attr("y")) + (+dest.attr("height") / 2))
            .attr("stroke-width", 2)
            .attr("stroke", "black");
//        console.log(line);
}

function createLineSrcToEnd(svg, src, lastsrc) {
    var r = Math.floor((Math.random() * 20) + 5);
    var line = svg.append("line")
            .attr("x1", (+src.attr("x")) + (+src.attr("width")))
            .attr("y1", (+src.attr("y")) + (+src.attr("height") / 2))
            .attr("x2", (+lastsrc.attr("x")) + (+lastsrc.attr("width") + r))
            .attr("y2", (+lastsrc.attr("y")) + (+lastsrc.attr("height")))
            .attr("stroke-width", 2)
            .attr("stroke", "gray");
//        console.log(line);
}

function createLineTargetToEnd(svg, src, lastsrc) {
    var r = Math.floor((Math.random() * 20) + 5);
    var line = svg.append("line")
            .attr("x1", (+src.attr("x")))
            .attr("y1", (+src.attr("y")) + (+src.attr("height") / 2))
            .attr("x2", (+lastsrc.attr("x")) + (+lastsrc.attr("width") + r))
            .attr("y2", (+lastsrc.attr("y")) + (+lastsrc.attr("height")))
            .attr("stroke-width", 2)
            .attr("stroke", "gray");
//        console.log(line);
}
//});



function readFileWithoutHeader(file, callback) {
    if (file.charAt(0) == "/") {
        file = file.substring(1, file.length);
        console.log("readFile, file name changed: " + file);
    } else {
        console.log("readFile, name is fine: " + file);
    }
    $.get(file, function(data) {
        var list = parseFileWithoutHeader(data);
//            console.log(list);
        callback(list);
    });
}


function readFile(file, callback) {
    if (file.charAt(0) == "/") {
        file = file.substring(1, file.length);
        console.log("readFile, file name changed: " + file);
    } else {
        console.log("readFile, name is fine: " + file);
    }
    $.get(file, function(data) {
        var list = parseFile(data);
//            console.log(list);
        callback(list);
    });
}


function parseFileWithoutHeader(allText) {
    var allTextLines = allText.split(/\r\n|\n/);
    var result = [];
//    console.log("lines");
//    console.log(allTextLines);
    for (var i = 0; i < allTextLines.length; i++) {
        if (allTextLines[i].length == 0) {
            break;
        }
        var split = allTextLines[i].split('\t');
//        console.log(split.length);
        if (split.length > 1) {
            result[i] = [split[0], split[2]];
        } else {
            result[i] = split[0];
        }
    }
    //alert(lines.length);
    return result;
}


function parseFile(allText) {
    var allTextLines = allText.split(/\r\n|\n/);
    var result = [];
    for (var i = 1; i < allTextLines.length; i++) {
        var split = allTextLines[i].split('\t');
        result[i] = split[0];
    }
    //alert(lines.length);
    return result;
}

function generateOtherNodes(labels, rank, count) {
    var other_nodes_list = Array();
    var pre_count = Math.ceil(count / 2);
    var post_count = count - pre_count;
    var pre_rank = Math.max(0, rank - pre_count);
    var post_rank = Math.min(labels.length - 1, rank + post_count);
    for (var i = pre_rank; i <= post_rank; i++) {
        if (i != rank) {
            other_nodes_list.push(labels[i][0])
        }
    }
    return other_nodes_list;
}

algorithmList = ["RWR", "SimRank", "PathSim", "Universal-DB"];
function showModal($element) {
    console.log("open it!!!");
    var lb_text = $element.attr("title");
    var id = $element.attr("id");
    var split = id.split("-");
    var listIndex = split[0];
    var isSource = split[1];
    console.log($element);
    console.log(lists_arr);
    console.log("listIndex: " + listIndex);
    console.log("isSource: " + isSource);
    var labels = lists_arr[listIndex]["target"];
    if (isSource == 1) {
        labels = lists_arr[listIndex]["src"];
    }
    var rank = findInArray(labels, lb_text);
    var other_nodes = generateOtherNodes(labels, rank, others_count);

//            console.log(lb_text);
    $("#dialog_2").dialog("open");
    setTimeout(
            function()
            {
                var q_text = $('.search-box').val();
                generateModalContent(lb_text, q_text, lists_arr[listIndex]["src"], lists_arr[listIndex]["target"], other_nodes, algorithmList[listIndex]);
            }, 50);
}
