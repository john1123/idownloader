<?php

// === composer.json ===
//{
//    "require": {
//    "php":">=5.3.0",
//    "john1123/imagedownloader": "*"
//  },
//  "minimum-stability": "dev",
//  "repositories":[
//    {
//        "type":"git",
//      "url":"https://github.com/john1123/idownloader"
//    }
//  ]
//}

// PS don`t forget to create ./files folder :)

require_once 'vendor/autoload.php';

$iDownloader = new \John1123\ImageDownloader\ImageDownloader('./files/');
$iDownloader->download(array(
    'http://www.o-prirode.com/_ph/51/1/622982453.jpg',
    'http://www.o-prirode.com/_ph/51/1/776271972.jpg',
    'http://www.o-prirode.com/_ph/51/1/599704398.jpg',
    'http://www.o-prirode.com/_ph/51/1/327038064.jpg',
    'http://www.o-prirode.com/_ph/51/1/483788201.jpg',
    'http://www.o-prirode.com/_ph/51/1/706934352.jpg',
));
