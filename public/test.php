<?php
echo 'Test 1';
echo '<br/>';
if (!empty($_GET['debug'])) {
    echo '<pre>';

    var_dump($_SERVER);
}
var_dump($_GET);
echo 'Hellor world';

file_get_contents("https://o8eqlgajbi-vpce-09bc0b188b0cf0095.execute-api.eu-west-2.amazonaws.com/sbx2");
?>
