<!DOCTYPE HTML>
<!--
	Landed by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<?php
class myTime
{
    public $minutes;
    public $seconds;
}
function contains($word, $string)
{
    return strpos($string, $word) !== false;
}
function getGameLength($connect)
{

$query ="SELECT AVG(endTime) FROM `BaronData`";
 $result = $connect->query($query);
/*
if ($result)
{
        echo "<FONT COLOR =#00ff00>Baron Data was successfully updated</FONT>";
}
else{
        echo "<FONT COLOR =#ff0000>Baron Data could not be updated</FONT>";

}*/

 $row = $result->fetch_assoc();
 $time = new myTime();
 $time->minutes =intval($row["AVG(endTime)"]/(60*1000));
 $time->seconds = ($row["AVG(endTime)"]/1000)%(60);
 return $time;
}
function getPopularBrawler($connect)
{

$query ="SELECT Razorfin, Ironback, Plundercrab, Ocklepod, Games FROM `Brawlers`";
 $result = $connect->query($query);


 $razorfin =0;
 $ironback =0;
 $plundercrab =0;
 $ocklepod =0;
 while($row = $result->fetch_assoc()) {
        $razorfin += $row["Razorfin"]*$row["Games"];
        $ironback += $row["Ironback"]*$row["Games"];
        $plundercrab += $row["Plundercrab"]*$row["Games"];
        $ocklepod += $row["Ocklepod"]*$row["Games"];
    }
$total = $razorfin + $ironback + $plundercrab + $ocklepod;

//echo "\n \t" .$razorfin ."\t" .$ironback ."\t" .$plundercrab ."\t" .$ocklepod ."\t" .$total;

$max =$razorfin;
$name ="Razorfin";

if($max < $ironback)
{
 $max = $ironback;
 $name ="Ironback";
}
if($max < $plundercrab)
{
 $max = $plundercrab;
 $name ="Plundercrab";
}
if($max < $ocklepod)
{
 $max = $ocklepod;
 $name = "Ocklepod";
}
$pickRate = $max/$total;
$pickRate = number_format($pickRate*100, 2, '.', '');
return array($name,$pickRate);
}
function getBestComp($connect)
{

$query ="SELECT * FROM `Brawlers` WHERE (Wins/Games) = (SELECT MAX(Wins/Games) FROM `Brawlers` WHERE Games > 200)";
$result = $connect->query($query);
$query2 ="SELECT SUM(Games) as Games FROM `Brawlers`";
$result2 = $connect->query($query2);
$row = $result->fetch_object();
$row2 = $result2->fetch_object();
$winRate = $row->Wins/$row->Games;
$winRate = number_format($winRate*100, 2, '.', '');
$pickRate = $row->Games/$row2->Games;
$pickRate = number_format($pickRate*100, 2, '.', '');
$razorfin = $row->Razorfin;
$ironback = $row->Ironback;
$plundercrab = $row->Plundercrab;
$ocklepod = $row->Ocklepod;
return array($razorfin, $ironback, $plundercrab, $ocklepod, $winRate, $pickRate);
}

function getPopularComp($connect)
{

$query ="SELECT * FROM `Brawlers` WHERE Games = (SELECT MAX(Games) FROM `Brawlers`)";
$result = $connect->query($query);
$query2 ="SELECT SUM(Games) as Games FROM `Brawlers`";
$result2 = $connect->query($query2);
$row = $result->fetch_object();
$row2 = $result2->fetch_object();
$winRate = $row->Wins/$row->Games;
$winRate = number_format($winRate*100, 2, '.', '');
$pickRate = $row->Games/$row2->Games;
$pickRate = number_format($pickRate*100, 2, '.', '');
$razorfin = $row->Razorfin;
$ironback = $row->Ironback;
$plundercrab = $row->Plundercrab;
$ocklepod = $row->Ocklepod;
return array($razorfin, $ironback, $plundercrab, $ocklepod, $winRate, $pickRate);
}
function getPopularChamp($connect)
{

$query ="SELECT * FROM Champions WHERE Games = (SELECT MAX(Games) FROM Champions)";
$result = $connect->query($query);
$query2 ="SELECT SUM(Games) as Games FROM Champions";
$result2 = $connect->query($query2);
$row = $result->fetch_object();
$row2 = $result2->fetch_object();
$winRate = $row->Wins/$row->Games;
$winRate = number_format($winRate*100, 2, '.', '');
$pickRate = $row->Games/($row2->Games/10);
$pickRate = number_format($pickRate*100, 2, '.', '');
return array($row->ChampID,$winRate, $pickRate);
}


