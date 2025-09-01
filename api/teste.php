<?php 

include ("conexao.php");
session_start();

if (isset($_SESSION['username'])){
  if (isset($_FILES["imagem"])) {
    // Establish MongoDB Connection
   
    $data['createdOn'] = new MongoDB\BSON\UTCDateTime;
    $data['filePath'] = $_FILES['imagem']['name'];
    $data['ID_email'] = $_SESSION['email'];

    move_uploaded_file($_FILES['imagem']['tmp_name'], 'images/'.$_FILES['imagem']['name']);

    $result = $collection->insertOne($data);
  }

  if (!empty($_GET['language'])){
  	$idioma = $_GET['language'];
  	setcookie(
    	"idioma",
    	$idioma,
    	time() + (10 * 365 * 24 * 60 * 60)
  	);

    if ($idioma == "en"){
      include("teste.html");
    }
    elseif ($idioma == "es"){
      include("teste.html");
    }
    else{
      include("teste.html");
    }

  }else{

  	if (!empty($_COOKIE["idioma"])){
  		if ($_COOKIE["idioma"] == "en"){
  	    	include("teste.html");
  	  	}
  	  	elseif ($_COOKIE["idioma"] == "es"){
  	    	include("teste.html");
  	  	}
  	  	else{
  	    	include("teste.html");
  	 	}
  	}else{
  		include("teste.html");
  	} 
  }
}else{
  header("Location: /login.php");
}

?>