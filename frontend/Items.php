<html>
        <head>
                <title>Items</title>
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

function contains($word, $string)
{
    return strpos($string, $word) !== false;
}

$File = fopen("/home/operational/rob.keys", 'r');

$API_KEY= trim(fgets($File));


$url ="https://global.api.pvp.net/api/lol/static-data/na/v1.2/item?itemListData=image&api_key=".$API_KEY;
$json = file_get_contents($url);
$jsonItems = json_decode($json);
$ID =3742;
class Item {
	
	public $name;
	public $wins;
	Public $games;
	Public $image;
	/*function Item ($item)
	{
	$this->name =$item->name;
	$this->image = $item->image->full;
	$this->wins =0;
	$this->games =0;
	}*/
/*	function Item()
	{
	$this->name ="";
        $this->wins =0;
        $this->games =0;
	}*/

}
$ID =67;

$items = array (
        3742 => new Item(),
        3430 => new Item(),
        3911 => new Item(),
        3744 => new Item(),
        3924 => new Item(),
        3829 => new Item(),
        3150 => new Item(),
        3652 => new Item(),
        3431 => new Item(),
        3434 => new Item(),
        3840 => new Item(),
        3745 => new Item(),
        );
//$items = array();
foreach($items as $key => $value)
{
$items[$key]->name =$jsonItems->data->$key->name; 
$items[$key]->image = $jsonItems->data->$key->image->full;
}
/*foreach($jsonItems->data as $item)
{
$items[$item->id] = new Item($item);


}*/
$query ="SELECT * FROM Items";
$result = $conn1->query($query);

 while($row = $result->fetch_object())
    {
        foreach($items as $key => $value)
            if(contains("".$key,$row->Combination))
             {
               $items[$key]->games += $row->Games;
               $items[$key]->wins += $row->Wins;
             }
    }

$query ="SELECT SUM(Games) as Games FROM Items";
$result = $conn1->query($query);
$totalGames = $result->fetch_object();

//echo "<div class=\"img \" style=\"height: 64px; width: 64px; background: url('//ddragon.leagueoflegends.com/cdn/5.16.1/img/item/3742.png');\"></div>";
echo "<center><table cellspacing= '25'  ><tr><th><center>Name</th><th><center>Pick Rate</th><th><center>Win Rate";
foreach($items as $key => $value)
{
        echo "<tr><td><center>" ."<div class=\"img \" style=\"height: 64px; width: 64px; background: url('//ddragon.leagueoflegends.com/cdn/5.16.1/img/item/".$value->image."');\"></div>"
	.$value->name."</center></td><td><center>" .number_format($value->games*100/$totalGames->Games, 2, '.', '') ."%</td><td><center>"
	     .number_format($value->wins*100/$value->games,2, '.', '')."%</center></td></tr>";



}

?>