function getBestChamp($connect)
{
$query ="SELECT * FROM Champions WHERE (Wins/Games) = (SELECT MAX(Wins/Games) FROM Champions WHERE Games > 400)";
$result = $connect->query($query);
$query2 ="SELECT SUM(Games) as Games FROM Champions";
$result2 = $connect->query($query2);
$row = $result->fetch_object();
$row2 = $result2->fetch_object();
$winRate = $row->Wins/$row->Games;
$winRate = number_format($winRate*100, 2, '.', '');
$pickRate = $row->Games/($row2->Games/10);
$pickRate = number_format($pickRate*100, 2, '.', '');
return array($row->ChampID,$winRate, $pickRate);
}

function getPopularNewItem($connect)
{

$items = array (
        3742 => 0,
        3430 => 0,
        3911 => 0,
        3744 => 0,
        3924 => 0,
        3829 => 0,
        3150 => 0,
        3652 => 0,
        3431 => 0,
        3434 => 0,
        3840 => 0,
        3745 => 0,
        );
$wins= $items;
$winRate =$items;
$query ="SELECT * FROM Items";
$result = $connect->query($query);
$players =0;

 while($row = $result->fetch_object())
    {
        foreach($items as $key => $value)
            if(contains("".$key,$row->Combination))
             {
               $items[$key] += $row->Games;
               $wins[$key] += $row->Wins;
             }
    }
$query ="SELECT SUM(Games) as Games FROM Items";
$result = $connect->query($query);
$totalGames = $result->fetch_object();
$players = $totalGames->Games;
$popular =0;
$max =0;
foreach($items as $key => $value)
{ $winRate[$key] = $wins[$key]/$value;
        if($max < $value)
        {
          $max = $value;
          $popular =$key;
        }
}
$winID=0;
$maxRate =0;
$popularWinRate=0;
foreach($winRate as $key =>$value)
{
 if($key == $popular)
 $popularWinRate =$value;
 if($maxRate < $value)
        {
          $maxRate = $value;
          $winID =$key;
        }
}
$popularWinRate = number_format($popularWinRate*100, 2, '.', '');
$popularPickRate=number_format($max/$players*100, 2, '.', '');
$bestWinRate = number_format($maxRate*100, 2, '.', '');
$bestPickRate=number_format($items[$winID]*100/$players,2, '.', '');
return array($popular,$popularPickRate,$popularWinRate,$winID,$bestPickRate,$bestWinRate);
}


function printBrawlers($razorfin, $ironback, $plundercrab, $ocklepod)
{


for($i=0; $i < $razorfin; $i++)
echo"<img src=\"//ddragon.leagueoflegends.com/cdn/5.16.1/img/item/3611.png\" alt=\"Razorfin\" style=\"width:32px;height:32px;\">";

for($i=0; $i < $ironback; $i++)
echo"<img src=\"//ddragon.leagueoflegends.com/cdn/5.16.1/img/item/3612.png\" alt=\"Ironback\" style=\"width:32px;height:32px;\">";

for($i=0; $i < $plundercrab; $i++)
echo"<img src=\"//ddragon.leagueoflegends.com/cdn/5.16.1/img/item/3613.png\" alt=\"Plundercrab\" style=\"width:32px;height:32px;\">";

for($i=0; $i < $ocklepod; $i++)
echo"<img src=\"//ddragon.leagueoflegends.com/cdn/5.16.1/img/item/3614.png\" alt=\"Ocklepod\" style=\"width:32px;height:32px;\">";


}
function getBaronToEndTime($connect)
{

$query ="SELECT AVG(endTime - lastBaronTime) as time FROM BaronData WHERE BaronData.lastBaronTeam/100 = BaronData.winningTeam";
$result = $connect->query($query);


 $row = $result->fetch_assoc();
 $time = new myTime();
 $time->minutes =intval($row["time"]/(60*1000));
 $time->seconds = ($row["time"]/1000)%(60);
 return $time;

}


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
$jsonChampions = json_decode($json);
$url2 ="https://global.api.pvp.net/api/lol/static-data/na/v1.2/item?itemListData=image&api_key=".$API_KEY;
$json2 = file_get_contents($url2);
$jsonItems = json_decode($json2);



