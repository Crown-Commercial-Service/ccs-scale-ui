<?php
echo 'Test 1';
echo '<br/>';
if (!empty($_GET['debug'])) {
    echo '<pre>';

    var_dump($_SERVER);
}
var_dump($_GET);
echo 'Hellor world';

// Test conn to GM service
$opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "x-api-key: ".$_ENV["GUIDED_MATCH_SERVICE_API_KEY"]."\r\n"
  )
);

$context = stream_context_create($opts);
$file = file_get_contents($_ENV["GUIDED_MATCH_SERVICE_ROOT_URL"]."/scale/guided-match-service/search-journeys/housing", false, $context);
echo $file;

// Test conn to agreements service
$agr_opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-language: en\r\n" .
              "x-api-key: ".$_ENV["AGREEMENTS_SERVICE_API_KEY"]."\r\n"
  )
);

$agr_context = stream_context_create($agr_opts);
$agr_file = file_get_contents($_ENV["AGREEMENTS_SERVICE_ROOT_URL"]."/scale/agreements-service/agreements/", false, $agr_context);
echo $agr_file;

?>
