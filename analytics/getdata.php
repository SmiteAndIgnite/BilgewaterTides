<?php
$matchID = $argv[1];
$bilgewater = $argv[2];


$Username = "obfuscated";
$Password = "obfuscated";
$host = "localhost";

if($bilgewater){
	$dbname = "APICHALLENGE";
}
else{
	$dbname = "NORMAL";
}
//$connect = mysql_connect($host, $Username, $Password);
$connect = new mysqli($host, $Username, $Password, $dbname);
if ($connect->connect_error) {
    die("Connection failed: " . mysqli_connect_error());
}


$aFile = fopen("/home/operational/aaron.keys", 'r');
$rFile = fopen("/home/operational/rob.keys", 'r');

$API_KEY_AARON = trim(fgets($aFile));
$API_KEY_ROB = trim(fgets($rFile));

fclose($aFile);
fclose($rFile);


if($bilgewater){
	$API_KEY = $API_KEY_AARON;
}
else{
	$API_KEY = $API_KEY_ROB;
}

$json = json_decode(file_get_contents('https://na.api.pvp.net/api/lol/na/v2.2/match/' . $matchID . '?includeTimeline=true&api_key=' . $API_KEY));


if (!$json->timeline){
	echo "ERROR GETTING JSON!!\n";
	exit();
}
//var_dump($json['timeline']);

$gameEnd = FALSE;
$eventEnd = FALSE;
$firstBaronFlag = FALSE;
$eventCounter;
$frameCounter = 1;
$timeBaronKilled = 0;
$firstBaronTime = 0;
$firstBaronTeam = 0;
$lastBaronTime = 0;
$lastBaronTeam = 0;
//var_dump($json['timeline']['frames']);
$lastBaronParticipant = "";
//while(!$gameEnd){

//	$eventCounter = 0;
//	$eventEnd = 0;
	


$firstFrame = true;
foreach($json->timeline->frames as $frames)
{
        if($firstFrame){
                //do nothing
                $firstFrame = false;
        }
        else{
                foreach($frames->events as $event)
                {
                        if($event->eventType == 'ELITE_MONSTER_KILL')
                                if($event->monsterType == 'BARON_NASHOR')
                		{
					$timeBaronKilled = $event->timestamp;
					 if(!$firstBaronFlag){
                                        $firstBaronFlag = TRUE;
                                        $firstBaronTime = $timeBaronKilled;
                                }
                                $lastBaronTime = $timeBaronKilled;
				$lastBaronParticipant = $event->killerId;
				}
		}
        }

}

if ($json->teams[0]->winner){
	$winningTeam = 1;
}
else{
	$winningTeam = 2;
}


$firstTeamBarons = $json->teams[0]->baronKills;
$secondTeamBarons = $json->teams[1]->baronKills;

if ($timeBaronKilled != 0){
	if ($json->teams[0]->firstBaron){
		$firstBaronTeam = 1;
	}
	else{
		$firstBaronTeam = 2;
	}
}
else
	$firstBaronTeam = 0;


$endTimeMilli = $json->matchDuration * 1000;
$lastBaronTeam = 0;
if ($lastBaronParticipant != ""){
	foreach ($json->participants as $player){
		if ($player->participantId == $lastBaronParticipant){
			$lastBaronTeam = $player->teamId;
			break;
		}
	}
}
$myquery = "INSERT INTO BaronData(firstBaronTime, firstBaronTeam, lastBaronTime, lastBaronTeam, aTeamBarons, bTeambarons, endTime, winningTeam) 
		VALUES($firstBaronTime, $firstBaronTeam, $lastBaronTime, $lastBaronTeam, $firstTeamBarons, $secondTeamBarons, $endTimeMilli, $winningTeam)";

$worked = $connect->query($myquery);

if ($worked)
{
        echo "<FONT COLOR =#00ff00>Baron Data was successfully updated</FONT>";
}
else{
        echo "<FONT COLOR =#ff0000>Baron Data could not be updated</FONT>";

}

if($bilgewater){
	$writeFile = "/home/operational/doneBrawlIDs.txt";
	$path ="/home/data/NA.json";
}
else{
	$writeFile = "/home/operational/doneNormIDs.txt";
	$path ="/home/data/NAnormal.json";
}

$line = $matchID .",";
$contents = file_get_contents($path);
$contents = str_replace($line, '', $contents);
file_put_contents($path, $contents);
 
$fWrite = fopen($writeFile, "a");
fwrite($fWrite, $matchID . "\n");
fclose($fWrite);

//$jsonParam = serialize($json);


$json_data = $json;

if($json_data->teams[0]->winner == true)
$winId = $json_data->teams[0]->teamId;
else
$winId = $json_data->teams[1]->teamId;



// GETTING CHAMPIONS
$i =0;

 while($i < 10)
{
        $championID = $json_data->participants[$i]->championId;
        $myquery = "SELECT * FROM Champions WHERE ChampID='$championID'";

        $result = $connect->query($myquery);
        $rows = mysqli_num_rows($result);
if($json_data->participants[$i]->teamId == $winId)
{
        if($rows == 0)
        {
        $query ="INSERT INTO Champions(ChampID,Wins, Games) Values($championID,1,1)";
        }
        else
        {
        $query ="UPDATE Champions SET Wins= Wins +1, Games=Games+1 WHERE ChampID=$championID";

        }
}
else
{
        if($rows == 0)
        {
        $query ="INSERT INTO Champions(ChampID,Wins, Games) Values($championID,0,1)";
        }
        else
        {
        $query ="UPDATE Champions SET Games=Games+1 WHERE ChampID=$championID";

        }

}

$worked = $connect->query($query);

$i = $i +1;
}

