<!DOCTYPE html>
<html>
    <head lang="en">
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="css/bootstrap-select.min.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="css/main.css"/>


        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootstrap-select.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.isotope/2.2.0/isotope.pkgd.min.js"></script>
        <script src="js/highcharts/highcharts.js"></script>
        <script src="js/highcharts/highcharts-3d.js"></script>
        <script src="js/highcharts/highcharts-more.js"></script>
        <script src="js/highcharts/modules/exporting.js"></script>
        <script src="js/highcharts/modules/data.js"></script>

        <script src="main.js"></script>
        <script src="formula.js"></script>
        <script src="chart.js"></script>

    </head>
    <body>

        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#"><b>Steel Calc</b></a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Help</a></li>
                    </ul>
                </div>
            </div>
        </nav>


        <div class="container-fluid">
            <div id="temp"></div>

            <div class="row">
                <div class="col-md-3 highlight">

                    <div class="row">
                        <div class="form-group col-md-12">
                            <center>
                                <div class="input-group btn-group" data-toggle="buttons">
                                    <label class="btn btn-default active">
                                        <input type="radio" value="beam"/> Bending
                                    </label>
                                    <label class="btn btn-default">
                                        <input type="radio" checked="checked" value="column"/> Compression
                                    </label>
                                  
                                    <label class="btn btn-default">
                                        <input type="radio" checked="checked" value="column"/> Combined
                                    </label>
                                </div>
                            </center>
                        </div>

                        <div class="form-group col-md-12">

                            <div class="input-group">
                                <!--<span class="input-group-btn">-->
                                <!--<button class="btn btn-default" type="button">Shape</button>-->
                                <!--</span>-->

                                <span class="input-group-addon" style="background-color: white;">Shape</span>
                                <select id="typepicker" class="form-control selectpicker" data-live-search="true">
                                </select>
                            </div>

                            <div class="input-group" style="margin-top:10px;">
                                <!--<span class="input-group-btn">-->
                                <!--<button class="btn btn-default" type="button">Length</button>-->
                                <!--</span>-->
                                <span class="input-group-addon">Effective Length</span>
                                <!--
                                    bending: unbraced lenght
                                -->
                                <input type="text" id="st-textbox" class="form-control" placeholder="Available Strength"
                                       aria-describedby="basic-addon1" value="50">
                                <span class="input-group-addon">ft</span>
                            </div>
                            
                              <div class="input-group" style="margin-top:10px;">
                                <!--<span class="input-group-btn">-->
                                <!--<button class="btn btn-default" type="button">Length</button>-->
                                <!--</span>-->
                                <span class="input-group-addon">KLy</span>
                                <!--
                                    bending: unbraced lenght
                                kly_equ = klx / (rx/ry)
                                
                                -->
                                <input type="text" id="st-textbox" class="form-control" placeholder="Available Strength"
                                       aria-describedby="basic-addon1" value="50">
                                <span class="input-group-addon">ft</span>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <button id="analytic-button" class="btn btn-default">Analysis</button>
                            <button id="design-button" class="btn btn-default">Design</button>
                        </div>
                    </div>

                </div>
                <div class="col-md-4 highlight">
                    <style type="text/css">
                        .tg {
                            border-collapse: collapse;
                            border-spacing: 0;
                        }

                        .tg td {
                            font-family: Arial, sans-serif;
                            font-size: 14px;
                            padding: 5px 5px;
                            border-style: solid;
                            border-width: 1px;
                            overflow: hidden;
                            word-break: normal;
                        }

                        .tg th {
                            font-family: Arial, sans-serif;
                            font-size: 14px;
                            font-weight: normal;
                            padding: 5px 5px;
                            border-style: solid;
                            border-width: 1px;
                            overflow: hidden;
                            word-break: normal;
                        }

                        .tg .tg-s6z2 {
                            text-align: center
                        }
                    </style>
                    <center>
                        <table class="tg table-bordered">
                            <tr>
                                <td id="shape" class="tg-s6z2" colspan="4"></td>
                            </tr>
                            <tr>
                                <td id="len" class="tg-s6z2" colspan="4"></td>
                            </tr>
                            <tr>
                                <th class="tg-s6z2">Pn/Ωc</th>
                                <th class="tg-s6z2">fcPn</th>
                                <th class="tg-s6z2">Mn/Ωb</th>
                                <th class="tg-s6z2">fbMn</th>
                            </tr>
                            <tr>
                                <td class="tg-s6z2" colspan="2">kips</td>
                                <td class="tg-s6z2" colspan="2">kip-ft</td>
                            </tr>
                            <tr>
                                <td class="tg-s6z2">ASD</td>
                                <td class="tg-s6z2">LRFD</td>
                                <td class="tg-s6z2">ASD</td>
                                <td class="tg-s6z2">LRFD</td>
                            </tr>
                            <tr>
                                <td class="tg-s6z2" id="kips_ASD"></td>
                                <td class="tg-s6z2" id="kips_LRFD"></td>
                                <td class="tg-s6z2" id="kip_ft_ASD"></td>
                                <td class="tg-s6z2" id="kip_ft_LRFD"></td>
                            </tr>

                        </table>
                </div>
                </center>

                <div class="col-md-4 highlight-white">
                    <div id="chart-container"></div>

                </div>
            </div>
        </div>
    </body>
</html>