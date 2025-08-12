<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Select;

class Selector extends Select
{
    public function setUp(): void
    {
        parent::setUp();

        $this
            ->native(false)
            ->searchable()
            ->preload();
    }
}
