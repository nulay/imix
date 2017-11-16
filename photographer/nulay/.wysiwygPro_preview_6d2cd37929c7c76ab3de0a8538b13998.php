<?php
if ($_GET['randomId'] != "PdiAj9KQz3FXxKR8gVaNRx4fXpkybhr0cY6GdroIiCnND_LJ_uDDpNkHyYkvmN7G") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
