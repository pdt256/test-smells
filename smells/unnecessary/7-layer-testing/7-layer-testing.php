<?php
define('FLOAT_DELTA', 0.000001);

/**
 * Subject under test
 */
class Financials
{
    /** @var Repo */
    private $repo;

    public function __construct(Repo $repo)
    {
        $this->repo = $repo;
    }

    public function getTransactionProfit(Transaction $transaction): int
    {
        return $transaction->getPrice() - $transaction->getCost();
    }

    public function getDailyProfit($year, $month, $day): int
    {
        $transactions = $this->repo->findByDate($year, $month, $day);
        print_r($transactions);
        $total = 0;
        foreach ($transactions as $transaction) {
            $total += $this->getTransactionProfit($transaction);
        }
        return $total;
    }
}

class SevenLayerTesting extends \PHPUnit_Framework_TestCase
{
    /** @var Financials */
    private $subject;

    /** @var Repo */
    private $repo;

    public function setUp()
    {
        $this->repo = new Repo();
        $this->subject = new Financials($this->repo);
    }

    public function testComputesDailyProfit()
    {
        $this->repo->saveTransactions([
            new Transaction(2016, 5, 12, 19.44, 18.11),
            new Transaction(2016, 5, 12, 21.40, 22.01),
            new Transaction(2016, 5, 12, 998.10, 907.20),
            # Bad day:
            new Transaction(2016, 5, 1, 999, 0),
        ]);

        $result = $this->subject->getDailyProfit(2016, 5, 12);

        $this->assertEquals(91, $result, null, FLOAT_DELTA);
    }

    public function testComputesTransactionProfit()
    {
        $transaction = new Transaction(2016, 10, 22, 33.22, 20.11);

        $result = $this->subject->getTransactionProfit($transaction);

        $this->assertSame(13, $result);
    }
}

class Repo
{
    /** @var Transaction[] */
    private $transactions;

    public function __construct()
    {
        $this->transactions = [];
    }

    /**
     * @param Transaction[] $transactions
     */
    public function saveTransactions(array $transactions)
    {
        foreach ($transactions as $transaction) {
            $this->addTransaction($transaction);
        }
    }

    public function addTransaction(Transaction $transaction)
    {
        $this->transactions[] = $transaction;
    }

    /**
     * @param int $year
     * @param int $month
     * @param int $day
     * @return Transaction[]|Generator
     */
    public function findByDate(int $year, int $month, int $day): Generator
    {
        foreach ($this->transactions as $transaction) {
            if (
                $transaction->getYear() === $year
                && $transaction->getMonth() === $month
                && $transaction->getDay() === $day
            ) {
                yield $transaction;
            }
        }
    }
}

class Transaction
{
    private $year;
    private $month;
    private $day;
    private $price;
    private $cost;

    public function __construct(int $year, int $month, int $day, float $price, float $cost)
    {
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
        $this->price = $price;
        $this->cost = $cost;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

    public function getDay(): int
    {
        return $this->day;
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
