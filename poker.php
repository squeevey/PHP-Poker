<?php

//start Timer
$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime; 


include 'init.php';

function playPoker()
{
	$player1 = new Player('Player 1');
	$player2 = new Player('Player 2');
	$player3 = new Player('Player 3');
	$player4 = new Player('Player 4');
	$player5 = new Player('Player 5');
	$deckOCards = new Deck();

	echo "\n--------- NEW GAME ---------\n";
	$gameOfPoker = new PokerGame($deckOCards, array($player1, $player2, $player3, $player4,$player5));
	$gameOfPoker->shuffleCards();
	$gameOfPoker->dealHands();

	//Comment Out "showHands to Hide Hands";
	$gameOfPoker->showHands();

	$gameOfPoker->dealCommunityCards();

	//Comment Out "showCommunityCards to Hide Cards";
	$gameOfPoker->showCommunityCards();

	$gameOfPoker->scoreHand();

	//Comment Out "showBestHands to Hide Best hands";
	$gameOfPoker->showBestHands();
	$highScore = $gameOfPoker->getWinner();
	return $highScore;
}


$gamesPlayed = 0;
$highestHandScore = 0;
// set the handbrake to the minimum hand point value you wish to break on...Minimum should be one more than the h
$whileLoopHandBrake = 1;

//counting array for stats of how many types of hands won before the royal flush hit.
$handName = array(
		1 =>0,
		2 =>0,
		3 =>0,
		4 =>0,
		5 =>0,
		6 =>0,
		7 =>0,
		8 => 0,
		9 => 0,
		10=>0
		);

while ($highestHandScore < $whileLoopHandBrake)
{
	$highestHandScore = playPoker();
	$handName[$highestHandScore]++;
	$gamesPlayed++;
}




$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$endtime = $mtime; 
$totaltime = ($endtime - $starttime); 

/* Hide Stats for now. 
echo "\nScript ran for ".$totaltime." seconds.\n"; 
echo "Total Games Played: $gamesPlayed\n";
echo "Other Hand Counts: \n";
print_r($handName);

*/

?>