<?php
require 'vendor/autoload.php';
define('PROJECT_ROOT', __DIR__);
$pdo = getDB();

$rows = getAllRows($pdo);
foreach ($rows as $row) {
    $id = $row['id'];
    $title = $row['title'];
    $is_not_law = Keyword::isObviousNotLaw($title);
    if ($is_not_law !== false) {
        echo "$id: not law detected.\n";
        updateIsLawRelated($id, !$is_not_law, $pdo);
    }
}
