<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header('Content-Type: application/json');
date_default_timezone_set('America/Manaus');

require_once __DIR__ . './jwt.php';

$json = $_POST;
if (empty($json)) $json = file_get_contents ( "php://input" );
$json = json_decode($json);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        post();
        break;
    case 'GET':
        get();
        break;
}

function post() {
    global $json;

    $response = array(
        "success"=>true,
        "data"=>"",
        "msg"=>"Logado com Sucesso!"
    );

    $dados = array(
        "nome"=> "fulano",
        "perfil"=> "admin",
        "cargo"=> "diretor"
    );

    if ($json->user === 'fulano@email.com' && $json->pass === '123') {
        $response["success"] = true;
        $response["jwt"] = generate($dados);
        $response["msg"] = "Logado com Sucesso!";
    }else{
        $response["success"] = false;
        $response["jwt"] = "";
        $response["msg"] = "Dados inválidos!";
    }

    echo json_encode($response);
}

function get () {
    global $json;

    $response = array(
        "success"=>true,
        "data"=>"",
        "msg"=>""
    );

    if (!validate()) {
        $response['success'] = false;
        $response['data'] = '';
        $response['msg'] = 'Não autorizado';
        die(json_encode($response));
    }

    $response['success'] = true;
    $response['data'] = $data;

    echo json_encode($response);
}

?>