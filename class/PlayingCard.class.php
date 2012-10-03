<?php
class PlayingCard {
	private $suit;
	private $value;

	public function __construct($suitIn, $valueIn) {
		$this->suit = $suitIn;
		$this->value = $valueIn;
	}

	public function getCardInfo()
	{	
		
		return array($this->value, $this->suit);
	}

	public function getCardValue()
	{
		return end($this->value);
	}

	public function getCardSuit()
	{
		return end($this->suit);
	}

	public function getCardReadableInfo()
	{
		return array(key($this->value), key($this->suit));
	}

	public function displayCard()
	{
		
		echo "[ " . key($this->value) . " of " . key($this->suit) . " ]";
	}
}
?>