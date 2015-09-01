<?php
	$bilgewater = $argv[1];	
	
	if($bilgewater){
		$source = "/home/data/NA.json";
		$writeFile = "/home/operational/doneBrawlIDs.txt";
	}
	else{
		$source = "/home/data/NAnormal.json";
		$writeFile = "/home/operational/doneNormIDs.txt";
	}
	$doneIds =0;
      	do
      { sleep(2);
	$data = json_decode(file_get_contents($source), true);

		foreach ($data as $newMatch){
			
				//execute code with matchID
				//fclose($inFile);
				$cmd = 'php -f /home/operational/getdata.php ' . $newMatch . ' ' . $bilgewater;
				exec($cmd);
				sleep(1);
				break;
				//exit();
			} 		
		
		//echo 'All match IDs have completed processing!';
	$doneIds = count(file($writeFile));	
      } while($doneIds < 10001 );
?>
