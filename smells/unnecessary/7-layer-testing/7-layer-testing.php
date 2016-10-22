<?php

/**
 * Subject under test
 */
class Financials
{
}

class SevenLayerTesting extends \PHPUnit_Framework_TestCase
{
    /** @var Financials */
    private $subject;

    public function setUp()
    {
        $this->subject = new Financials();
    }

    public function testComputesTransactionProvit()
    {
        $this->assertTrue(true);
    }
}
