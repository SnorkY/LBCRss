<?php
/**
 * Code minimalisme de génération de flux RSS pour Leboncoin.fr
 * @version 1.0
 */


$dirname = dirname(__FILE__);

require $dirname."/lib/feedgenerator/FeedGenerator.php";
require $dirname."/lib/lbc.php";

if (empty($_GET["url"])) {
    require $dirname."/form.php";
    return;
}

try {
    $_GET["url"] = Lbc::formatUrl($_GET["url"]);
} catch (Exception $e) {
    echo "Cette adresse ne semble pas valide.";
    exit;
}


$content = file_get_contents($_GET["url"]);
$ads = Lbc_Parser::process($content);

$title = "LeBonCoin";
$urlParams = parse_url($_GET["url"]);
if (!empty($urlParams["query"])) {
    parse_str($urlParams["query"], $aQuery);
    if (!empty($aQuery["q"])) {
        $title .= " - ".$aQuery["q"];
    }
}

$feeds = new FeedGenerator();
$feeds->setGenerator(new RSSGenerator);
$feeds->setTitle($title);
$feeds->setChannelLink(
    !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on"?"https":"http".
    "://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]
);
$feeds->setLink("http://www.leboncoin.fr");
$feeds->setDescription("Flux RSS de la recherche : ".htmlspecialchars($_GET["url"]));

if (count($ads)) {
    foreach ($ads AS $ad) {
        $item = new FeedItem(
            md5($ad->getId().$ad->getDate()),
            $ad->getTitle(),
            $ad->getLink(),
            require $dirname."/view.phtml"
        );
        $item->pubDate = date3339($ad->getDate());
        $feeds->addItem($item);
    }
}
$feeds->display();
