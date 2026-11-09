<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Handle subprocess execution when invoked by the PDO extension bridge below
if (getenv('VISITOR_PDO_SUBPROCESS') === '1') {
    require __DIR__.'/../vendor/autoload.php';

    $payloadRaw = stream_get_contents(STDIN);
    $payload = json_decode((string) $payloadRaw, true);

    if (is_array($payload)) {
        $_SERVER = $payload['server'] ?? $_SERVER;
        $_GET = $payload['get'] ?? [];
        $_POST = $payload['post'] ?? [];
        $_COOKIE = $payload['cookie'] ?? [];
        $_FILES = $payload['files'] ?? [];
    }

    /** @var Application $app */
    $app = require_once __DIR__.'/../bootstrap/app.php';

    $rawBody = isset($payload['raw_body']) ? (string) base64_decode((string) $payload['raw_body'], true) : '';

    $request = Request::createFromBase(
        new \Symfony\Component\HttpFoundation\Request(
            $_GET,
            $_POST,
            [],
            $_COOKIE,
            $_FILES,
            $_SERVER,
            $rawBody
        )
    );

    /** @var Kernel $kernel */
    $kernel = $app->make(Kernel::class);
    $response = $kernel->handle($request);
    $kernel->terminate($request, $response);

    $cookies = [];
    foreach ($response->headers->getCookies() as $cookie) {
        $cookies[] = (string) $cookie;
    }

    $envelope = [
        'status' => $response->getStatusCode(),
        'headers' => $response->headers->allPreserveCaseWithoutCookies(),
        'cookies' => $cookies,
        'content' => base64_encode((string) $response->getContent()),
    ];

    fwrite(STDOUT, '__VISITOR_RESPONSE_START__'.json_encode($envelope).'__VISITOR_RESPONSE_END__');
    exit(0);
}

// If running under PHP built-in server without pdo_mysql loaded in php.ini, bridge to PHP with pdo_mysql enabled
if (! extension_loaded('pdo_mysql')) {
    $extDir = dirname(PHP_BINARY).DIRECTORY_SEPARATOR.'ext';
    if (is_dir($extDir) && file_exists($extDir.DIRECTORY_SEPARATOR.'php_pdo_mysql.dll')) {
        $cmd = [
            PHP_BINARY,
            '-d', 'extension_dir='.$extDir,
            '-d', 'extension=pdo_mysql',
            '-d', 'extension=mysqli',
            '-d', 'extension=pdo_sqlite',
            '-d', 'extension=sqlite3',
            __FILE__,
        ];

        $payload = json_encode([
            'server' => $_SERVER,
            'get' => $_GET,
            'post' => $_POST,
            'cookie' => $_COOKIE,
            'files' => $_FILES,
            'raw_body' => base64_encode((string) file_get_contents('php://input')),
        ]);

        $env = array_merge(getenv(), ['VISITOR_PDO_SUBPROCESS' => '1']);
        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $proc = proc_open($cmd, $descriptors, $pipes, dirname(__DIR__), $env);
        if (is_resource($proc)) {
            fwrite($pipes[0], (string) $payload);
            fclose($pipes[0]);

            $stdout = (string) stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($proc);

            if (preg_match('/__VISITOR_RESPONSE_START__(.*)__VISITOR_RESPONSE_END__/s', $stdout, $matches) === 1) {
                $envelope = json_decode($matches[1], true);
                if (is_array($envelope)) {
                    http_response_code((int) ($envelope['status'] ?? 200));

                    foreach (($envelope['headers'] ?? []) as $name => $values) {
                        foreach ((array) $values as $value) {
                            header($name.': '.$value, false);
                        }
                    }

                    foreach (($envelope['cookies'] ?? []) as $cookieHeader) {
                        header('Set-Cookie: '.$cookieHeader, false);
                    }

                    echo base64_decode((string) ($envelope['content'] ?? ''), true);
                    exit(0);
                }
            }
        }
    }
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
