$(function() {
    var dialog;

    dialog = $("#dialog_2").dialog({
        autoOpen: false,
        height: "550",
        width: "98%",
        modal: true
    });

//    $("#hi").on("click", function() {
//        console.log("open it!");
//        dialog.dialog("open");
//    });

});

//$(document).ready(function() {
var top_k = 10;
var rect_width = 120;
var rect_height = 30;
var truncate_limit = 18;

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

    generateList("#ranking1", src_file1, target_file1);
    generateList("#ranking2", src_file2, target_file2);
    generateList("#ranking3", src_file3, target_file3);
    generateList("#ranking4", src_file4, target_file4);
}

function generateList(selector, src_query_file, target_query_file) {
    //    d3.select(selector).remove();
    d3.select(selector).selectAll("*").remove();
    var svgContainer = d3.select(selector).append("svg")
            .attr("width", 280)
            .attr("height", top_k * 35);

    readFileWithoutHeader(src_query_file, function(src_list) {
        readFileWithoutHeader(target_query_file, function(target_list) {
//            console.log("src_list");
//            console.log(src_list);
//                console.log(target_list[1]);
//                console.log(src_list[5]);
//                console.log(svgContainer);
            createAll(svgContainer, top_k, src_list, target_list);
        });
    });
}




function createAll(svg, top_k, src_list, target_list) {
    var srcRects = createList(svg, 5, 10, top_k, src_list, src_list, target_list);
    var destRects = createList(svg, 155, 10, top_k, target_list, src_list, target_list);
    var connect_src = [];
    var connect_target = [];
    for (var i = 0; i < top_k; i++) {
        for (var j = 0; j < top_k; j++) {
            if (src_list[i] == target_list[j]) {
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
function createList(svg, x_init, y_init, count, labels, srcLabels, targetLabels) {
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
        var lb = labels[i];
        var original_text = labels[i];
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
                .attr("font-family", "sans-serif")
                .attr("font-size", "11px")
                .attr("fill", "black");
        text.on("click", function(d) {

            console.log("open it!!!");
            var lb_text = $(this).attr("title");
            var rank = jQuery.inArray(lb_text, labels);
            var pre_node_index = Math.max(rank - 1, 0);
            var post_node_index = rank + 1;
            var pre_node = labels[pre_node_index];
            var post_node = labels[post_node_index];
//            console.log(lb_text);
            $("#dialog_2").dialog("open");
            setTimeout(
                    function()
                    {
                        var q_text = $('.search-box').val();
                        generateModalContent(lb_text, q_text, srcLabels, targetLabels, pre_node, post_node);
                    }, 100);
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
        var split = allTextLines[i].split('\t');
        result[i] = split[0];
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
