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
The outermost element of our data gathering task (the process by which we actually make API calls and store the   resulting information) is this wrapper, a PHP script that launches our main script based on the remaining match IDs in the JSON source and limits itself to the time constraints of the dev API key. The code that this wrapper calls, which will be explained below, dynamically removes values from the JSON of match IDs to improve the efficiency of this process. This code also tells the main file whether or not the sourced match ID is a Bilgewater game.
  
####getData.php
This is the core script of our system in terms of gathering data from the API. It begins by grabbing the JSON result of a single match ID call, which is then taken linearly through a series of steps to store the data we need. The Baron data is gathered first, followed in order by champion data, item data, and brawler data (if applicable). This data is passed to our SQL database.

##Frontend
####Modules
The landing page is split into sections based on the pieces of the data relevant to those specific statistics, much like the organization of the database tables. The headers for each section give a "TL;DR" of the data we looked for
in that section. For example, in the Champions section, the landing page displays the most popular champion (by pick rate) and what we've dubbed the "most successful" champion, or the champion with the highest win rate while still maintaining a somewhat reasonable pick rate. As such, a champion, item, or brawler combination with a very low play rate relative to its fellows (i.e. a champion found in only 250 of 10,000 analyzed games) is not considered for the spot of "best" or "most successful." Each section features a link to the raw data, generally sorted by pick/play rate from most popular to least popular. 

####HTML5up
There's no hiding the fact that we are not developers or programmers with a history in frontend web, so we opted for a sleek template provided by HTML5up in order to display our content in a way that was clear, concise, and polished, but without sacrificing too much time that could be spent on the backend.
HTML5up can be found here: http://html5up.net/
