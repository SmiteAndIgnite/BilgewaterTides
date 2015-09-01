# BilgewaterTides

Repo for submission of project in the Riot API Challenge 2.0 <br>
Entrants: Roberto Miguel (Rawbearto), Aaron Kemmer (RustledBadKid)
##
--------------------
### Our Stack
  LAMP via CentOS 7 <br>
  PHP for the backend analytics <br>
  Javascript/HTML frontend - Landed template by HTML5up! <br>
  
##Database

  Our SQL database is the core link between analysis and presentation.
  Storing the data obtained via the API allowed us to split work and processing time
  without losing data. As a result, we spent the first week of the competition period 
  setting up scripts to gather the data that we deemed important and store it here. 
  The database is organized for Black Market games as follows and dublicated (with the exception of brawler data)
  for normal game type analysis: <br>
  * Baron Data Table
    * Time of first Baron slain
    * Team that got first Baron
    * Time of last Baron slain
    * Team that got last Baron
    * Game duration (end of match timestamp)
    * Winning team
  
  * Champions Data Table
    * Champion ID
    * Games won
    * Games played

  * Items Data Table
    * Champion ID
    * Final Item Combination
    * Wins with said combination
    * Games played with said combination
    
  * Brawlers Data Table (Each row represents a different combination of brawlers)
    * Combination ID
    * Number of Razorfins
    * Number of Ironbacks
    * Number of Plundercrabs
    * Number of Ocklepods
    * Number of wins with combination
    * Number of games with combination

##Analytical Backend

####finalwrapper.php
The outermost element of our data gathering task (the process by which we actually make API calls and store the   resulting information) is this wrapper, a PHP script that launches our main script based on the remaining match IDs in the JSON source and limits itself to the time constraints of the dev API key. The code that this wrapper calls, which will be explained below, dynamically removes values from the JSON of match IDs to improve the efficiency of this process.
  
##
