<?php

$addresses = explode("\n", file_get_contents('urls.txt'));
$size = count($addresses);
$randomIndex = rand(0, $size - 1);
$randomUrl = $addresses[$randomIndex];

header('Location: http://' . $randomUrl, true, 303);