//GETTING ITEMS
foreach($json_data->participants as $participant)
{
	
	$items = array(
		 0   => 0,
        	 1   => 0,
        	 2   => 0,
        	 3   => 0,
        	 4   => 0,
		 5   => 0,
		
        );

        for($i=0; $i <= 5; $i++)
        { $item = "item" .$i;
          //$items[$i] = $participant->stats->$item;
          $items[$i] =$participant->stats->$item;
         // $itemid ="" .$participant->stats->$item;
         // echo "<br>" .$json_items->data->$itemid->name;
        }
	sort($items);
	$combination = "";
	foreach($items as $it)
	$combination = $combination .$it .",";

        $championID = $participant->championId;
        $myquery = "SELECT * FROM Items WHERE ChampID='$championID' AND Combination ='$combination'";

        $result = $connect->query($myquery);
        $rows = mysqli_num_rows($result);
if($participant->stats->winner)
{
        if($rows == 0)
        {
        $query ="INSERT INTO Items(ChampId, Combination,Wins, Games) Values($championID,\"$combination\",1,1)";
        }
        else
        {
        $query ="UPDATE Items SET Wins= Wins +1, Games=Games+1 WHERE ChampID=$championID AND Combination ='$combination'";
        }
}
else
{
        if($rows == 0)
        {
        $query ="INSERT INTO Items(ChampId, Combination,Wins, Games) Values($championID,\"$combination\",0,1)";
        }
        else
        {
        $query ="UPDATE Items SET Games=Games+1 WHERE ChampID=$championID  AND Combination ='$combination'";

        }

}

$worked = $connect->query($query);

}




//GETTING BRAWLERS


if($bilgewater){
$Team1Brawlers = array(
        3611 => 0,
        3612 => 0,
        3613 => 0,
        3614 => 0,
        );
$Team2Brawlers = array(
        3611 => 0,
        3612 => 0,
        3613 => 0,
        3614 => 0,
        );

$firstFrame = true;
foreach($json_data->timeline->frames as $frames)
{
	if($firstFrame){
		//do nothing
		$firstFrame = false;
	}
	else{
        	foreach($frames->events as $event)
        	{
                	if($event->eventType == 'ITEM_PURCHASED')
                        	if($event->itemId >= 3611 && $event->itemId <= 3614)
                                	if($event->participantId <=5)
                                		$Team1Brawlers[$event->itemId]++;
                                	else
                                		$Team2Brawlers[$event->itemId]++;
        	}
	}

}

$Team1Concat ="";
$Team2Concat = "";

foreach($Team1Brawlers as $brawler)
$Team1Concat = $Team1Concat .$brawler;

foreach($Team2Brawlers as $brawler)
$Team2Concat = $Team2Concat .$brawler;

$i =0;


//echo"COMBINATIONS:<br>";
 $myquery = "SELECT * FROM Brawlers WHERE Combination='$Team1Concat'";

        $result = $connect->query($myquery);
        $rows1 = mysqli_num_rows($result);
 $myquery = "SELECT * FROM Brawlers WHERE Combination='$Team2Concat'";

        $result = $connect->query($myquery);
        $rows2 = mysqli_num_rows($result);

if($winId == 100)
{
if($rows1 ==0)
{
$query1 = "INSERT INTO Brawlers(Combination, Razorfin, Ironback, Plundercrab, Ocklepod, Wins, Games)
Values($Team1Concat, $Team1Brawlers[3611], $Team1Brawlers[3612], $Team1Brawlers[3613], $Team1Brawlers[3614],1,1)";
}

else
{
$query1 ="UPDATE Brawlers SET Wins= Wins +1, Games=Games+1 WHERE Combination='$Team1Concat'";
}


if($rows2 ==0)
{
$query2 = "INSERT INTO Brawlers(Combination, Razorfin, Ironback, Plundercrab, Ocklepod,Wins, Games)
Values($Team2Concat,$Team2Brawlers[3611], $Team2Brawlers[3612], $Team2Brawlers[3613], $Team2Brawlers[3614],0,1)";
}
else
{
$query2 ="UPDATE Brawlers SET Games=Games+1 WHERE Combination='$Team2Concat'";
}


}
else
{


if($rows1 ==0)
{
$query1 = "INSERT INTO Brawlers(Combination, Razorfin, Ironback, Plundercrab, Ocklepod, Wins, Games)
Values($Team1Concat,$Team1Brawlers[3611], $Team1Brawlers[3612], $Team1Brawlers[3613], $Team1Brawlers[3614], 0,1)";
}
else
{
$query1 ="UPDATE Brawlers SET Games=Games+1 WHERE Combination='$Team1Concat'";
}

if($rows2 ==0)
{
$query2 = "INSERT INTO Brawlers(Combination, Razorfin, Ironback, Plundercrab, Ocklepod, Wins, Games)
Values($Team2Concat,$Team2Brawlers[3611], $Team2Brawlers[3612], $Team2Brawlers[3613], $Team2Brawlers[3614],1,1)";
}
else
{
$query2 ="UPDATE Brawlers SET Wins= Wins +1, Games=Games+1 WHERE Combination='$Team2Concat'";
}
}


$worked = $connect->query($query1);
$worked = $connect->query($query2);


}


exit();
?>
