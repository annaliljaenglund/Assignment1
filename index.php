<?php
require("vendor/autoload.php");

use GuzzleHttp\Client;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$client = new Client();
$url = 'http://unicorns.idioti.se/';

$id;

if (!empty($_GET['id'])){
  $id = $_GET['id'];
}
else {
  $id = null;
}

$res = $client->request('GET', $url . $id, [
  'headers' => [
    'Accept' => 'application/json',
    'Content-type' => 'application/json'
    ]]);

    $result = $res->getBody();
    $data = json_decode($result, true);

    $log = new Logger('Assignment_1');
    $log->pushHandler(new StreamHandler('visits.log', Logger::INFO));
    ?>

    <!doctype html>
    <style>
    * {
      font-family: Georgia;
    }
    </style>
    <html>
    <head>
      <title>Enhörningar</title>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
      <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    </head>
    <body>
      <div class="container">
        <h1>Enhörningar</h1>
        <form action="" method="get">
          <div>
            <label for="id">Id på enhörning </label>
            <input type="number" id="id" name="id" class="form-control" required>
            <input type="submit" value="Visa enhörning" class="btn btn-success">
          </div>
          <?php
          if (isset($_GET["id"])){
            $image = $data['image'];
            echo "<h1>". $data['name'] . "</h1>";
            echo $data['description'] ."<br/><br/>". "Rapporterad av: " . $data['reportedBy']."<br/><br/>" .
            '<img src="'.$image.'" alt="random image" />'."<br/><br/>";
            $log->info("Informtion om " . $data['name'] . " söktes");
          }
          ?>
        </form>
        <form action="" method="get">
          <div>
            <input type="submit" value="Visa alla enhörningar" class="btn btn-primary">
          </div>
          <?php if (!isset($_GET["id"])){?>
            <h2>Alla Enhörningar</h2>
            <?php
            foreach ($data as $unicorn){
              if($unicorn['id'] <=12){
                $string = 'id';
                echo $unicorn[$string] . ": " . $unicorn['name'];
                echo "<a class='btn btn-default' href='?id=$unicorn[$string]' style='float: right;'>Läs mer</a>" . "</br></br>";
              }
            }
            $log->info("Information om alla enhörningar söktes");
          }
          ?>
        </form>
      </div>
    </body>
    </html>
