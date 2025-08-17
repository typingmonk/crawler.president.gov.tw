<?php
require 'vendor/autoload.php';
define('PROJECT_ROOT', __DIR__);
$pdo = getDB();

$rows = getAllRowsWithLawName($pdo);
foreach ($rows as $row) {
    $id = $row['id'];
    $law_name = $row['law_name'];
    $query_url = 'https://ly.govapi.tw/v2/laws?q="' . $law_name . '"&類別=母法&limit=10';
    $ret = file_get_contents($query_url);
    $json = json_decode($ret);
    $laws = $json->laws ?? [];
    if (empty($laws)) {
        //no matched law in db
        echo "no matched law in db: $law_name\n";
        continue;
    }
    $is_matched = false;
    foreach ($laws as $law) {
        $law_name_retrieved = $law->名稱;
        if ($law_name != $law_name_retrieved) {
            //law_name not exact matched
            continue;
        }

        $is_matched = true;
        //update law_id
        $law_id = $law->法律編號;
        echo "Matched: $law_id:$law_name\n";
        updateLawID($id, $law_id, $pdo);
        break;
    }

    if (!$is_matched) {
        echo "no law_name exact matched: $law_name\n";
    }
}
