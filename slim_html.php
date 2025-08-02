<?php
require 'vendor/autoload.php';
define('PROJECT_ROOT', __DIR__);

$html = file_get_contents(PROJECT_ROOT . '/html/49957.html');

// Remove the <head>...</head> block
$html = preg_replace('/<head\b[^>]*>.*?<\/head>/is', '', $html);
// Remove all <script>...</script> blocks
$html = preg_replace('/<script\b[^>]*>.*?<\/script>/is', '', $html);
// Remove all <noscript>...</noscript> blocks
$html = preg_replace('/<noscript\b[^>]*>.*?<\/noscript>/is', '', $html);
$html = preg_replace('/<!--.*?-->/s', '', $html);
$html = preg_replace('/^\s*[\r\n]/m', '', $html);

file_put_contents(PROJECT_ROOT . '/html/49957.html', $html);
