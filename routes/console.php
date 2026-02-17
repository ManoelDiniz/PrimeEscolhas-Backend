<?php

use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
*/

Schedule::command('analytics:report')->everyFiveSeconds();

Schedule::command('analytics:device-report')->everyTwoHours();

Schedule::command('analytics:daily-report')->dailyAt('00:00');

Schedule::command('analytics:weekly-report')->weeklyOn(1, '06:00');

Schedule::command('analytics:traffic-source-report')->dailyAt('08:00');

Schedule::command('analytics:top-pages-report')->dailyAt('10:00');
