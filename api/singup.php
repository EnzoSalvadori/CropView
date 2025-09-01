<?php 


include ("conexao.php");
$mensagem = "";

if (!empty($_POST)) {
  $data['email'] =  $_POST['email'];
  $validaEmail = $collection->findOne(['email' => $data['email']]);
  if ($validaEmail == NULL) {
  	$data['senha'] =  $_POST['senha'];
	  $data['senha'] =  password_hash($data['senha'], PASSWORD_DEFAULT);
	  $result = $collection->insertOne($data);
    header("Location: /login.php");
  }else{
  	$mensagem = "Email já cadastrado ou invalido";
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
    include("singup.html");
  }
  elseif ($idioma == "es"){
    include("singup.html");
  }
  else{
    include("singup.html");
  }

}else{

	if (!empty($_COOKIE["idioma"])){
		if ($_COOKIE["idioma"] == "en"){
	    	include("singup.html");
	  	}
	  	elseif ($_COOKIE["idioma"] == "es"){
	    	include("singup.html");
	  	}
	  	else{
	    	include("singup.html");
	 	}
	}else{
		include("singup.html");
	} 
}

?>