?>

<html>
	<head>
		<title>Winion Waves - Bilgewater</title>
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

							<!--<li>
									<a href="#">Layouts</a>
								<ul>
									<li><a href="left-sidebar.html">Left Sidebar</a></li>
									<li><a href="right-sidebar.html">Right Sidebar</a></li>
									<li><a href="no-sidebar.html">No Sidebar</a></li>
									<li>
										<a href="#">Submenu</a>
										<ul>
											<li><a href="#">Option 1</a></li>
											<li><a href="#">Option 2</a></li>
											<li><a href="#">Option 3</a></li>
											<li><a href="#">Option 4</a></li>
										</ul>
									</li>
								</ul>
							</li>
							<li><a href="elements.html">Elements</a></li>
							<li><a href="#" class="button special">Sign Up</a></li>-->
						</ul>
					</nav>
				</header>

			<!-- Banner -->
				<section id="banner">
					<div class="content">
						<header>
							<h2>Bilgewater Tides</h2>
							<p>Analyzing the impact of <br/>Black Market Brawlers</p>
						</header>
						<span class="image">
						
						<img src="images/test.jpg" alt/></span>
					</div>
					<a href="#one" class="goto-next scrolly">Next</a>
				</section>

			<!-- One -->
				<section id="one" class="spotlight style1 bottom">
					<span class="image fit main"><img src="images/leagueBrawlers.jpg" alt="" /></span>
					<div class="content">
						<div class="container">
							<div class="row">
								<div class="4u 12u$(medium)">
									<header>
										<h2>No lane is safe</h2>
										<p>A compilation of 20,000 NA Games <br> 10,000 Normal <br> 10,000 Black Market</p>
									</header>
								</div>
								<div class="4u 12u$(medium)">
									<p>
									Our goal with this submission to the Riot Games API Competition
									is to look at the fundamental changes to the game, or in some cases
									lack thereof, caused by the introduction of the new Black Market Brawlers
									game type during the Bilgewater event.
									</p>
								</div>
								<div class="4u$ 12u$(medium)">
									<p>
									Black Market Brawlers introduced a new set of items for various play styles,
									as well five new types of minions which were used at the discretion of individual
									players. Our analysis looks at the results of these elements in changing game
									times, team win rates, and the statistics of individual champions, with special
									focus on pushing power under the waves of new minions.
									</p>
								</div>
							</div>
						</div>
					</div>
					<a href="#two" class="goto-next scrolly">Next</a>
				</section>

			<!-- Two -->
				<section id="two" class="spotlight style2 right">
					<span class="image fit main"><img src="images/nashorHighRes.png" alt="" /></span>
					<div class="content">
						<header>
							<h1>Buffed Brawlers - Anticlimactic?</h1>
							<p>We found that the introduction of brawlers did not have a significant impact on game length.
							Teams that pulled off a win as an immediate result of tackling Baron Nashor weren't noticeably faster
							with the new minions. <!--The Brawlers are strong, but even with a buff they may not push you to a hasty win.--> </p>

							 <?php
                                                         echo "<center><h1>Average Game Length </h1>";
                                                          $brawlerGameLength = getGameLength($conn1);
							  $normalGameLength=getGameLength($conn2);
 
								echo" <section>
                                                                <div class=\"table-wrapper\">
                                                                        <table>
                                                                                <thead>
                                                                                        <tr>
                                                                                                <th><center>BmB</th>
                                                                                                <th><center>Normal</th>
                                                                                        </tr>
                                                                                </thead>
                                                                                <tbody>";
                                    echo"<tr><td><center>" .$brawlerGameLength->minutes ." m " .$brawlerGameLength->seconds." s";

                                                echo "</center></td><td><center>" .$normalGameLength->minutes ." m " .$normalGameLength->seconds ." s </td></tr>";
                                                echo"  </tbody>
                                                                        </table>
                                                                </div>";

                                                         echo "<center><h1>Average Baron to Win Time </h1>";
                                                          $brawlerBaron = getBaronToEndTime($conn1);
							  $normalBaron = getBaronToEndTime($conn2);


                                                                echo" <section>
                                                                <div class=\"table-wrapper\">
                                                                        <table>
                                                                                <thead>
                                                                                        <tr>
                                                                                                <th><center>BmB</th>
                                                                                                <th><center>Normal</th>
                                                                                        </tr>
                                                                                </thead>
                                                                                <tbody>";
                                    echo"<tr><td><center>" .$brawlerBaron->minutes ." m " .$brawlerBaron->seconds." s";

                                                echo "</center></td><td><center>" .$normalBaron->minutes ." m " .$normalBaron->seconds ." s </td></tr>";
                                                echo"  </tbody>
                                                                        </table>
                                                                </div>";

                                                        ?>
	

							
						</header>
					</div>
					<a href="#three" class="goto-next scrolly">Next</a>
				</section>

			<!-- Three -->
				<section id="three" class="spotlight style3 left">
					<span class="image fit main bottom"><img src="images/brawlers.jpg" alt="" /></span>
					<div class="content">
						<header>
							 <?php
                               				 echo "<h1><center>Most Successful Brawler Comp</h1>";
	echo" <section>
                                                                <div class=\"table-wrapper\">
                                                                        <table>
                                                                                <thead>
                                                                                        <tr>
                                                                                                <th><center>Brawler Comp</th>
                                                                                                <th><center>Pick Rate</th>
                                                                                                <th><center>Win Rate</th>
                                                                                        </tr>
                                                                                </thead>
                                                                                <tbody>";
                               				 list($razorfin,$ironback, $plundercrab, $ocklepod,$winRate, $pickRate) = getBestComp($conn1);
 						echo "<tr><th><center>";
						 printBrawlers($razorfin, $ironback, $plundercrab,$ocklepod); 

        echo "</center></td><td><center>" .$pickRate ."%</td><td><center>". $winRate
        ."%</center></td></tr>";
