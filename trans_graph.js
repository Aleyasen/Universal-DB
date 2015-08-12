
$(document).ready(function() {

    generateNetworks();

    $('.refresh-graph').click(function() {
        generateNetworks();
    });

});

function filterGraph(seed, radius, max_nodes) {
    var generic_url = "./filtergraph.php?";
    generic_url += "seed=" + seed + "&";
    generic_url += "radius=" + radius + "&";
    generic_url += "max_nodes=" + max_nodes;
    var src_url = generic_url + "&schema=src";
    var target_url = generic_url + "&schema=target";
    console.log("src_url " + src_url);
    console.log("target_url " + target_url);
    $.get(src_url, function(file_loc) {
        console.log("get " + file_loc);
//        alert(file_loc);
        generateGraph("#trans-src", file_loc);
    });
    $.get(target_url, function(file_loc) {
        console.log("get " + file_loc);
//        alert(file_loc);
        generateGraph("#trans-target", file_loc);
    });
    $(".refresh-graph").css('visibility', 'visible');
}

function generateNetworks() {
    $(".refresh-graph").css('visibility', 'hidden');
//    var datafile = "data.json";
//    var src_datafile = "data/uw-courses_small/source.json";
//    var target_datafile = "data/uw-courses_small/target.json";
//    var datafile =  "data/samplegraph/data.json";
    var src_datafile = "data/yods/schema-src.json";
//    var src_datafile = "data/yods/output/out-31137/schema-src.json";
    var target_datafile = "data/yods/schema-target.json";


    generateGraph("#trans-src", src_datafile);
    generateGraph("#trans-target", target_datafile);
}

function generateGraph(container, inputdata) {
    d3.select(container).html("");
    var width = $(container).width();
//        alert(width);
//            height = $("#rwr-src").width();
    var height = 400;
//    alert(width);
//    alert(height);
    var color = d3.scale.category10();
    var force = d3.layout.force()
//                .charge(-40)
//                .linkDistance(10)
//                .friction(0.9)
//                .alpha(.1)
            .size([width, height]);

    safety = 0;

    var svg = d3.select(container).append("svg")
            .attr("width", width)
            .attr("height", height);


    var drag = force.drag()
            .on("dragstart", dragstart);


    d3.json(inputdata, function(error, graph) {
        if (error)
            throw error;
        var n = graph.nodes.length;
//        graph.nodes.forEach(function(d, i) {
//            d.x = d.px = (width) * Math.sin(i * 2 * Math.PI / n);
//            d.y = d.py = (height) * (-Math.cos(i * 2 * Math.PI / n));
//                d.px = d.py = width * i / n;

//        });
        force
                .nodes(graph.nodes)
                .links(graph.links);


        force.start();
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
                .attr("class", "link")
                .style("stroke-width", function(d) {
                    return Math.sqrt(d.value);
                });

        var node = svg.selectAll(".node")
                .data(graph.nodes)
                .enter().append("circle")
                .attr("class", "node")
                .attr("data-id", function(d) {
                    return d.dataId;
                })
                .attr("r", 5)
//                    .classed("fixed", true )
                //                    
//                    .attr("fixed", "true")
//                    .attr("x", width / 2)
//                    .attr("y", height / 2)
                .style("fill", function(d) {
                    return color(d.group);
                })
                .on("dblclick", dblclick)
//                    .on("mouseup", function(d){
//                        dragstart(d);
//                        force.tick();
//                    })
                .call(drag);

        node.append("title")
                .text(function(d) {
                    return d.name + " (" + d.type + ")";
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
            node.attr("cx", function(d) {
                return d.x;
            })
                    .attr("cy", function(d) {
                        return d.y;
                    })
//                node.attr("transform", function(d) {
//                    return "translate(" + d.x + "," + d.y + ")";
//                });

        });
    });


    function dblclick(d) {
//            d3.select(this).classed("fixed", d.fixed = false).style("fill", function(d) {
//                return color(d.group);
//            });
//        alert("dbclick");
        filterGraph(d.name, 1, 20);
        var selector = ".node[data-id=\"" + d.dataId + "\"]";
        console.log("selector = " + selector);
        d3.selectAll(".trans-graph > svg").selectAll(selector)
                .attr("r", 5)
//                    .attr("x", d.x)
//                    .attr("y", d.y)
//                    .attr("cx", d.x)
//                    .attr("cy", d.y)
                .classed("fixed", d.fixed = false);
//                    .style("fill", "#f00");

    }

    function dragstart(d) {
//            d3.select(this).select("text").transition()
//                    .duration(750)
//                    .attr("x", 22)
//                    .style("fill", "steelblue")
//                    .style("stroke", "lightsteelblue")
//                    .style("stroke-width", ".5px")
//                    .style("font", "20px sans-serif");
//            d3.select(this).select("circle").transition()
//                    .duration(750)
//                    .attr("r", 16)
//                    .style("fill", "lightsteelblue");

        console.log(d.x);
//            d3.select(this).classed("fixed", d.fixed = true).style("fill", "#f00");
        var selector = ".node[data-id=\"" + d.dataId + "\"]";
        console.log("selector = " + selector);
        d3.selectAll(".trans-graph > svg").selectAll(selector)
                .attr("r", 10)
                .attr("x", d.x)
                .attr("y", d.y)
                .attr("px", d.x)
                .attr("py", d.y)
                .attr("cx", function(d) {
                    return d.x;
                })
                .attr("cy", function(d) {
                    return d.y;
                })
                .classed("fixed", d.fixed = true);
//                    .style("fill", "#f00");
    }
}