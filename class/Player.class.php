<?php
class Player {


	public $playerName;
	//this will include the community cards when they are up.
	private $gameHand;
	//this is the hand score for the bestHand
	private $handScore;
	//THIS IS AN ARRAY, not a PokerHand Object of the best hand of the player
	private $bestHand;

	//variable array to translate the players score to poker hand name.
	private $handName = array(
		1 => "a HIGH CARD",
		2 => "a PAIR",
		3 => "TWO PAIR",
		4 => "THREE of a KIND",
		5 => "a STRAIGHT",
		6 => "a FLUSH",
		7 => "a FULL HOUSE",
		8 => "FOUR of a KIND",
		9 => "a STRAIGHT FLUSH",
		10=> "a  ♛ ROYAL FLUSH ♛"
		);



	//create a player with their name.
	public function __construct($name)
	{
		$this->playerName = $name;
	}

	// a new poker hand.
	public function newHand($pokerHand)
	{	
		$this->gameHand = $pokerHand;
	}

	//display the player's game hand.
	public function showHand()
	{
		$cards = $this->gameHand->getCards();
		echo "\n$this->playerName: ";
		foreach($cards as $card)
		{
			$card->displayCard();			
		}
		echo "\n";

	}

	// Return the Hand Score or throw an error if not set.
	public function getHandScore()
	{	
		try
		{
			if(isset($this->handScore))
			{
				return $this->handScore;		
			}
			else
			{
				throw new Exception("$this->playerName has no score.");	
			}
		}
		catch(Exception $e)
		{
			echo 'Error: ' . $e->getMessage() . "\n";
		}

	}

	// Return the Best Hand Array or throw an error if it's not set
	public function getBestHand()
	{
		try
		{
			if(isset($this->bestHand))
			{
				return $this->bestHand;		
			}
			else
			{
				throw new Exception("$this->playerName does not have a best hand yet.");	
			}

		}
		catch(Exception $e)
		{
			echo 'Error: ' . $e->getMessage() . "\n";
		}
	}

	//Display to the console the best hand
	public function showBestHand()
	{
		try
		{
			//Best Hand is not a pokerhand object in this case.
			$cards = $this->bestHand->getCards();
			echo "\n$this->playerName with " . $this->handName[$this->handScore] . ": \n";
			foreach($cards as $card)
			{
				$card->displayCard();			
			}
			echo "\n";
		}
		catch(Exception $e)
		{
			echo 'Error: ' . $e->getMessage() . "\n";
		}
	}

	//Add the community cards to the player's hand
	public function loadCommunityCards($communityCards)
	{

		$this->gameHand->addCardsToHand($communityCards);
		
	}

	//sort the hand
	public function sortHand()
	{
		$this->gameHand->sortHand();

	}

