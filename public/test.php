<?php
if (!empty($_GET['debug'])) {
 echo '<pre>';
    var_dump($_SERVER);
}
echo 'Hellor world';
?>