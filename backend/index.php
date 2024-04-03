<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use DI\Bridge\Slim\Bridge;
use Middlewares\TrailingSlash;
use Slim\Exception\HttpNotFoundException;

use App\Controllers\TestController;
use App\Controllers\UserController;
use App\Controllers\PostController;

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

//$container = new \DI\Container();
$builder = new \DI\ContainerBuilder();
$builder->addDefinitions([
    PDO::class => function() {
        $options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];
        $dsn = "{$_ENV['DB_DRIVER']}:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_NAME']}";
        return new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $options);
    }
]);
$container = $builder->build();

$app = Bridge::create($container);
$app->addBodyParsingMiddleware();
$app->add(new TrailingSlash());
$errorMiddleware = $app->addErrorMiddleware($_ENV['ERROR_DETAILS'], true, true);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->forceContentType('application/json');

$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
            ->withHeader('Access-Control-Allow-Origin', 'http://localhost:3000')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Access-Control-Allow-Credentials', 'true');
});

$app->options('/{routes:.+}', function (Request $request, Response $response) {
    return $response;
});

$app->get('/api/phpinfo', function (Request $request, Response $response) {
    $response->getBody()->write(phpinfo());
    return $response;
});

$app->post('/api/users',  [UserController::class, 'register_user']);
$app->post('/api/login',  [UserController::class, 'login']);
$app->post('/api/logout', [UserController::class, 'logout']);

$app->get('/api/posts', [PostController::class, 'get_posts']);
$app->post('/api/posts', [PostController::class, 'add_post']);

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
    throw new HttpNotFoundException($request);
});

$app->run();