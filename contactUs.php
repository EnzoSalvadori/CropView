<?php 
if (!empty($_POST))
{
    $name = $_POST['name'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $assunto =  $_POST['assunto'];
    $message = $_POST['message'];
    $soma = $_POST['soma'];
    $idioma = $_COOKIE["idioma"];

    if ($soma == 11)
    {
      $formcontent=" From: $name $lastName \n E-mail: $email \n Phone: $phone \n Message: $message";
      $recipient = "contato@cropview.com.br";
      $subject = "CropView - Formulário de $assunto $soma";
      $mailheader = "From: ... \r\n";
      mail($recipient, $subject, $formcontent, $mailheader) or die("Error sending your message, send it directly to contato@cropview.com.br!");
      header("Location: /");
      exit();
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
    include("contactUs2.html");
  }
  elseif ($idioma == "es"){
    include("contactUs3.html");
  }
  else{
    include("contactUs1.html");
  }

}else{
  if (!empty($_COOKIE["idioma"])){
    if ($_COOKIE["idioma"] == "en"){
      include("contactUs2.html");
    }
    elseif ($_COOKIE["idioma"] == "es"){
      include("contactUs3.html");
    }
    else{
      include("contactUs1.html");
    }
  }else{
    include("contactUs1.html");
  }
}

if (!empty($_POST))
{
 if ($soma != 11)
    {
       ?>
        <script> document.getElementById('alert').style.display = '';</script>
      <?php
    }
}
?>