
var default_radius = 1;
var default_max_nodes = 100000;

var radius_fr_filter = 3;
var max_nodes_fr_filter = 200;

var default_radius_fr = 4;
var default_max_nodes_fr = 1000;

var default_icon_size = 20;
var focus_icon_size = 25;


function findInArray1D(arr, element) {
    for (var i = 0; i < arr.length; i++) {
        if (arr[i] == element) {
            return i;
        }
    }
    return -1;
}

function findInArray(arr, element) {
    for (var i = 0; i < arr.length; i++) {
        if (arr[i][0] == element) {
            return i;
        }
    }
    return -1;
}

var dbs;
$(document).ready(function() {

    $("#choose-dataset").css("background-color", "#E0E0E0");

    $(".menu-header").click(function() {
        $(".menu-header").css("background-color", "#f5f5f5");
        $(this).css("background-color", "#E0E0E0");
    });
    var url = "./getattr.php?attr=all";
    return $.getJSON(url, function(data) {
        dbs = data;
        generateNetworks();

    });

    $('.refresh-graph').click(function() {
        generateNetworks();
    });

    $('.refresh-graph-comp').click(function() {
        console.log("click on refresh");
//        generateModalContent();
    });

});

getEntityNodes = function(dataset) {
//    alert(dataset);
    var url = "./getattr.php?dataset=" + dataset + "&attr=entity_nodes";
    return $.getJSON(url, function(data) {
//        console.log("get " + file_loc);
//        alert(file_loc);
//        alert(entity_nodes_json);
//        var data = JSON.parse(entity_nodes_json);
//        alert("salam" + data);
        return data;
    });
}

function findNodes(nodes, name) {
    for (var i = 0; i < nodes.length; i++) {
//        console.log(nodes.name);
        if (nodes[i].name == name) {
            return nodes[i];
        }
    }
    return null;
}
function filterGraph(seed, radius, max_nodes) {
    var generic_url = "./filtergraph.php?";
    generic_url += "seed=" + escape(seed) + "&";
    generic_url += "radius=" + radius + "&";
    generic_url += "max_nodes=" + max_nodes + "&";
    var selectedDataset = $(".datasetpicker").select().val();
//    alert(selectedDataset);
    generic_url += "dataset=" + selectedDataset;

    var entitynd = getEntityNodes(selectedDataset);
    entitynd.then(function(entityNodes) {

        var src_url = generic_url + "&schema=src";
        var target_url = generic_url + "&schema=target";
        console.log("src_url " + src_url);
        console.log("target_url " + target_url);
        $.get(src_url, function(file_loc) {
            console.log("get " + file_loc);
//        alert(file_loc);
            generateGraph("#trans-src", file_loc, entityNodes, "source");
        });
        $.get(target_url, function(file_loc) {
            console.log("get " + file_loc);
//        alert(file_loc);
            generateGraph("#trans-target", file_loc, entityNodes, "target");
        });
        $(".refresh-graph").css('visibility', 'visible');
    });
}


function generateModalContent(result_node, query_node, src_ranking_list, target_ranking_list, other_nodes) {
    console.log("generateModalContent started.");
    var generic_url = "./filtergraph.php?";
    generic_url += "compare=1&";
    generic_url += "result_node=" + escape(result_node) + "&";
    generic_url += "query_node=" + escape(query_node) + "&";
    generic_url += "other_nodes=" + escape(JSON.stringify(other_nodes)) + "&";
    generic_url += "radius=" + default_radius_fr + "&";
    generic_url += "max_nodes=" + default_max_nodes_fr + "&";
    var selectedDataset = $(".datasetpicker").select().val();
    var entitynd = getEntityNodes(selectedDataset);
    entitynd.then(function(entityNodes) {

        generic_url += "dataset=" + selectedDataset;
        var src_url = generic_url + "&schema=src";
        var target_url = generic_url + "&schema=target";

        console.log("src_url " + src_url);
        console.log("target_url " + target_url);
        $.get(src_url, function(file_loc) {
            console.log("get " + file_loc);
//        alert(file_loc);
            generateGraphForRanking("#modal-content-src", file_loc, result_node, query_node, src_ranking_list, entityNodes, other_nodes);
        });
        $.get(target_url, function(file_loc) {
            console.log("get " + file_loc);
//        alert(file_loc);
            generateGraphForRanking("#modal-content-target", file_loc, result_node, query_node, target_ranking_list, entityNodes, other_nodes);

        });
    });
}