echo"  </tbody>
                                                                        </table>
                                                                </div>
";

                                    			 echo "<center><h1> Most Popular Brawler Comp</h1>";
                                			 list($razorfin,$ironback, $plundercrab, $ocklepod,$winRate, $pickRate) = getPopularComp($conn1);
							   echo" <section>
                                                                <div class=\"table-wrapper\">
                                                                        <table>
                                                                                <thead>
                                                                                        <tr>
                                                                                                <th><center>Brawler Comp</th>
                                                                                                <th><center>Pick Rate</th>
                                                                                                <th><center>Win Rate</th>
                                                                                        </tr>
                                                                                </thead>
                                                                                <tbody>";
                                                echo "<tr><th><center>";
						printBrawlers($razorfin,$ironback, $plundercrab, $ocklepod);
        echo "</center></td><td><center>" .$pickRate ."%</td><td><center>". $winRate
        ."%</center></td></tr>";
echo"  </tbody>
                                                                        </table>
                                                                </div>
";

list($name, $pickRate) = getPopularBrawler($conn1);
$razorfin =0;
$ironback =0;
$plundercrab =0;
$ocklepod =0;
if($name =="Razorfin")
$razorfin =1;
if($name =="Ironback")
$ironback =1;
if($name =="Plundercrab")
$plundercrab =1;
if($name =="Ocklepod")
$ocklepod =1;
 echo "<center><h1> Most Popular Brawler</h1>";
                                                           echo" <section>
                                                                <div class=\"table-wrapper\">
                                                                        <table>
                                                                                <thead>
                                                                                        <tr>
                                                                                                <th><center>Brawler</th>
                                                                                                <th><center>Pick Rate</th>
                                                                                        </tr>
                                                                                </thead>
                                                                                <tbody>";
                                                echo "<tr><th><center>";
                                                printBrawlers($razorfin,$ironback, $plundercrab, $ocklepod);
						echo"<div><center>" .$name ."</div>";
        echo "</center></td><td><center>" .$pickRate."%</center></td></tr>";
echo"  </tbody>
                                                                        </table>
                                                                </div>
