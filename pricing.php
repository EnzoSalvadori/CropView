<?php 

if (!empty($_GET['language'])){
	$idioma = $_GET['language'];
	setcookie(
  	"idioma",
  	$idioma,
  	time() + (10 * 365 * 24 * 60 * 60)
	);

	if ($idioma == "en"){
  	include("pricing2.html");
  }
  elseif ($idioma == "es"){
  	include("pricing3.html");
  }
  else{
    include("pricing.html");
  }

}else{

	if ($_COOKIE["idioma"] == "en"){
  	include("pricing2.html");
  }
  elseif ($_COOKIE["idioma"] == "es"){
  	include("pricing3.html");
  }
  else{
    include("pricing.html");
  }
}

?>