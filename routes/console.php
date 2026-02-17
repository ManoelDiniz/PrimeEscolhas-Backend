<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Removendo registros manuais de comandos baseados em classes, pois eles são carregados automaticamente pelo Kernel.