";

                             				 ?>	

						<ul class="actions">
							<li><a href="http://winionwaves.a-a-ron.me/challenge/brawlers2.php" class="button">Data</a></li>
						</ul>
					</div>
					<a href="#four" class="goto-next scrolly">Next</a>
				</section>
			
			 <!-- Four -->
                                <section id="four" class="spotlight style2 right">
                                        <span class="image fit main"><img src="images/mf1.jpg" alt="" /></span>
                                        <div class="content">
                                                <header>
								<?php
                                                         echo "<center><h1>Most Popular Champion </h1>";
							 list($champion,$winRate, $pickRate) = getPopularChamp($conn1);
                                                           echo" <section>
                                                                <div class=\"table-wrapper\">
                                                                        <table>
                                                                                <thead>
                                                                                        <tr>
                                                                                                <th><center>Champion</th>
                                                                                                <th><center>Pick Rate</th>
                                                                                                <th><center>Win Rate</th>
                                                                                        </tr>
                                                                                </thead>
                                                                                <tbody>";
						$image = $jsonChampions->data->$champion->image;
    				    echo"<tr><td><center>"."<div class=\"img \" style=\"height: 48px; width: 48px; background: url('//ddragon.leagueoflegends.com/cdn/5.16.1/img/sprite/"
        			    .$image->sprite ."') -" .$image->x ."px -" .$image->y ."px ;\"></div>"."<center>"  .$jsonChampions->data->$champion->name;
        
						echo "</center></td><td><center>" .$pickRate ."%</td><td><center>". $winRate."%</center></td></tr>";
						echo"  </tbody>
                                                                        </table>
                                                                </div>";

                                                         echo "<center><h1>Most Successful Champion </h1>";
                                                         list($champion,$winRate, $pickRate) = getBestChamp($conn1);
                                                           echo" <section>
                                                                <div class=\"table-wrapper\">
                                                                        <table>
                                                                                <thead>
                                                                                        <tr>
                                                                                                <th><center>Champion</th>
                                                                                                <th><center>Pick Rate</th>
                                                                                                <th><center>Win Rate</th>
                                                                                        </tr>
                                                                                </thead>
                                                                                <tbody>";
                                                $image = $jsonChampions->data->$champion->image;
                                    echo"<tr><td><center>"."<div class=\"img \" style=\"height: 48px; width: 48px; background: url('//ddragon.leagueoflegends.com/cdn/5.16.1/img/sprite/"
                                    .$image->sprite ."') -" .$image->x ."px -" .$image->y ."px ;\"></div>"."<center>"  .$jsonChampions->data->$champion->name;

                                                echo "</center></td><td><center>" .$pickRate ."%</td><td><center>". $winRate."%</center></td></tr>";
                                                echo"  </tbody>
                                                                        </table>
                                                                </div>";

                                                        ?>


                                                <ul class="actions">
                                                        <li><a href="http://winionwaves.a-a-ron.me/challenge/champions.php" class="button">Data</a></li>
                                                </ul>
                                        </div>
                                        <a href="#five" class="goto-next scrolly">Next</a>
                                </section>
			
 <!-- Five -->
                                <section id="five" class="spotlight style3 left">
                                        <span class="image fit main bottom"><img src="images/items.jpg" alt="" /></span>
                                        <div class="content">
                                                <header>
			
                                                         <?php
							  list($popularID,$popularPickRate,$popularWinRate,$winID,$bestPickRate,$bestWinRate) =getPopularNewItem($conn1);
                                                         echo "<h1><center>Most Popular New Item</h1>";
        echo" <section>
                                                                <div class=\"table-wrapper\">
                                                                        <table>
                                                                                <thead>
                                                                                        <tr>
                                                                                                <th><center>Item</th>
                                                                                                <th><center>Pick Rate</th>
                                                                                                <th><center>Win Rate</th>
                                                                                        </tr>
                                                                                </thead>
                                                                                <tbody>";
								 $name =$jsonItems->data->$popularID->name;
								 
								$image = $jsonItems->data->$popularID->image->full;
 					echo "<td><center>" ."<div class=\"img \" style=\"height: 64px; width: 64px; background: url('//ddragon.leagueoflegends.com/cdn/5.16.1/img/item/".$image."');\"></div>";

       					echo $name ."</td><td><center>" .$popularPickRate ."%</td><td><center>". $popularWinRate."%</center></td></tr>";
echo"  </tbody>
                                                                        </table>
                                                                </div>
