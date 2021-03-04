<?php
date_default_timezone_set('America/Argentina/Cordoba');
header('Access-Control-Allow-Origin: *');
header('HTTP/1.0 201 CREATED');

$token = 'APP_USR-608543104562541-021902-b17564179aba1584351fbd0b037de82b-509680563';

$_SERVER['QUERY_STRING']?: exit(header('Location: webhook.html'));
if($_SERVER['QUERY_STRING'] === 'limpiar'){var_dump(file_put_contents('webhook.html', '<pre>')); exit('<br />limpiado');}

if($_GET['topic'] === 'merchant_order'){ 
    $detalles = file_get_contents('https://api.mercadopago.com/merchant_orders/'.$_GET['id'].'?access_token='.$token);}


elseif($_GET['topic'] === 'payment'){
    $detalles = file_get_contents('https://api.mercadopago.com/v1/payments/'.$_GET['id'].'?access_token='.$token);}

$detalles = json_decode($detalles)?: 'raw: '.gettype($detalles).': '.$detalles;

var_dump(file_put_contents(
    'webhook.html',
    date('Y-m-d H:i:s')
        . PHP_EOL . '<details><summary>SERVER:</summary>'
        . var_export($_SERVER, true) . '</details>'
        . PHP_EOL . 'GET:&emsp;'
        . var_export($_GET, true)
        . PHP_EOL . 'BODY:&emsp;'
        . file_get_contents('php://input')
        . PHP_EOL . '<details><summary>FETCH API</summary>'
        . var_export($detalles, true) . '</details>'
        . PHP_EOL . '<details><summary>Request Headers</summary>'
        . var_export($http_response_header, true) . '</details>'
        . '<hr />',
    FILE_APPEND
));
