<?php


$file = '/etc/apache2/sites-available/000-default.conf';
$content = file_get_contents($file);

$newContent = str_replace('/var/www/html', '/var/www/html/public', $content);

file_put_contents($file, $newContent);
