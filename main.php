<?php
require 'vendor/autoload.php';
define('PROJECT_ROOT', __DIR__);
$pdo = getDB();

curl_list();
$page_id_latest = getLatestPageID();
$page_id = getNextPageID($pdo);

while ($page_id <= $page_id_latest) {
    echo "Downloading gazette: $page_id ...\n";
    $status_code = curl_page($page_id);
    $data = retrieveData($page_id);
    if (isset($data)) {
        insertGazetteData($pdo, $data);
    } else {
        echo "failed.\n";
        echo "http status code: $status_code\n";
    }
    $page_id++;
    if ($page_id > $page_id_latest) {
        break;
    }
    sleep(60); //slow crawling
}
