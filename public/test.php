<?php
echo 'Test 1';
echo '<br/>';
if (!empty($_GET['debug'])) {
    echo '<pre>';

    var_dump($_SERVER);
}
var_dump($_GET);
echo 'Hellor world';

// Create a stream
$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "x-api-key: ".$_ENV["GUIDED_MATCH_SERVICE_API_KEY"]."\r\n"
  )
);

$context = stream_context_create($opts);

// Open the file using the HTTP headers set above
$file = file_get_contents($_ENV["GUIDED_MATCH_SERVICE_ROOT_URL"]."/scale/guided-match-service/search-journeys/housing", false, $context);
echo $file;

?>
