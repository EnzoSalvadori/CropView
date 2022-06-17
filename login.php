<?php 

require_once 'vendor/autoload.php';
include ("conexao.php");
use Google\Client as Google_Client;
session_start();
$mensagem = "";

$cookie = $_COOKIE['g_csrf_token'] ?? '';

//logando com o google API
if (!empty($_POST['credential']) && !empty($_POST['g_csrf_token'])) {
	if ($_POST['g_csrf_token'] != $cookie) {
		$mensagem = "Erro ao logar com o google";
		exit;
	}
	// Get $id_token via HTTPS POST.
	$client = new Google_Client(['client_id' => "757100643901-lfqngqkb55sdvrlkoimdj8p2h9akpc30.apps.googleusercontent.com"]);  // Specify the CLIENT_ID of the app that accesses the backend
	$payload = $client->verifyIdToken($_POST['credential']);
	if (isset($payload['email'])) {
	   $_SESSION['username'] =  $payload['name'];
	   $_SESSION['email'] =  $payload['email'];
       header("Location: /teste.php");
	} else {
	  $mensagem = "Erro ao logar com o google";
	  exit;
	}
}

//logando com o banco de dados
if (!empty($_POST['email'])) {

  $data['email'] =  $_POST['email'];
  $data['senha'] =  $_POST['senha'];

  $usuario = $collection->findOne(['email' => $data['email']]);
  if ($usuario == NULL) {
    $mensagem = "Email não cadastrado ou invalido";
  }else{
    if(password_verify($data['senha'],$usuario['senha'])){
       $_SESSION['username'] =  explode("@",$data['email'])[0];
       $_SESSION['email'] =  $data['email'];
       header("Location: /teste.php");
    }else{
       $mensagem = "Senha incorreta";
    }
  }
}

if (!empty($_GET['language'])){
	$idioma = $_GET['language'];
	setcookie(
  	"idioma",
  	$idioma,
  	time() + (10 * 365 * 24 * 60 * 60)
	);

  if ($idioma == "en"){
    include("login.html");
  }
  elseif ($idioma == "es"){
    include("login.html");
  }
  else{
    include("login.html");
  }

}else{

	if (!empty($_COOKIE["idioma"])){
		if ($_COOKIE["idioma"] == "en"){
	    	include("login.html");
	  	}
	  	elseif ($_COOKIE["idioma"] == "es"){
	    	include("login.html");
	  	}
	  	else{
	    	include("login.html");
	 	}
	}else{
		include("login.html");
	} 
}

?>