function generateNetworks() {
    $(".refresh-graph").css('visibility', 'hidden');
//    var datafile = "data.json";
//    var src_datafile = "data/uw-courses_small/source.json";
//    var target_datafile = "data/uw-courses_small/target.json";
//    var datafile =  "data/samplegraph/data.json";
//    var src_datafile = "data/yods/schema-src.json";
//    var src_datafile = "data/yods/output/out-31137/schema-src.json";
//    var target_datafile = "data/yods/schema-target.json";


//    generateGraph("#trans-src", src_datafile);
//    generateGraph("#trans-target", target_datafile);

    var generic_url = "./filtergraph.php?";
    generic_url += "nofilter=1&";
    var selectedDataset = $(".datasetpicker").select().val();
    var entitynd = getEntityNodes(selectedDataset);
    entitynd.then(function(entityNodes) {
//        alert(result);

        console.log(">>>>>>>>>>>>>>>>>>");
        console.log(entityNodes);
        generic_url += "dataset=" + selectedDataset;
        var src_url = generic_url + "&schema=src";
        var target_url = generic_url + "&schema=target";
        console.log("src_url " + src_url);
        console.log("target_url " + target_url);
        $.get(src_url, function(file_loc) {
            console.log("get no filter: " + file_loc);
//        alert(file_loc);
            generateGraph("#trans-src", file_loc, entityNodes, "source");
        });
        $.get(target_url, function(file_loc) {
            console.log("get no filter: " + file_loc);
//        alert(file_loc);
            generateGraph("#trans-target", file_loc, entityNodes, "target");
        });
        $(".refresh-graph").css('visibility', 'visible');
    });
}

