<?php

if (!isset($_GET["c"])) {
    include 'network.php';
} else if (($_GET["c"]) == 1) {
    include 'import.php';
} else if (($_GET["c"]) == 2) {
    include 'transformation.php';
} else if (($_GET["c"]) == 3) {
    include 'network.php';
} else if (($_GET["c"]) == 4) {
    include 'ranking.php';
} else if (($_GET["c"]) == 5) {
    include 'setting.php';
}
?>