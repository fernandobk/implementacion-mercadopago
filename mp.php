<?php
ini_set('display_errors', '1');

$data = file_get_contents('php://input');
$data = json_decode($data);
$deviceId = $data->dId;
$data = base64_decode($data->data);
$data = json_decode($data);

$miurl = 'https://fbk-testmp.herokuapp.com/';
$urlmp = 'https://api.mercadopago.com/checkout/preferences';
$access_token = 'APP_USR-608543104562541-021902-b17564179aba1584351fbd0b037de82b-509680563';
$integrator_id = 'abc123';

$item = new stdClass;
$item->id = '1234';
$item->title = $data->title;
$item->description = htmlentities('Dispositivo móvil de Tienda e-commerce');
$item->quantity = (int)$data->unit;
$item->currency_id = 'ARS';
$item->unit_price = (float)$data->price;
$item->picture_url = $miurl . $data->img;

$cliente = new stdClass;
$cliente->name = 'Lalo';
$cliente->surname = 'Landa';
$cliente->email = 'test_user_63274575@testuser.com';
$cliente->phone = new stdClass;
    $cliente->phone->area_code = '11';
    $cliente->phone->number = '22223333';
$cliente->address = new stdClass;
    $cliente->address->street_name = 'False';
    $cliente->address->street_number = 123;
    $cliente->address->zip_code = '1111';

$pref = new stdClass;
$pref->notification_url = $miurl . 'webhook.php?source_news=ipn';
$pref->auto_return = 'approved';
$pref->external_reference = time();
$pref->binary_mode = true;
$pref->items = array($item);
$pref->payer = $cliente;

$pref->back_urls = new stdClass;
    $pref->back_urls->success = $miurl . 'retorno.php';
    $pref->back_urls->pending = $miurl . 'retorno.php';
    $pref->back_urls->failure = $miurl . 'retorno.php';

$pref->payment_methods = new stdClass;
    $pref->payment_methods->excluded_payment_methods = array(array('id'=>'amex'));
    $pref->payment_methods->excluded_payment_types = array(array('id'=>'atm'));
    $pref->payment_methods->installments = 6;
    $pref->payment_methods->default_installments = 1;

$pref = json_encode($pref);
//exit('<pre>' . var_export($pref, true));

$fetch_headers = array();
$fetch_headers[] = 'content-type:application/json;charset:utf-8';
$fetch_headers[] = 'Authorization: Bearer ' . $access_token;
$fetch_headers[] = 'x-integrator-id: ' . $integrator_id;
$fetch_headers = implode(PHP_EOL, $fetch_headers);

$fetch = @file_get_contents(
    $urlmp, false, stream_context_create(
        array(
            'http' => array(
                'method' => 'POST',
                'header' => $fetch_headers,
                'content' => $pref
            )
        )
    )
);
if($fetch){$fetch = json_decode($fetch);}

if(explode(' ', $http_response_header[0])[1] == 201){
    if($fetch->init_point){
        header('HTTP/1.0 202 Accepted');
        exit($fetch->init_point);
    }else{
        header('HTTP/1.0 500 Internal server error');
        exit('Error obteniendo el punto de inicio<br /><pre>' . var_export($fetch, true));
    }
}else{
    header('HTTP/1.0 500 Internal server error');
    exit('Error enviando los datos de preferencia<br /><pre>' . var_export(array($http_response_header, $fetch_headers, $pref), true));
}