function generateGraph(container, inputdata, entityNodes, schema) {
//    alert(entityNodes);

    var dataset = $(".datasetpicker").select().val();
    console.log(dbs);
    console.log(schema);
    console.log(dataset);
//    alert(dbs[dataset]["schema"][schema]["param"]["distance"]);
    d3.select(container).html("");
    var width = $(container).width();
//        alert(width);
//            height = $("#rwr-src").width();
    var height = Math.max(400, ($(window).height()) - 200);
    console.log("width = " + width);
    console.log("height = " + height);
//    alert(width);
//    alert(height);
    var color = d3.scale.category10();
    var force = d3.layout.force()
//            .gravity(.1)
            .distance(dbs[dataset]["schema"][schema]["param"]["distance"])
            .charge(dbs[dataset]["schema"][schema]["param"]["charge"])
//            .friction(.4)
            .size([width, height]);
    safety = 0;
    var svg = d3.select(container).append("svg")
            .attr("width", width)
            .attr("height", height);
    var drag = force.drag()
            .on("dragstart", dragstart);
//            .on("drag", movingNode);

    d3.json(inputdata, function(error, graph) {
        if (error) {
            throw error;
        }
//        var n = graph.nodes.length;
//        graph.nodes.forEach(function(d, i) {
//            d.x = d.px = (width) * Math.sin(i * 2 * Math.PI / n);
//            d.y = d.py = (height) * (-Math.cos(i * 2 * Math.PI / n));
//                d.px = d.py = width * i / n;

//        });

///////////////////////////
        force
                .nodes(graph.nodes)
                .links(graph.links);


        force.start();

        var n = 10;
//        force.start();
//        for (var i = n * n; i > 0; --i)
//            force.tick();
//        force.stop();
///////////////////////////
//            while (force.alpha() > 0.001) { // You'll want to try out different, "small" values for this
//                console.log(safety + " " + force.alpha());
//                force.tick();
//                if (safety++ > 5000) {
//                    break;// Avoids infinite looping in case this solution was a bad idea
//                }
//            }
//            if (safety < 5000) {
//                console.log('success??');
//            }
//            force.stop();
//            force.start();
//            force.stop();
//            force.start();
//            for (var i = 0; i < 20; ++i)
//                force.tick();
//            force.stop();

        var link = svg.selectAll(".link")
                .data(graph.links)
                .enter().append("line")
                .attr("class", "link");

        var node = svg.selectAll(".node")
                .data(graph.nodes)
                .enter().append("g")
                .attr("class", "node")
                .attr("data-id", function(d) {
                    return d.dataId;
                })
                .on("dblclick", dblclick)
                .call(force.drag);

//
//        var link = svg.selectAll(".link")
//                .data(graph.links)
//                .enter().append("line")
//                .attr("class", "link")
//                .style("stroke-width", function(d) {
//                    return Math.sqrt(d.value);
//                });
//        var nodes = d3.range(n).map(function() {
//            return {};
//        });
//        var links = d3.range(n).map(function(d) {
//            return {
//                source: d,
//                target: (d + 3) % n
//            };
//        });

//        console.log("links");
//        console.log(links);
//        var node = svg.selectAll(".node")
//                .data(graph.nodes)
//                .enter().append("g") // was circle
//                .attr("class", "node")
//                .attr("cx", "100")
//                .attr("cy", "100")
//                .attr("data-id", function(d) {
//                    return d.dataId;
//                })
//                .attr("r", 5)
//                    .classed("fixed", true )
        //                    
//                    .attr("fixed", "true")
//                    .attr("x", width / 2)
//                    .attr("y", height / 2)
//                .style("fill", function(d) {
//                    return color(d.group);
//                })
//                .on("dblclick", dblclick)
//                    .on("mouseup", function(d){
//                        dragstart(d);
//                        force.tick();
//                    })
//                .call(drag);
//        node.append("title")
//                .text(function(d) {
//                    return d.name + " (" + d.type + ")";
//                });

        node.append("image")
                .attr("xlink:href", function(d) {
                    return "img/icons/" + d.type + ".png";
                })
                .attr("x", -default_icon_size / 2)
                .attr("y", -default_icon_size / 2)
                .attr("width", default_icon_size)
                .attr("height", default_icon_size);

        node.append("text")
                .attr("dx", 15)
                .attr("dy", ".35em")
                .text(function(d) {
                    var isInList = jQuery.inArray(d.type, entityNodes);
                    if (isInList == -1) {
                        return "";
                    } else {
                        return d.name;
                    }
                });
//            force.stop();

//            node.append("text")
//                    .attr("dx", 12)
//                    .attr("dy", ".35em")
//                    .text(function(d) {
//                        return d.name
//                    })
//                    .style("color", "black");

        force.on("tick", function() {
            link.attr("x1", function(d) {
                return d.source.x;
            })
                    .attr("y1", function(d) {
                        return d.source.y;
                    })
                    .attr("x2", function(d) {
                        return d.target.x;
                    })
                    .attr("y2", function(d) {
                        return d.target.y;
                    });
//            node.attr("cx", function(d) {
//                return d.x;
//            })
//                    .attr("cy", function(d) {
//                        return d.y;
//                    })
//                node.attr("transform", function(d) {
//                    return "translate(" + d.x + "," + d.y + ")";
//                });
            node.attr("transform", function(d) {
                return "translate(" + d.x + "," + d.y + ")";
            });

        });

    });
    function dblclick(d) {
//            d3.select(this).classed("fixed", d.fixed = false).style("fill", function(d) {
//                return color(d.group);
//            });
//        alert("dbclick");
        filterGraph(d.name, default_radius, default_max_nodes);
        var selector = ".node[data-id=\"" + d.dataId + "\"] image";
        console.log("selector = " + selector);
        d3.selectAll(".trans-graph > svg").selectAll(selector)
                .attr("width", 25)
                .attr("height", 25)
//                    .attr("y", d.y)
//                    .attr("cx", d.x)
//                    .attr("cy", d.y)
                .classed("fixed", d.fixed = false);
//                    .style("fill", "#f00");

    }

    function dragstart(d) {
        console.log(d.x);
//            d3.select(this).classed("fixed", d.fixed = true).style("fill", "#f00");
        var selector = ".node[data-id=\"" + d.dataId + "\"]";
        console.log("selector = " + selector);
        d3.selectAll(".trans-graph > svg").selectAll(selector)
                .classed("fixed", d.fixed = true);

        d3.selectAll(".trans-graph > svg").selectAll(selector + " image")
                .attr("width", focus_icon_size)
                .attr("height", focus_icon_size)
                .attr("x", -focus_icon_size / 2)
                .attr("y", -focus_icon_size / 2);

        d3.selectAll(".trans-graph > svg").selectAll(selector + " text")
                .attr("dx", 25)
                .attr("dy", ".35em");
    }


    function movingNode(d) {
//        console.log("movedown");

        var selector = ".node[data-id=\"" + d.dataId + "\"]";
        selected_nodes = d3.selectAll(".trans-graph > svg").selectAll(selector)
                .attr("x", d.x)
                .attr("y", d.y)
                .attr("px", d.x)
                .attr("py", d.y);

        console.log(selected_nodes);
//        d3.selectAll(".trans-graph > svg").selectAll(selector)
//                .classed("fixed", d.fixed = true);
//        selected_nodes.attr("transform", function(dd) {
//            console.log("translate(" + d.x + "," + d.y + ")");
//            return "translate(" + d.x + "," + d.y + ")";
//        });
        force.tick();
//        var link = svg.selectAll(selector + " .link");
//        link.attr("x1", function(d) {
//            return d.source.x;
//        })
//                .attr("y1", function(d) {
//                    return d.source.y;
//                })
//                .attr("x2", function(d) {
//                    return d.target.x;
//                })
//                .attr("y2", function(d) {
//                    return d.target.y;
//                });
//        selected_nodes[0].attr("transform", function(d) {
//            return "translate(" + d.x + "," + d.y + ")";
//        });

//        nodes.forEach(function(o, i) {
//            o.x += (Math.random() - .5) * 40;
//            o.y += (Math.random() - .5) * 40;
//        });
//        force.resume();
    }
//
//    function tick(node) {
//        // Update positions of circle elements.
//        node.attr("cx", function(d) {
//            return d.x;
//        })
//        .attr("cy", function(d) {
//            return d.y;
//        });
//    }
}




