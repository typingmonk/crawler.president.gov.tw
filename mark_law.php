<?php
require 'vendor/autoload.php';
define('PROJECT_ROOT', __DIR__);
$pdo = getDB();

$rows = getAllRows($pdo);
foreach ($rows as $row) {
    $id = $row['id'];
    $title = $row['title'];
    $result = Keyword::getLawNameType1($title);
    if ($result !== false) {
        echo "$id: law detected.\n";
        updateIsLawRelated($id, 1, $result[1], $result[0], $pdo);
    }
}
