<?

//With default settings
$app = new Slim();

//With custom settings
$app = new Slim(array(
    'log.enable' => true,
    'log.path' => './logs',
    'log.level' => 4
));

//GET route
$app->get('/index', function ($name) {
    echo "Hello you've reached SlimJim!";
});

//POST route
$app->post('/deploy/:payload', function () {
    echo $payload
    //$app->response()->status(400);
});

$app->run();
