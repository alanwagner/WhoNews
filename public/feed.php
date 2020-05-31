<?php
/**
 * WhoNews : feed.php
 *
 * @author     Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2020 Alan G. Wagner
 * @license    GNU GPL 3.0
 */

$data = null;
$xml = null;

if (isset($_GET['pre'])) {
    echo("<pre>\n");
}

if (!empty($_GET['url'])) {

    $ch = curl_init();

    ob_start();

    // Configuration de l'URL et d'autres options
    curl_setopt($ch, CURLOPT_URL, $_GET['url']);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    // Récupération de l'URL
    curl_exec($ch);

    // Fermeture de la session cURL
    curl_close($ch);

    $data = ob_get_contents();
    ob_end_clean();
}

if (is_string($data)) {
    $xml=simplexml_load_string($data);
}


echo $data;

//print_r($xml);

if (isset($_GET['pre'])) {
    echo("\n</pre>\n");
}

