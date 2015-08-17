<div class="row">
    <?php include 'searchbar.php'; ?>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="inner-container">
            <div class="col-md-12 highlight text-center">
                RWR
            </div>
            <div class="col-md-12 highlight text-center">
                <div id="ranking1"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="inner-container">
            <div class="col-md-12 highlight text-center">
                SimRank
            </div>
            <div class="col-md-12 highlight text-center">
                <div id="ranking2"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="inner-container">
            <div class="col-md-12 highlight text-center">
                PathSim
            </div>
            <div class="col-md-12 highlight text-center">
                <div id="ranking3"></div>
            </div>
        </div>
    </div>

    <div id="dialog" title="Algorithms Comparison">
        <!--<div>Query: XYZ</div>-->
        <?php include 'network_modal.php'; ?>
    </div>
</div>
