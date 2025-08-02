<?php
use Symfony\Component\DomCrawler\Crawler;

function getLatestPageID()
{
    $html = file_get_contents(PROJECT_ROOT . "/html/list.html");
    if ($html == '') {
        return null;
    }
    $crawler = new Crawler($html);
    try {
        $url = $crawler->filter('.tblRoW')->eq(1)
            ->filter('[data-title="標題"] a')->filter('a')->attr('href');
    } catch (Exception $e) {
        echo "Exception class:" . get_class($e) . "\n";
        return null;
    }

    $page_id = explode('/', $url)[3];
    return (int) $page_id;
}

function retrieveData($page_id)
{
    $html = file_get_contents(PROJECT_ROOT . "/html/$page_id.html");
    if ($html == '') {
        return null;
    }
    $crawler = new Crawler($html);
    try {
        $title = $crawler->filter('.fmTitle1')->text();
    } catch (Exception $e) {
        echo "Exception class:" . get_class($e) . "\n";
        return null;
    }

    //get date and gazette index
    $date_n_index = $crawler->filter('h4.goldencolor.inline')->text();
    preg_match('/(\d+)年(\d+)月(\d+)日/', $date_n_index, $matches);
    $roc_year = (int) $matches[1];
    $year = $roc_year + 1911;
    $month = str_pad($matches[2], '0', STR_PAD_LEFT);
    $day = str_pad($matches[3], '0', STR_PAD_LEFT);
    $date = "{$year}-{$month}-{$day}";

    preg_match('/第(\d+)號/', $date_n_index, $matches);
    $gazette_index = $matches[1];

    return [
        ':id' => $page_id,
        ':title' => $title,
        ':date' => $date,
        ':gazette_index' => $gazette_index,
    ];
}
