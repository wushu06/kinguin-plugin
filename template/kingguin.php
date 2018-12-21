<?php


use Inc\Data\Api\Connect;

$con = new Connect();
$limit = 1;
$response = $con->hmuApiBasicConnection('GET', 'https://api2.kinguin.net/integration/v1/products?limit='.$limit);
/*
 '<pre>';
var_dump( json_decode($response)->results);
 '</pre>';*/

foreach (json_decode($response)->results as $product) {
$name = $product->name;
 $desc = $product->description;
 $image = $product->coverImage;
 $developers = $product->developers;
 foreach ($developers as $developer) {
      $developer;
 }
$publishers = $product->publishers;
foreach ($publishers as $publisher) {
     $publisher;
}
$genres = $product->genres;
foreach ($genres as $genre) {
     $genre;
}
 $platform = $product->platform;
 $releaseDate = $product->releaseDate;
 $is_instock = $product->stock;
 $qty = $product->qty;
 $price = $product->price;
 $isPreorder = $product->isPreorder;
 $regionalLimitations = $product->regionalLimitations;
 $regionId = $product->regionId;
 $activationDetails = $product->activationDetails;
 $kinguinId = $product->kinguinId;
$screenshots = $product->screenshots;
foreach ($screenshots as $screenshot) {
     $screenshot->url;
}
$videos = $product->videos;
foreach ($videos as $video) {
     $video->video_id;
}
$languages = $product->languages;
foreach ($languages as $language) {
 $language;
}
$systemRequirements = $product->systemRequirements;
foreach ($systemRequirements as $systemRequirement) {
     $system = $systemRequirement->system;
    $requirements = $systemRequirement->requirement;
    foreach ($requirements as $requirement){
         $requirement;
    }
}

}
?>


