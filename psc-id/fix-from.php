<?php
$path = __DIR__ . '/.env';
$env  = file_get_contents($path);
$env  = preg_replace('/^MAIL_FROM_ADDRESS=.*/m', 'MAIL_FROM_ADDRESS="pscemacc2023@gmail.com"', $env);
file_put_contents($path, $env);
echo "Updated.\n";
