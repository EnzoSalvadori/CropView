<?php 

include ("conexao.php");
session_start();

if (isset($_SESSION['username'])){
  # $resposta = $collection->find() retorna varias respostas
  $resposta = $collection->findOne(
    ['ID_email' => $_SESSION['email']] 
  );
  var_dump($resposta);
}else{
    header("Location: /login.php");
}

?>