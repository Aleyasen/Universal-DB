<div class="row">
    <?php include 'searchbar.php'; ?>
</div>
<div class="row">
    <div class="col-md-offset-0 col-md-3 input-group" style="padding-bottom: 10px;">
        <span class = "input-group-addon" style = "background-color: white;">Meta-walk</span>
        <select  class="selectpicker metawalkpicker">
            <option value="movie" selected="selected">conf-paper-conf</option>
            <option value="biblo" >conf-paper-domain-keyword-domain-paper-conf</option>
            <option value="course" >All the meta-walks</option>
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
    <div class="col-md-3">
        <div class="row">
            <div class="inner-container">
                <div class="col-md-12 highlight text-center">
                    RWR
                </div>
                <div class="col-md-12 highlight">
                    <div id="rwr-src"></div>
                </div>


                <div class="col-md-12 highlight">
                    <div id="rwr-target"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">

        <div class="row">

            <div class="inner-container">
                <div class="col-md-12 highlight text-center">
                    SimRank
                </div>
                <div class="col-md-12 highlight">
                    <div id="simrank-src"></div>
                </div>


                <div class="col-md-12 highlight">
                    <div id="simrank-target"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">

        <div class="row">

            <div class="inner-container">
                <div class="col-md-12 highlight text-center">
                    PathSim
                </div>
                <div class="col-md-12 highlight">
                    <div id="rpathsim-src"></div>
                </div>


                <div class="col-md-12 highlight">
                    <div id="rpathsim-target"></div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-3">

        <div class="row">

            <div class="inner-container">
                <div class="col-md-12 highlight text-center">
                    Universal-DB
                </div>
                <div class="col-md-12 highlight">
                    <div id="rpathsim2-src"></div>
                </div>


                <div class="col-md-12 highlight">
                    <div id="rpathsim2-target"></div>
                </div>
            </div>
        </div>
    </div>


</div>