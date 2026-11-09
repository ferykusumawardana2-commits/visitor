<?php

namespace App\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand as BaseServeCommand;

use function Illuminate\Support\php_binary;

class ServeCommand extends BaseServeCommand
{
    /**
     * Get the full server command with PDO extensions enabled for Laragon PHP.
     *
     * @return list<string>
     */
    protected function serverCommand(): array
    {
        $server = file_exists(base_path('server.php'))
            ? base_path('server.php')
            : base_path('vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php');

        $php = php_binary();
        $extDir = dirname($php).DIRECTORY_SEPARATOR.'ext';

        $command = [$php];

        if (is_dir($extDir)) {
            $command[] = '-d';
            $command[] = 'extension_dir='.$extDir;

            foreach (['pdo_mysql', 'mysqli', 'pdo_sqlite', 'sqlite3'] as $ext) {
                $dll = $extDir.DIRECTORY_SEPARATOR.'php_'.$ext.'.dll';
                if (file_exists($dll)) {
                    $command[] = '-d';
                    $command[] = 'extension='.$ext;
                }
            }
        }

        $command[] = '-S';
        $command[] = $this->host().':'.$this->port();
        $command[] = $server;

        return $command;
    }
}
