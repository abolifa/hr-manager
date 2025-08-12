<?php

namespace App\Providers;

use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        FilamentColor::register([
            'rose' => Color::Rose,
            'cyan' => Color::Cyan,
            'blue' => Color::Blue,
            'indigo' => Color::Indigo,
            'purple' => Color::Purple,
            'pink' => Color::Pink,
            'red' => Color::Red,
            'orange' => Color::Orange,
            'yellow' => Color::Yellow,
            'green' => Color::Green,
//            'gray' => Color::Gray,
            'slate' => Color::Slate,
            'zinc' => Color::Zinc,
            'neutral' => Color::Neutral,
            'stone' => Color::Stone,
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