	//Make all (21) five combinations of cards and score them.
	public function scoreHand()
	{	
		/* Sort all 7 cards. All future subhands will be in decending order, so we don't have to sort again */
		$this->sortHand();
		$this->handScore = 0;
		for($card1=0; $card1 < 3 AND $this->handScore < 10; $card1++)
		{
			for($card2=$card1+1; $card2 < 4 AND $this->handScore < 10; $card2++)
			{
				for($card3=$card2+1; $card3 < 5 AND $this->handScore < 10 ; $card3++)
				{
					for($card4=$card3+1; $card4 < 6 AND $this->handScore < 10; $card4++)
					{
						for($card5=$card4+1; $card5 < 7 AND $this->handScore < 10; $card5++)
						{	
							//get 5 combinations of cards
							$newHandOfFiveCards = new PokerHand(
											$this->gameHand->getCard($card1),
											$this->gameHand->getCard($card2),
											$this->gameHand->getCard($card3),
											$this->gameHand->getCard($card4),
											$this->gameHand->getCard($card5)
										);

							//This sets the first value so we can score it.
							if (!isset($this->bestHand))
							{
								$this->bestHand = $newHandOfFiveCards;
							}
							//unique values and the count of those values
							$currentArrayOfValues = $newHandOfFiveCards->getValueCounts();
							$currentArrayOfSuits = $newHandOfFiveCards->getSuitCounts();

							//get the number of uniques in the hand
							$currentValuesCount = count($currentArrayOfValues);
							$currentSuitCount = count($currentArrayOfSuits);

							//get just the values for comparison later
							$currentValues = array_keys($currentArrayOfValues);							
							$highestCurrentValue = current($currentArrayOfValues);

							//get the unique values, suits, and count of the best hand
							$bestArrayOfValues = $this->bestHand->getValueCounts();
							$bestArrayOfSuits = $this->bestHand->getSuitCounts();
							$bestValuesCount = count($bestArrayOfValues);
							$bestSuitCount = count($bestArrayOfSuits);
							$bestValues = array_keys($bestArrayOfValues);

							//Return the max of the two.
							$maxArray = max($currentValues, $bestValues);

							//inialize the isStraight variable to false
							$isStraight=FALSE;

							//get the first card of the new hand compute the descending sum of five cards. 
							$straightAdd = ($newHandOfFiveCards->getCard(0)->getCardValue() * 5) - 10;

							//get the sum of the hands
							$sumOfHand = $newHandOfFiveCards->handSum();
							$sumOfBestHand = $this->bestHand->handSum();

							//Must be 5 unique cards AND 
							// the sum must match the above algorithm computed from the first card, or 
							//the special case (sum 28) AND  Ace is the highcard (of a sorted hand)
							if($currentValuesCount==5 AND 
								($sumOfHand == $straightAdd OR 
									($sumOfHand == 28 AND $newHandOfFiveCards->getCard(0)->getCardValue() ==14 )))
							{
								$isStraight=TRUE;
							}

							//initialize the current handscore to 0;
							$currentHandScore = 0;

							//In these if statements we set the score if the current is better
							//Otherwise it stays at 0.  At the end, 
							// if the $currentHandScore is >= we then assign the hand to the best hand


							//Check if 4 of Kind or Full House
							if( $currentValuesCount==2)
							{
								//if 4 of a kind
								if($highestCurrentValue==4)
								{	
									//if the hand score is LESS than 8. Then auto set. 
									if($this->handScore < 8)
									{
										$currentHandScore = 8;
									}
									elseif($this->handScore == 8)
									{
										//if the max of the two hands equals the current hand
										if($maxArray==$currentValues)
										{
											$currentHandScore = 8;
										}
									}
									
								} 
								elseif ($highestCurrentValue==3)
								{
									//if the hand score is LESS than 7. Then auto set. 
									if($this->handScore < 7)
									{
										$currentHandScore = 7;
									}
									elseif($this->handScore == 7)
									{

										if($maxArray==$currentValues)
										{
											$currentHandScore = 7;
										}
									}
								}
							}
							//Check if FLUSH properties
							elseif($currentSuitCount==1)
							{
								//is it a straight flush?
								if($isStraight)
								{
									//is it a royal flush?
									if($sumOfHand == 60)
									{
										$currentHandScore = 10;
									}
									else
									{
										if($this->handScore < 9)
										{
											$currentHandScore = 9;
										}
										elseif($this->handScore ==9)
										{
											//if they aren't both WHEELS, compare the sums
											if($sumOfHand != 28 AND  $sumOfBestHand != 28)
											{
												if($sumOfHand > $sumOfBestHand)
												{
													$currentHandScore = 9;
												}
											}
											elseif ($sumOfHand != 28 AND $sumOfBestHand==28) 
											{	//if the current hand isn't Ace low, but the best hand is...replace it.
												//otherwise, besthand is the same or higher. 
												$currentHandScore = 9;
											}
											
										}
									}
								}
								else
								{
									//This is just a flush
									//if the hand score is LESS than 6. Then auto set. 
									if($this->handScore < 6)
									{
										$currentHandScore = 6;
									}
									elseif($this->handScore == 6)
									{

										if($maxArray==$currentValues)
										{
											$currentHandScore = 6;
										}
									}

								}

							}
							elseif($currentValuesCount==3)
							{	//3 values means 3 of a kind or 2 pair

								//is this a 3 of a Kind?
								if($highestCurrentValue==3)
								{	
									if($this->handScore < 4)
									{
										$currentHandScore = 4;
									}
									elseif($this->handScore == 4)
									{

										if($maxArray==$currentValues)
										{
											$currentHandScore = 4;
										}
									}
									
								}  
								// check if  2 pairs
								elseif ($highestCurrentValue==2)
								{
									if($this->handScore < 3)
									{
										$currentHandScore = 3;
									}
									elseif($this->handScore == 3)
									{

										if($maxArray==$currentValues)
										{
											$currentHandScore = 3;
										}
									}
								}
							}
							elseif ($currentValuesCount == 4)
							{	//4 values means a pair

								//is this a Pair?
								if($highestCurrentValue==2)
								{	
									if($this->handScore < 2)
									{
										$currentHandScore = 2;
									}
									elseif($this->handScore == 2)
									{

										if($maxArray==$currentValues)
										{
											$currentHandScore = 2;
										}
									}
									
								}  
								
							}
							elseif ($currentValuesCount==5)
							{

								//this is a straight compare
								if($isStraight)
								{

									if($this->handScore < 5)
									{
										$currentHandScore = 5;
									}
									elseif($this->handScore ==5)
									{
										//if they aren't both wheels, compare the sums
										if($sumOfHand != 28 AND  $sumOfBestHand != 28)
										{
											if($sumOfHand > $sumOfBestHand)
											{
												$currentHandScore = 5;
											}
										}
										elseif ($sumOfHand != 28 AND $sumOfBestHand==28) 
										{	//if the current hand isn't Ace low, but the best hand is...replace it.
											//otherwise, besthand is the same or higher. 
											$currentHandScore = 5;
										}
										
									}

								}
								else
								{
									//this is a high card compare
									if($this->handScore < 1)
									{
										$currentHandScore = 1;
									}
									elseif($this->handScore == 1)
									{

										if($maxArray==$currentValues)
										{
											$currentHandScore = 1;
										}
									}
								}
							}
							//If the handscore is less than or equal to the currenHandScore then the new hand is better. 
							if($this->handScore <= $currentHandScore)
							{
								$this->bestHand = $newHandOfFiveCards;
								$this->handScore = $currentHandScore;
							}
						}	
					}
				}
			}
		}
	}
}


?>