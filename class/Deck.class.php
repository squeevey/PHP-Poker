<?php
class Deck {
	private  $deckValues = array(
		array("A" => 14,
			"K" => 13, 
			"Q" =>12, 
			"J" =>11, 
			"10" =>10,
			"9"=>9, 
			"8"=>8,
			"7" =>7,
			"6"=>6,
			"5"=>5, 
			"4"=>4,
			"3"=>3, 
			"2"=>2),
		array("♠" => 'spades' ,"♥" => 'hearts', "♦" => 'diamonds', "♣" => 'clubs')
		);
	//array of Card objects
	private  $fullDeck = array();
	//check variable for ensuring the deck count matches.
	private  $deckSize = 52;
	//The pointer
	private  $cardPointer = 0;


	public function __construct()
	{

		foreach($this->deckValues[0] as $cardValueKey => $cardValueValue)	
		{
			foreach($this->deckValues[1] as $cardSuitKey => $cardSuitValue)	
			{
				
				try
				{	
					//build the deck using the above array.
					$card = new PlayingCard(   array($cardSuitKey=>$cardSuitValue),  array($cardValueKey=>$cardValueValue));
					$this->fullDeck[] = $card;
					//make sure the size is correct.
					if ((count($this->fullDeck) > $this->deckSize))
					{
						throw new Exception("Initialization Error, Cards exceed deck size");
						
					}

				}
				catch(Exception $e)
				{
					echo 'Error: ' . $e->getMessage() . "\n";
				}

			}
		}
	}

	//randomize the deck and reset the cardPointer to 0.
  	public  function shuffleDeck() {
  		shuffle($this->fullDeck);
  		$this->cardPointer=0;
  	}

  	public  function getCard() {
  		try
  		{	//if the pointer is less than the deck size. Return a card
  			if($this->cardPointer < $this->deckSize)
  			{	
 
  				$cardOut = $this->fullDeck[$this->cardPointer];
  				$this->cardPointer++;
  				return $cardOut;
  
  			}
  			else {
  				throw new Exception("Sorry, Out of Cards");
  				
  			}
  		}
  		catch(Exception $e)
		{
			echo 'Error: ' . $e->getMessage() . "\n";
		}
  		
  	}

}
?>