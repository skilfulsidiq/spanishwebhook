<?php

require('./vendor/autoload.php');

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Our web handlers
var_dump("hello");
$app->get('/', function() use($app) {
  
header('Content-Type: application/json');
$request = file_get_contents('php://input');
$req_dump = print_r( $request, true );
$fp = file_put_contents( 'request.log', $req_dump );

// Updated Answer
if($json = json_decode(file_get_contents("php://input"), true)){
   $data = $json;
}
// $data = json_decode(file_get_contents('php://input'));
// $answers = $data->form_response->answers;
// print_r($answers);
header('Content-Type: application/json; charset=utf-8');
$answers= $data->form_response->answers;
return json_encode($data);
  // $app['monolog']->addDebug('logging output.');
  // return $app['twig']->render('index.twig');
});

$app->run();
