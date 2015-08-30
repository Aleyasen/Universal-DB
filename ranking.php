<div class="row">
    <?php include 'searchbar.php'; ?>
</div>
<div class="row">
    <div class="col-md-1">
        <div style="height: 180px; margin-top: 120px;" role="tablist">
            <div class="panel-heading btn-block menu-header" style="height: 100%;">
                <br>
                <center>

                    <div class="pageButtonContainer rightArrow">
                        <div class="pageButton">
                            <a href="#network" aria-controls="network" role="tab" data-toggle="tab">
                                <div style="margin-bottom: 8px;"><img src="img/arrow-left.svg" alt=""></div>
                                <div><img src="img/network.png" width="45px"></div>
                                <div>Choose Dataset</div>
                            </a>
                        </div>
                    </div>
                </center>
            </div>
        </div>

    </div>
    <div class="col-md-11">
        <div class="row">
            <div class="col-md-3 ranking-col">
                <div>
                    <div class="col-md-12 highlight text-center">
                        RWR
                    </div>
                    <div class="col-md-12 highlight text-center">
                        <div class="row" style="margin-bottom: 0px;">
                            <div class="col-md-6 text-center source-dataset-title">

                            </div>
                            <div class="col-md-6 text-center target-dataset-title">

                            </div>
                        </div>
                        <div id="ranking1"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 ranking-col">
                <div>
                    <div class="col-md-12 highlight text-center">
                        SimRank
                    </div>
                    <div class="col-md-12 highlight text-center">
                        <div class="row" style="margin-bottom: 0px;">
                            <div class="col-md-6 text-center source-dataset-title">

                            </div>
                            <div class="col-md-6 text-center target-dataset-title">

                            </div>
                        </div>
                        <div id="ranking2"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 ranking-col">
                <div>
                    <div class="col-md-12 highlight text-center">
                        PathSim
                    </div>
                    <div class="col-md-12 highlight text-center">
                        <div class="row" style="margin-bottom: 0px;">
                            <div class="col-md-6 text-center source-dataset-title">

                            </div>
                            <div class="col-md-6 text-center target-dataset-title">

                            </div>
                        </div>
                        <div id="ranking3"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 ranking-col">
                <div>
                    <div class="col-md-12 highlight text-center">
                        Universal-DB
                    </div>
                    <div class="col-md-12 highlight text-center">
                        <div class="row" style="margin-bottom: 0px;">
                            <div class="col-md-6 text-center source-dataset-title">

                            </div>
                            <div class="col-md-6 text-center target-dataset-title">

                            </div>
                        </div>
                        <div id="ranking4"></div>
                    </div>
                </div>
            </div>

            <div id="dialog_2" title="Algorithms Comparison">
                <!--<div>Query: XYZ</div>-->
                <?php include 'network_modal.php'; ?>
            </div>
        </div>

    </div>
</div>