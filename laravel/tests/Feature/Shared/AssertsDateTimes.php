<?php


namespace Tests\Feature\Shared;


use DateTime;

trait AssertsDateTimes
{
    protected string $dateTimeFormat = "Y-m-d\TH:i:s\Z";

    protected function assertValidDateTime(string $datetime): bool
    {
        return DateTime::createFromFormat($this->dateTimeFormat, $datetime) instanceof DateTime;
    }

    protected function assertRecentDateTime(string $datetime)
    {
        $then = DateTime::createFromFormat($this->dateTimeFormat, $datetime);
        $this->assertNotFalse($then, "${datetime} does not match the required datetime format!");

        $secondsBetween = (new DateTime())->getTimestamp() - $then->getTimestamp();
        $this->assertLessThan(100000, $secondsBetween);
    }

}
