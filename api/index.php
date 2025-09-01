<?php 

if (!empty($_GET['language'])){
	$idioma = $_GET['language'];
	setcookie(
  	"idioma",
  	$idioma,
  	time() + (10 * 365 * 24 * 60 * 60)
	);

  if ($idioma == "en"){
    include("index2.html");
  }
  elseif ($idioma == "es"){
    include("index3.html");
  }
  else{
    include("index.html");
  }

}else{

	if (!empty($_COOKIE["idioma"])){
		if ($_COOKIE["idioma"] == "en"){
	    	include("index2.html");
	  	}
	  	elseif ($_COOKIE["idioma"] == "es"){
	    	include("index3.html");
	  	}
	  	else{
	    	include("index.html");
	 	}
	}else{
		include("index.html");
	} 
}

?>