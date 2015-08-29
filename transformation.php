
<div class="row" style="margin-top:35px;">
    <div class="col-md-11">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="inner-container">
                        <div class="col-md-12 highlight text-center" id="source-label">
                            Source
                        </div>
                        <div class="col-md-12 highlight">
                            <div class="refresh-graph" style="position: relative; padding:5px; float: right; ; visibility: hidden;">
                                <a><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></a>
                            </div>
                            <div id="trans-src" class="trans-graph"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">

                <div class="row">

                    <div class="inner-container">
                        <div class="col-md-12 highlight text-center" id="target-label">
                            Target
                        </div>
                        <div class="col-md-12 highlight">
                            <div class="refresh-graph" style="position: relative; padding:5px; float: right; visibility: hidden;">
                                <a><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></a>
                            </div>
                            <div id="trans-target" class="trans-graph"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-md-1">
        <div style="height: 180px; margin-top: 170px;" role="tablist">
            <div class="panel-heading btn-block menu-header" style="height: 100%;">
                <br>
                <center>

                    <div class="pageButtonContainer rightArrow">
                        <div class="pageButton">
                            <a href="#ranking" aria-controls="ranking" role="tab" data-toggle="tab">
                                <div style="margin-bottom: 5px;"><img src="img/arrow-right.svg" alt=""></div>
                                <div><img src="img/ranking.png" width="35px"></div>
                                <div>Compare Rankings</div>
                            </a>
                        </div>
                    </div>
                </center>
            </div>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-md-offset-0 col-md-3 input-group" style="padding-bottom: 0px;">
        <span class = "input-group-addon" style = "background-color: white;">Dataset</span>
        <select  class="selectpicker datasetpicker">
            <?php
            require_once 'utils.php';
//            readDatasetFiles();
//            c_log(print_r($dbs, true));

            foreach ($dbs as $elem) {
                ?>
                <option value="<?php echo $elem["id"]; ?>" selected="selected"><?php echo $elem["name"]; ?></option>
                <?php
            }
            ?>
        </select>
    </div>
</div>
