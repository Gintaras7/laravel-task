<?php

use App\Console\Commands\ImportProductsCommand;
use App\Console\Commands\ImportStocksCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(ImportStocksCommand::class)->dailyAt('00:00');
Schedule::command(ImportProductsCommand::class)->dailyAt('00:02');
