<?php
if ($_GET['randomId'] != "Ln6Y95wZdconxXwJ17JrOzIEWm7oziotllOU5U6nBbHWFnahaJxMBBscCMJosOTa") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
