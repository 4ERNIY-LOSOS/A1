<?php

declare(strict_types=1);

function logMessage($message): void
{
    echo $message . PHP_EOL;
    $message = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
    file_put_contents('/log/install.log', $message, FILE_APPEND);
}

logMessage('Starting script execution...');

if (!file_exists('/hleb/composer.json')) {
    logMessage("Creating a new project in /hleb using Composer...");

    logMessage(shell_exec(('composer create-project phphleb/hleb /hleb 2>&1')));

    copy('/src/database.php', '/hleb/config/database-local.php');
    unlink('/src/database.php');

    rename('/src/.php-cs-fixer.php', '/hleb/.php-cs-fixer.php');
    exec('/root/.composer/vendor/bin/php-cs-fixer fix /hleb');
} else {
    logMessage("The project is already installed. Skip creating the HLEB2 project.");
}
exec('sh /root/www-data.sh');
logMessage('Setup completed.' . PHP_EOL);
