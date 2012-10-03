<?php
class PokerGame  {

	
	public $seats =5;

	public $pokerPlayers;  
	public $cardDeck;
	public $dealer;
	public $communityCards;

	public function __construct($deckOfCards, $players)
	{
		$this->cardDeck = $deckOfCards;
		$this->dealer = new Player('DEALER');
		try
		{	
			if (count($players) > $this->seats)
			 {
			 	throw new Exception("Sorry, there are too many players");
			 	
			}
			else
			{
				foreach($players as $player)
				{
					$this->pokerPlayers[] = $player;
				}	
			}
		}
		catch(Exception $e)
		{
			echo 'Error: ' . $e->getMessage() . "\n";
		}

	}

	public function addPlayer($player)
	{
		try
		{
			if (count($pokerPlayers) >= $seats) //$dealer is included in the Poker Players
			{
				throw new Exception("No Seats Available");
				
			}
			else
			{
				$this->pokerPlayers[]=$player;
			}
		}
		catch(Exception $e)
		{
			echo 'Error: ' . $e->getMessage() . "\n";
		}
	}

	public function getWinner() 
	{
		$scoreArray = array();
		foreach($this->pokerPlayers as $node =>$pokerPlayer)
		{
			$scoreArray[$pokerPlayer->playerName] = array($pokerPlayer->getHandScore(), $pokerPlayer);

		}
		$scoreArray[$this->dealer->playerName] = array($this->dealer->getHandScore(), $this->dealer);

		$highScore = 0;
		$highScoreArray = array();
		foreach($scoreArray as $player)
		{
			if($player[0] == $highScore)
			{
				//There will be at least ONE player in the highscorearray
				//compare the player to the winners.
				$winner = $highScoreArray[0];
				
				$winnerCounts = $winner[1]->getBestHand()->getValueCounts();

				$newCounts = $player[1]->getBestHand()->getValueCounts();
				
				$maxArray = max(array_keys($newCounts), array_keys($winnerCounts));

				$arrayReturn = array_intersect_key($newCounts, array_flip($maxArray));
				//print_r($highScoreArray);
				//echo "\nWinner Counts: \n";
			//	print_r($winnerCounts);
			//	echo "\nNewCounts: \n";
			//	print_r($newCounts);
			//	echo "\nMaxCounts: \n";
			//	print_r($maxArray);
			//	echo "\nArrayReturn\n";
			//	print_r($arrayReturn);
				//special case for straights
				if($highScore == 9 OR $highScore ==5)
				{
					$sumOfPlayer = $player[1]->getBestHand()->handSum();
					$sumOfWinner = $winner[1]->getBestHand()->handSum();
					if($sumOfPlayer != 28 AND  $sumOfWinner != 28)
					{
						if($sumOfPlayer == $sumOfWinner) 
						{
							$highScoreArray[] = $player;
						}
						elseif($sumOfPlayer > $sumOfWinner) 
						{	
							//reset the array to zero items because all previous items were ties.
							$highScoreArray = array();
							$highScoreArray[] = $player;
						}
					}
					elseif ($sumOfPlayer != 28 AND $sumOfWinner ==28) 
					{	
						//reset the array to zero items because all previous items were ties.
						$highScoreArray = array();
						$highScoreArray[] = $player;
					}
				}
				elseif($newCounts == $winnerCounts)
				{
					$highScoreArray[] = $player;
				}
				elseif($arrayReturn === $newCounts)
				{
					//reset the array to zero items because all previous items were ties.
					$highScoreArray = array();
					$highScoreArray[] = $player;
				}

			} 
			elseif ($player[0] > $highScore)
			{
				//reset highscore and clear array;
				//this also handles the first item of an array
				$highScoreArray = array();
				$highScoreArray[] = $player;
				$highScore = $player[0];

			}
		}		
		if(count($highScoreArray) > 1)
		{
			echo "\n-------------------\nIt is a tie between: \n-----------------";
			foreach ($highScoreArray as $tiePlayer) {
				$tiePlayer[1]->showBestHand();
			}
		}
		else
		{
			echo "\n-------------------\nThe Winner is: \n-------------------";
			$highScoreArray[0][1]->showBestHand();
		}
		return $highScore;

	}

	public function scoreHand()
	{


		foreach($this->pokerPlayers as $player)
		{
			$player->loadCommunityCards($this->communityCards);
			$player->scoreHand();

		}
		$this->dealer->loadCommunityCards($this->communityCards);
		$this->dealer->scoreHand();

	}


	public function dealHands()
	{
		foreach($this->pokerPlayers as $node => $players)
		{	

			
			$card1 = $this->cardDeck->getCard();
			$card2 = $this->cardDeck->getCard();
			$pokerHand = new PokerHand($card1, $card2);
		 	$players->newHand($pokerHand);

		}
		$pokerHand = new PokerHand($this->cardDeck->getCard(), $this->cardDeck->getCard());
		$this->dealer->newHand($pokerHand);

	}

	public function showHands()
	{
		foreach($this->pokerPlayers as $node =>$players)
		{	
			$players->showHand();
		}
		$this->dealer->showHand();
	}

	public function showBestHands()
	{
		foreach($this->pokerPlayers as $node =>$players)
		{	
			$players->showBestHand();
		}
		$this->dealer->showBestHand();
	}

	public function dealCommunityCards()
	{
		$this->communityCards = array(
			$this->cardDeck->getCard(), 
			$this->cardDeck->getCard(), 
			$this->cardDeck->getCard(),
			$this->cardDeck->getCard(),
			$this->cardDeck->getCard());
	}

	public function showCommunityCards()
	{
		echo "\nCommunity Cards: \n";
		foreach($this->communityCards as $card)
		{
			$card->displayCard();
		}
		echo "\n\n";
	}


	public function shuffleCards()
	{
		$this->cardDeck->shuffleDeck();
	}
}

?>