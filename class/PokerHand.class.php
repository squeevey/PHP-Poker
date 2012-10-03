<?php

class PokerHand  {
	
	private $cards;
	private $sumOfHand;
	private $sortedValueCounts;
	private $sortedSuitCounts;

	//add the cards to the hand, if any passed
	public function __construct()
	{	
		$argList = func_get_args();
		foreach($argList as $card)
		{
			$this->cards[] = $card;
		}
	}

	//return the array of cards
	public function getCards()
	{

		return $this->cards;
	}


	//return a specific card from the hand
	public function getCard($num)
	{
		return $this->cards[$num];
	}

	//return reverse sorted array of the values of the cards as keys, and the count of how many are in the hand.
	public function getValueCounts()
	{
		if(isset($this->sortedValueCounts)) {
			return $this->sortedValueCounts;
		} else {
			$arrayValues = array();
			foreach ($this->cards as $card) {
				$arrayValues[]=$card->getCardValue();
			}
			$this->sortedValueCounts = array_count_values($arrayValues);
			arsort($this->sortedValueCounts);
			//return sum
			return $this->sortedValueCounts;
		}
	}

	//return reverse array of the values of the cards as keys, and the count of how many are in the hand.
	public function getSuitCounts()
	{
		if(isset($this->sortedSuitCounts)) {
			return $this->sortedSuitCounts;
		} else {
			$arraySuit = array();
			foreach ($this->cards as $card) {
				$arraySuit[]=$card->getCardSuit();
			}
			$this->sortedSuitCounts = array_count_values($arraySuit);
			arsort($this->sortedSuitCounts);
			//return sum
			return $this->sortedSuitCounts;
		}
	}

	//takes an array of cards and adds them to the hand. 
	public function addCardsToHand($arrayOfCards) 
	{

		foreach($arrayOfCards as $card)
		{
			$this->cards[] = $card;
		}
	}

	//count the sum of the cards
	public function handSum() 
	{
		$sum = 0;
		foreach ($this->cards as $card) {
			$sum+=$card->getCardValue();
		}
		$this->sumOfHand = $sum;
		//return sum
		return $sum;
	}

	//sort the hand from highest to lowest
	public function sortHand() 
	{
		//get the count of the cards
		//set the swap variable to TRUE to enter the loop
		$cardCount = count($this->cards);
		$swap = TRUE;

		//for each card that needs a swap
		for($i=0;$i<$cardCount AND $swap;$i++)
		{	
			//set the swap to false
			//take the first card if it is less than the next card
			//set the swap variable to true 
			//swap the card positions.
			$swap = FALSE;
			for($j=0;$j<$cardCount-1;$j++)
			{
				if($this->cards[$j]->getCardValue() < $this->cards[$j+1]->getCardValue())
				{	
					$swap=TRUE;
					$tempCard = $this->cards[$j+1];
					$this->cards[$j+1]=$this->cards[$j];
					$this->cards[$j]=$tempCard;
				}

			}
		}
	}
}


?>