<?php
require 'vendor/autoload.php';
define('PROJECT_ROOT', __DIR__);
$pdo = getDB();

$rows = getAllRowsWithLawName($pdo);
foreach ($rows as $row) {
    $id = $row['id'];
    $law_name = $row['law_name'];
    $law_id = Keyword::getLawID($law_name);
    if (!empty($law_id)) {
        updateLawID($id, $law_id, $pdo);
    }
}
