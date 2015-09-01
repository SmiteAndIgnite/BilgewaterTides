<html>
        <head>
                <title>Champions</title>
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
</div>
<br>
<br>
<br>
<br>
<!--<center><div align ="center">
<center><section align ="center">-->
  <!--<center><div class="content">
                                <center><div class="container">
-->				

 
		
                                              <!--          <div class="row">

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



$query ="SELECT * FROM Champions ORDER BY Games DESC";
$result = $conn1->query($query);
$query2 ="SELECT SUM(Games) as Games FROM Champions";
$result2 = $conn1->query($query2);
$totalGames = $result2->fetch_object();
$totalGames->Games /=10;

$result3 = $conn2->query($query);
//echo "<div class=\"img \" style=\"height: 128px; width: 128px; background: url('//ddragon.leagueoflegends.com/cdn/5.2.1/img/champion/Aatrox.png');\"></div>";
echo "<center><table cellspacing= '25' align=\"center\" style=\"margin: 0px auto;\" ><tr><th><center>Champion</th><th><center>BmB PickRate</th><th><center>Normal Pick Rate</th>
        <th><center>BmB WinRate</th><th><center>Normal WinRate</th>";
while($row = $result->fetch_object())
{
$ID =$row->ChampID;
$query ="SELECT * FROM Champions WHERE ChampID = $ID";
$result3 = $conn2->query($query);
$row2 =$result3->fetch_object();
$ID2 = $row2->ChampID;
//<div class="img " style="height: 48px; width: 48px; background: url('//ddragon.leagueoflegends.com/cdn/5.16.1/img/sprite/champion2.png')
// -144px -96px ;"></div>
$image = $jsonItems->data->$ID->image;
        echo "<tr><td><center>"."<div class=\"img \" style=\"height: 48px; width: 48px; background: url('//ddragon.leagueoflegends.com/cdn/5.16.1/img/sprite/"
        .$image->sprite ."') -" .$image->x ."px -" .$image->y ."px ;\"></div>"."<center>"  .$jsonItems->data->$ID->name."</td><td><center> ". number_format($row->Games*100/$totalGames->Games, 2, '.', '')
        ."%</center></td><td><center>" .number_format($row2->Games*100/$totalGames->Games, 2, '.', '') ."%</td><td><center>". number_format($row->Wins*100/$row->Games,2, '.', '')
        ."%</td><td><center>". number_format($row2->Wins*100/$row2->Games,2, '.', '')."%</center></td></tr>";



}

?>
                 
</section>
</body>
</html>
