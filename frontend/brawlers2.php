<html>
        <head>
                <title>Brawlers</title>
                <meta charset="utf-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1" />
                <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
                <link rel="stylesheet" href="assets/css/main.css" />
                <!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
                <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
        </head>
        <body class="landing">
                <div id="page-wrapper">

                        <!-- Header -->
                                <header id="header">
                                        <nav id="nav">
                                                <ul>

                                        <li><a href="index.php" class = "button special">Home</a></li>
                                        <li><a href="brawlers2.php" class = "button special">Brawlers</a></li>
                                        <li><a href="champions.php" class = "button special">Champions</a></li>
                                        <li><a href="Items.php" class = "button special">Items</a></li>
                                         </ul>
                                  </nav>
                                </header>
<br>
<br>
<br>
<br>
<!--<center><div align ="center">
<center><section align ="center">
  <center><div class="content">
                                <center><div class="container">
-->


                <!--
                                                        <div class="row">

                                                      <div class="4u 12u$(medium)">
                                                                        <header>
                                                                      <p></p>

                                                                        </header>
                                                                </div>-->


<?php
$Username = "obfuscated";
$Password = "obfuscated";
$host = "localhost";
$dbname1= "APICHALLENGE";
$dbname2 ="NORMAL";
//$connect = mysql_connect($host, $Username, $Password);
$conn1 = new mysqli($host, $Username, $Password, $dbname1);
if ($conn1->connect_error) {
    die("Connection failed: " . mysqli_connect_error());
}

$conn2 = new mysqli($host, $Username, $Password, $dbname2);

if ($conn2->connect_error) {
    die("Connection failed: " . mysqli_connect_error());
}


$File = fopen("/home/operational/rob.keys", 'r');

$API_KEY= trim(fgets($File));

$url ="https://global.api.pvp.net/api/lol/static-data/na/v1.2/champion?dataById=true&champData=image&api_key=".$API_KEY;
$json = file_get_contents($url);
$jsonItems = json_decode($json);



$query ="SELECT * FROM Brawlers ORDER BY Games DESC";
$result = $conn1->query($query);

$query2 ="SELECT SUM(Games) as Games FROM Brawlers";
$result2 = $conn1->query($query2);
$totalGames = $result2->fetch_object();

$result3 = $conn2->query($query);
//echo "<div class=\"img \" style=\"height: 128px; width: 128px; background: url('//ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/Aatrox.png');\"></div>";
echo "<center><table cellspacing= '25'  ><tr><th width =><center>Brawler Comp </th><th><center>Pick Rate</th><th><center>Win Rate";
while($row = $result->fetch_object())
{
echo "<tr><th><center>";
for($i=0; $i < $row->Razorfin; $i++)
echo"<img src=\"//ddragon.leagueoflegends.com/cdn/5.16.1/img/item/3611.png\" alt=\"Razorfin\" style=\"width:64px;height:64px;\">";

for($i=0; $i < $row->Ironback; $i++)
echo"<img src=\"//ddragon.leagueoflegends.com/cdn/5.16.1/img/item/3612.png\" alt=\"Ironback\" style=\"width:64px;height:64px;\">";

for($i=0; $i < $row->Plundercrab; $i++)
echo"<img src=\"//ddragon.leagueoflegends.com/cdn/5.16.1/img/item/3613.png\" alt=\"Plundercrab\" style=\"width:64px;height:64px;\">";

for($i=0; $i < $row->Ocklepod; $i++)
echo"<img src=\"//ddragon.leagueoflegends.com/cdn/5.16.1/img/item/3614.png\" alt=\"Ocklepod\" style=\"width:64px;height:64px;\">";


        echo "</center></td><td><center>" .number_format($row->Games*100/$totalGames->Games, 2, '.', '') ."%</td><td><center>". number_format($row->Wins*100/$row->Games,2, '.', '')
        ."%</center></td></tr>";



}


?>
