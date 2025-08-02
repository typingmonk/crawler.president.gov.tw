<?php

function getDB()
{
    $pdo = new PDO('sqlite:' . PROJECT_ROOT . '/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    return $pdo;
}

function getNextPageID($pdo)
{
    $stmt = $pdo->query('SELECT MAX(id) AS max_id FROM gazette_article');
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $page_id = $row['max_id'] ?? 33027; //33027 is the oldest page_id
    $page_id = (int) $page_id + 1;

    return $page_id;
}

function insertGazetteData($pdo, $data)
{
    $stmt = $pdo->prepare('INSERT INTO gazette_article (id, title, date, gazette_index) VALUES (:id, :title, :date, :gazette_index)');
    $success = $stmt->execute($data);

    return $success;
}

function updateIsLawRelated($id, $is_law_related, $matched_pattern, $pdo) {
    $stmt = $pdo->prepare('UPDATE gazette_article SET is_law_related = :is_law_related, matched_pattern = :matched_pattern WHERE id = :id');
    $result = $stmt->execute([
        ':is_law_related' => $is_law_related,
        ':matched_pattern' => $matched_pattern,
        ':id' => $id
    ]);

    return $result;
}

function getAllRows($pdo)
{
    $stmt = $pdo->query("SELECT * FROM gazette_article ORDER BY id DESC");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $rows;
}

function getLawRelatedRows($pdo)
{
    $stmt = $pdo->query("SELECT * FROM gazette_article WHERE (title LIKE '%條文' OR title LIKE '%條例') AND date >= '2016-05-20' ORDER BY date DESC, id DESC");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $rows;
}


