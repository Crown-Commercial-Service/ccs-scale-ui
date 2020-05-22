<?php
echo 'Test 10';
echo '<br/>';
if (!empty($_GET['debug'])) {
    echo '<pre>';
    
    var_dump($_SERVER);
}
var_dump($_ENV);
var_dump($_GET);
echo 'Hellor world';
?>