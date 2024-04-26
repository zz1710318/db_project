<?php
    $host='localhost';
    $dbuser='root';
    $dbpassword='root';
    $dbname='DEshop';
  
    $link = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpassword);

?>