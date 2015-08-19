var loc_result_dir;
var loc_queries;
$(document).ready(function() {

    $(document).on('change', '#topk input:radio', function(event) {
        var k = ($(this).val());
        doIt(loc_queries, loc_result_dir, k);
    });

    $('.search-button').click(function() {
        doIt(loc_queries, loc_result_dir);
        generateAllGraphs();
    });


});



function initRankingView(query_file) {
    console.log("init Ranking View >>>>>>>> " + query_file);
    readFile(query_file, function(queries) {
        var words = [];
        for (var i = 1; i < queries.length; i++) {
            words.push(queries[i]);
        }
        $(".search-box").autocomplete({
            source: words
        });

        $(".search-box").val(words[0]);

        var selectedVal = $(".datasetpicker").select().val();
        var url = "./getattr.php?attr=result_dir&dataset=" + selectedVal;
        $.get(url, function(result_dir) {
//        console.log("get " + file_loc);
            loc_result_dir = result_dir;
            loc_queries = queries;
            doIt(queries, result_dir);
        });
    });
}

function doIt(query_list, result_dir, k) {
    var q = $('.search-box').val();
//        alert(q);
    var q_index = 0;
    for (var i = 1; i < query_list.length; i++) {
        if (query_list[i] == q) {
            q_index = i;
            break;
        }
    }
    var tk;
    if (k === undefined) {
        tk = $('#topk label.active input').val();
    } else {
        tk = k;
    }
//        alert(tk);
    generateAllLists(result_dir, q_index, tk, q);
}