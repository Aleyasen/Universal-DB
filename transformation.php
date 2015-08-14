<div class="row">
    <div class="col-md-offset-0 col-md-3 input-group" style="padding-bottom: 10px;">
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
<div class="row">
    <!--    <div class="col-md-1">
            <div class="row">
                <div class="col-md-12 vertical-text highlight text-center">
                    Source
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 vertical-text highlight text-center">
                    Target
                </div>
            </div>
        </div>-->
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