function generateGraphForRanking(container, inputdata, result_node, query_node, ranking_list, entityNodes, other_nodes) {
    console.log("result_node: " + result_node);
    console.log("query_node: " + query_node);
//    console.log("ranking list:");
//    console.log(ranking_list);
    d3.select(container).html("");
    var width = $(container).width();
    var height = Math.max(400, ($(window).height() * 0.9) - 165);
    console.log("width = " + width);
    console.log("height = " + height);
    var color = d3.scale.category10();
    var force = d3.layout.force()
            .gravity(.04)
            .distance(90)
            .charge(-200)
            .friction(.8)
            .size([width, height]);
    safety = 0;
    var svg = d3.select(container).append("svg")
            .attr("width", width)
            .attr("height", height);
    var drag = force.drag()
            .on("dragstart", dragstart);
    d3.json(inputdata, function(error, graph) {
        if (error) {
            throw error;
        }

        force
                .nodes(graph.nodes)
                .links(graph.links);
//        console.log(graph.nodes);
        var query_node_obj = findNodes(graph.nodes, query_node);
        console.log("query_obj");
        console.log(query_node_obj);
        query_node_obj.x = width / 2;
        query_node_obj.y = height / 2;
        query_node_obj.fixed = true;

        var r = 200;
        other_nodes.push(result_node);
        other_nodes = jQuery.unique(other_nodes);
        var count = other_nodes.length;
        console.log("other nodes");
        console.log(other_nodes);
        for (var i = 0; i < count; i++) {
            var oth_node = findNodes(graph.nodes, other_nodes[i]);
            oth_node.x = query_node_obj.x + (r * Math.sin((i * 2 * Math.PI + count * Math.PI / 4) / count));
            oth_node.y = query_node_obj.y + (r * Math.cos((i * 2 * Math.PI + count * Math.PI / 4) / count));
            oth_node.fixed = true;
        }

        force.start();

        var n = 10;

        var link = svg.selectAll(".link")
                .data(graph.links)
                .enter().append("line")
                .attr("class", "link");

        var node = svg.selectAll(".node")
                .data(graph.nodes)
                .enter().append("g")
                .attr("class", "node")
                .attr("data-id", function(d) {
                    return d.dataId;
                })
                .on("dblclick", dblclick)
                .call(force.drag);


        node.append("image")
                .attr("xlink:href", function(d) {
                    return "img/icons/" + d.type + ".png";
                })
                .attr("x", -default_icon_size / 2)
                .attr("y", -default_icon_size / 2)
                .attr("width", function(d) {
                    if (d.name == result_node) {
                        return 35;
                    } else if (d.name == query_node) {
                        return 45;
                    } else {
                        return default_icon_size;
                    }
                })
                .attr("height", function(d) {
                    if (d.name == result_node || d.name == query_node) {
                        return 40;
                    } else {
                        return default_icon_size;
                    }
                });

        node.append("text")
                .attr("dx", 15)
                .attr("dy", ".35em")
                .text(function(d) {
                    var isInList = jQuery.inArray(d.type, entityNodes);
                    if (isInList == -1) {
                        return "";
                    } else {
//                        var rank = jQuery.inArray(d.name, ranking_list);
                        var rank = findInArray(ranking_list, d.name);
                        if (d.name == result_node) {
                            return d.name + "* (" + (rank + 1) + ")" + " [" + ranking_list[rank][1] + "]";
                        }
                        if (d.name == query_node) {
                            return d.name + " (q)";
                        }

                        if (rank != -1) {
                            var rank_in_other_nodes = findInArray1D(other_nodes, d.name);
                            console.log(other_nodes);
                            console.log(d.name);
                            console.log(rank_in_other_nodes);
                            if (rank_in_other_nodes != -1) {
                                return d.name + " (" + (rank + 1) + ")" + " [" + ranking_list[rank][1] + "]";
                            } else {
                                return "";
                            }
                        }
                        return d.name;
                    }

                });

        force.on("tick", function() {
            link.attr("x1", function(d) {
                return d.source.x;
            })
                    .attr("y1", function(d) {
                        return d.source.y;
                    })
                    .attr("x2", function(d) {
                        return d.target.x;
                    })
                    .attr("y2", function(d) {
                        return d.target.y;
                    });

            node.attr("transform", function(d) {
                return "translate(" + d.x + "," + d.y + ")";
            });

        });

    });
    function dblclick(d) {
        filterGraph(d.name, radius_fr_filter, max_nodes_fr_filter);
        var selector = ".node[data-id=\"" + d.dataId + "\"] image";
        console.log("selector = " + selector);
        d3.selectAll(".compare-graph > svg").selectAll(selector)
                .attr("width", 25)
                .attr("height", 25)
                .attr("x", -25 / 2)
                .attr("y", -25 / 2)
                .classed("fixed", d.fixed = false);

    }

    function dragstart(d) {
        console.log(d.x);
        var selector = ".node[data-id=\"" + d.dataId + "\"]";
        console.log("selector = " + selector);
        d3.selectAll(".compare-graph > svg").selectAll(selector)
                .classed("fixed", d.fixed = true);

        d3.selectAll(".compare-graph > svg").selectAll(selector + " image")
                .attr("width", 45)
                .attr("height", 45)
                .attr("x", -45 / 2)
                .attr("y", -45 / 2);

        d3.selectAll(".compare-graph > svg").selectAll(selector + " text")
                .attr("dx", 25)
                .attr("dy", ".35em");
    }


}