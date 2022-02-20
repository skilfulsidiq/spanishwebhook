<?php

require('./vendor/autoload.php');
require './helpers/curlapi.php';

$app = new Silex\Application();
$app['debug'] = true;
// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/web/views',
));

// Our web handlers
$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('index.twig');
});
$app->post('/', function() use($app) {
 
// header('Content-Type: application/json');
// $request = file_get_contents('php://input');


// Updated Answer
if($request = json_decode(file_get_contents("php://input"), true)){
  $req_dump = print_r( $request, true );
$fp = file_put_contents( 'request.log', $req_dump );
   $data = $request;
}
$fields = $data['form_response']['definition']['fields'];
$answers= $data['form_response']['answers'];
$structured = [];
  $amount = $answers[1]["choice"]["label"];
  $lastname =  $answers[2]["text"];
  $phone =  $answers[3]["phone_number"];
  $email =  $answers[4]["email"];

  $form_params = [
    "LastName"=>$lastname,
    "Email"=>$email,
    "HomePhone"=> $phone,
    "TotalDebt"=>$amount
  ];

  
  
  try {
//

    $make_call = callAPI('POST', 'https://unitedsettlement.secure.force.com/portal/services/apexrest/postJSON', json_encode($form_params));
    $response = json_decode($make_call, true);
    // $errors   = $response['response']['errors'];
    // $data     = $response['response']['data'];

    return json_encode($response);

  } catch (\Exception $th) {
    //throw $th;

    // $err = $th->getResponse()->getBody(true)->getContents();
    // app['monolog']->addDebug($err);
    return json_dncode($th->getMessage());
  }

  
});

$app->run();
