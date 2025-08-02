<?php
require 'vendor/autoload.php';
define('PROJECT_ROOT', __DIR__);
$pdo = getDB();

$rows = getAllRows($pdo);
foreach ($rows as $row) {
    $id = $row['id'];
    $title = $row['title'];
    $matched_pattern = Keyword::isObviousNotLaw($title);
    if ($matched_pattern !== false) {
        echo "$id: not law detected.\n";
        updateIsLawRelated($id, 0, $matched_pattern, '', $pdo);
    }
}
