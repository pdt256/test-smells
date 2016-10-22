<?php

/**
 * Subject under test
 */
class Financials
{
    public function getTransactionProfit(Transaction $transaction): int
    {
        return $transaction->getPrice() - $transaction->getCost();
    }
}

class SevenLayerTesting extends \PHPUnit_Framework_TestCase
{
    /** @var Financials */
    private $subject;

    public function setUp()
    {
        $this->subject = new Financials();
    }

    public function testComputesTransactionProfit()
    {
        $transaction = new Transaction(33.22, 20.11);

        $result = $this->subject->getTransactionProfit($transaction);

        $this->assertSame(13, $result);
    }
}

class Transaction
{
    private $price;
    private $cost;

    public function __construct(float $price, float $cost)
    {
        $this->price = $price;
        $this->cost = $cost;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCost(): float
    {
        return $this->cost;
    }
}
