<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('health:check', function (): void {
    $this->info('Application running.');
});
