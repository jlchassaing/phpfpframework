<?php 
/**
 * This is a php functional programming experimental framework
 *
 * @author Jean-Luc Chassaing <jlchassaing@gmail.com>
 *
 */

/*
 * load template
 */
$model = file_get_contents('model.html.php');

/**
 * display renders the templates with the given params values
 * @param array $params
 * @return bool|string
 */
$display = function ($params = []) use ($model)
{
  $result = $model;
  array_walk($params, function($value,$key) use (& $result){
    $result = str_replace("{{ $key }}", $value, $result);
  });
  return $result;
};

/**
 *
 * @param $request
 * @return bool|string
 */
$defaultRootAction = function ($request) use ( $display )
{
  $params = [
    'content' => "The request : " .$request['uri'],
    'title' => "Test FP",
  ];
  return $display($params);
};

/**
 * router array each path must match an action
 */
$router = [
  '/' => $defaultRootAction,
];


$request = [
  'uri' => $_SERVER['REQUEST_URI'],
  'post' => $_POST,
  'get' => $_GET,
  'session' => $_SESSION,
];

$kernel = [
  'request' => $request,
  'router' => $router,
];

/**
 * @param $kernel
 * @return mixed
 */
function run($kernel)
{
  
  $uri = $kernel['request']['uri'];
  return $kernel['router'][$uri]($kernel['request']);
}

/**
 * display final result
 */
print(run($kernel));