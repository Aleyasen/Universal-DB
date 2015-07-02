<?php
$string = file_get_contents("dbs.json");
$json_a = json_decode($string, true);
//if (!isset($_COOKIE["selected_db"])) {
//    setcookie("selected_db", "1");
//}
?>
<div class="row">
    <div class="col-md-6 highlight">
        <div style="margin:20px;">
            <h4>Available Databases</h4>
            <div class="list-group">
                <?php
                foreach ($json_a as $key => $value) {
                    if (isset($_COOKIE["selected_db"]) && $_COOKIE["selected_db"] == $value["id"]) {
                        ?>
                        <a href="#" data-id="<?php echo $value["id"]; ?>" class="list-group-item active db-link"><?php echo $value["name"]; ?></a>
                        <?php
                    } else {
                        ?>
                        <a href="#" data-id="<?php echo $value["id"]; ?>" class="list-group-item db-link"><?php echo $value["name"]; ?></a>

                        <?php
                    }
                }
                ?>
            </div>
            <br>
            <h4>Import New Database</h4>
            <span class="btn btn-default btn-file">

                Browse <input type="file">
            </span>
        </div>
    </div>
    <div class="col-md-4">


    </div>

</div>