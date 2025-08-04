<?php

$entrypointPath = '/usr/local/bin/docker-php-entrypoint';

$lines = explode(PHP_EOL, file_get_contents($entrypointPath));

array_splice($lines, 1, 0, 'php /src/init.php');

file_put_contents($entrypointPath, implode(PHP_EOL, $lines));
