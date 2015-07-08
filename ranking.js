
//$(document).ready(function() {
var top_k = 10;
var rect_width = 120;
var rect_height = 30;
var truncate_limit = 20;

function generateAllLists(query, tk) {
    top_k = tk;
//        var query = "5";
    var root_dir = "data\\ranking\\top2000_q100_movie_imdb_to_freebase_results";
//    var root_dir = "data\\ranking\\dblp_c50_top2000";

    var src_file1 = root_dir + "\\source\\rwr\\answer.query" + query + ".txt";
    var target_file1 = root_dir + "\\target\\rwr\\answer.query" + query + ".txt";
    var src_file2 = root_dir + "\\source\\simrank\\answer.query" + query + ".txt";
    var target_file2 = root_dir + "\\target\\simrank\\answer.query" + query + ".txt";
    var src_file3 = root_dir + "\\source\\pathsim\\answer.query" + query + ".txt";
    var target_file3 = root_dir + "\\target\\pathsim\\answer.query" + query + ".txt";

    generateList("#ranking1", src_file1, target_file1);
    generateList("#ranking2", src_file2, target_file2);
    generateList("#ranking3", src_file2, target_file2);
}

function generateList(selector, src_query_file, target_query_file) {
    //    d3.select(selector).remove();
    d3.select(selector).selectAll("*").remove();
    var svgContainer = d3.select(selector).append("svg")
            .attr("width", 300)
            .attr("height", top_k * 35);

    readFile(src_query_file, function(src_list) {
        readFile(target_query_file, function(target_list) {
//            console.log(src_list);
//                console.log(target_list[1]);
//                console.log(src_list[5]);
//                console.log(svgContainer);
            createAll(svgContainer, top_k, src_list, target_list);
        });
    });
}




function createAll(svg, top_k, src_list, target_list) {
    var srcRects = createList(svg, 5, 10, top_k, src_list);
    var destRects = createList(svg, 170, 10, top_k, target_list);
    var connect_src = [];
    var connect_target = [];
    for (var i = 1; i <= top_k; i++) {
        for (var j = 1; j <= top_k; j++) {
            if (src_list[i] == target_list[j]) {
                createLine(svg, srcRects[i], destRects[j]);
                connect_src[i] = true;
                connect_target[j] = true;
            }
        }
    }

    for (var i = 1; i <= top_k; i++) {
        if (!connect_src[i]) {
            createLineSrcToEnd(svg, srcRects[i], srcRects[top_k]);
        }
    }

    for (var i = 1; i <= top_k; i++) {
        if (!connect_target[i]) {
            createLineTargetToEnd(svg, destRects[i], srcRects[top_k]);
        }
    }
}
function createList(svg, x_init, y_init, count, labels) {
    var rects = [];
    for (var i = 1; i <= count; i++) {
        var rectangle = svg.append("rect")
                .attr("x", x_init)
                .attr("y", y_init + (rect_height * (i - 1)))
                .attr("width", rect_width)
                .attr("height", rect_height)
                .attr("fill", "white")
                .attr("stroke", "black")
                .attr("stroke-width", 1);
        rects[i] = rectangle;
//            console.log(labels[i]);
        var lb = labels[i];
        if (lb.length > truncate_limit) {
            lb = lb.substring(0, truncate_limit) + "...";
        }
        var text = svg.append("text")
                .attr("x", x_init + 5)
                .attr("y", y_init + (rect_height * (i - 1)) + 20)
                .text(lb)
                .attr("font-family", "sans-serif")
                .attr("font-size", "11px")
                .attr("fill", "black");
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
    var r = Math.floor((Math.random() * 30) + 10);
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
    var r = Math.floor((Math.random() * 30) + 10);
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


function readFile(file, callback) {
    $.get(file, function(data) {
        var list = parseFile(data);
//            console.log(list);
        callback(list);
    });
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
