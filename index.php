<?php

require "vendor/autoload.php";

$path = './sprites';


$atlas = new \CssAtlas\SpriteAtlas\SpriteAtlas();

$atlas->loadFromDir($path);
//dd($atlas);
$atlas->generateImageAndCss('./dist');
