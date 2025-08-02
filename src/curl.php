<?php
function curl_list()
{
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, "https://www.president.gov.tw/Page/129");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $res = curl_exec($curl);
    $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    file_put_contents(PROJECT_ROOT . "/html/list.html", $res);
    return $status_code;
}

function curl_page($page_id)
{
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, "https://www.president.gov.tw/Page/294/$page_id");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $res = curl_exec($curl);
    $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    file_put_contents(PROJECT_ROOT . "/html/$page_id.html", $res);
    return $status_code;
}

function law_query($law_name_input)
{
    $curl = curl_init();    

    curl_setopt($curl, CURLOPT_URL, 'https://ly.govapi.tw/v2/laws?q="' .  $law_name_input . '"&limit=1');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $res = curl_exec($curl);
    $data = json_decode($res, JSON_UNESCAPED_UNICODE);
    $laws = $data['laws'];

    if (empty($laws)) {
        return null;
    }

    $law = $laws[0];
    return $law;
}
