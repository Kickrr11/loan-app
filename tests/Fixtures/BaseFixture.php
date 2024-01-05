<?php

namespace Tests\Fixtures;

use App\Models\User;
use Carbon\Carbon;

trait BaseFixture
{
    protected User $user;

    /**
     * @return void
     */
    public function createUser(): void
    {
        $this->user = User::factory()->create();
    }

    public function thenAt($year, $month, $day, $hour, $minute, $second)
    {
        $dateTime = Carbon::now();
        $dateTime->year = $year;
        $dateTime->month = $month;
        $dateTime->day = $day;
        $dateTime->hour = $hour;
        $dateTime->minute = $minute;
        $dateTime->second = $second;
        Carbon::setTestNow($dateTime);
    }
}
