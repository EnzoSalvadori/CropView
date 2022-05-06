 <?php 

require 'vendor/autoload.php';

$client  = new MongoDB\Client("mongodb://localhost:27017");
$dataBase = $client->selectDatabase('cropView');
$collection = $dataBase->selectCollection("user");

?>