";

                                                         echo "<center><h1> Most Successful New Item</h1>";
                                                           echo" <section>
                                                                <div class=\"table-wrapper\">
                                                                        <table>
                                                                                <thead>
                                                                                        <tr>
                                                                                                <th><center>Item</th>
                                                                                                <th><center>Pick Rate</th>
                                                                                                <th><center>Win Rate</th>
                                                                                        </tr>
                                                                                </thead>
                                                                                <tbody>";
								 $name =$jsonItems->data->$winID->name;

                                                                $image = $jsonItems->data->$winID->image->full;
                                        echo "<td><center>" ."<div class=\"img \" style=\"height: 64px; width: 64px; background: url('//ddragon.leagueoflegends.com/cdn/5.16.1/img/item/".$image."');\"></div>";

                                        echo $name ."</td><td><center>" .$bestPickRate ."%</td><td><center>". $bestWinRate."%</center></td></tr>";

echo"  </tbody>
                                                                        </table>
                                                                </div>
";

                   

                                                         ?>
   <ul class="actions">
                                                        <li><a href="http://winionwaves.a-a-ron.me/challenge/Items.php" class="button">Data</a></li>
                                                </ul>
                                        </div>
                                        <a href="#banner" class="goto-next scrolly">Next</a>
                                </section>


			<!-- Four -->
<!--				<section id="four" class="wrapper style1 special fade-up">
					<div class="container">
						<header class="major">
							<h2>Accumsan sed tempus adipiscing blandit</h2>
							<p>Iaculis ac volutpat vis non enim gravida nisi faucibus posuere arcu consequat</p>
						</header>
						<div class="box alt">
							<div class="row uniform">
								<section class="4u 6u(medium) 12u$(xsmall)">
									<span class="icon alt major fa-area-chart"></span>
									<h3>Ipsum sed commodo</h3>
									<p>Feugiat accumsan lorem eu ac lorem amet accumsan donec. Blandit orci porttitor.</p>
								</section>
								<section class="4u 6u$(medium) 12u$(xsmall)">
									<span class="icon alt major fa-comment"></span>
									<h3>Eleifend lorem ornare</h3>
									<p>Feugiat accumsan lorem eu ac lorem amet accumsan donec. Blandit orci porttitor.</p>
								</section>
								<section class="4u$ 6u(medium) 12u$(xsmall)">
									<span class="icon alt major fa-flask"></span>
									<h3>Cubilia cep lobortis</h3>
									<p>Feugiat accumsan lorem eu ac lorem amet accumsan donec. Blandit orci porttitor.</p>
								</section>
								<section class="4u 6u$(medium) 12u$(xsmall)">
									<span class="icon alt major fa-paper-plane"></span>
									<h3>Non semper interdum</h3>
									<p>Feugiat accumsan lorem eu ac lorem amet accumsan donec. Blandit orci porttitor.</p>
								</section>
								<section class="4u 6u(medium) 12u$(xsmall)">
									<span class="icon alt major fa-file"></span>
									<h3>Odio laoreet accumsan</h3>
									<p>Feugiat accumsan lorem eu ac lorem amet accumsan donec. Blandit orci porttitor.</p>
								</section>
								<section class="4u$ 6u$(medium) 12u$(xsmall)">
									<span class="icon alt major fa-lock"></span>
									<h3>Massa arcu accumsan</h3>
									<p>Feugiat accumsan lorem eu ac lorem amet accumsan donec. Blandit orci porttitor.</p>
								</section>
							</div>
						</div>
						<footer class="major">
							<ul class="actions">
								<li><a href="#" class="button">Magna sed feugiat</a></li>
							</ul>
						</footer>
					</div>
				</section>-->

			<!-- Five -->
				<!--<section id="five" class="wrapper style2 special fade">
					<div class="container">
						<header>
							<h2>Magna faucibus lorem diam</h2>
							<p>Ante metus praesent faucibus ante integer id accumsan eleifend</p>
						</header>
						<form method="post" action="#" class="container 50%">
							<div class="row uniform 50%">
								<div class="8u 12u$(xsmall)"><input type="email" name="email" id="email" placeholder="Your Email Address" /></div>
								<div class="4u$ 12u$(xsmall)"><input type="submit" value="Get Started" class="fit special" /></div>
							</div>
						</form>
					</div>
				</section>-->

			<!-- Footer -->
				<footer id="footer">
					<ul class="icons">
						<li><a href="https://github.com/SmiteAndIgnite/BilgewaterTides" class="icon alt fa-github"><span class="label">GitHub</span></a></li>
					</ul>
					<ul class="copyright">
						<li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
					</ul>
				</footer>

		</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/jquery.dropotron.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>
