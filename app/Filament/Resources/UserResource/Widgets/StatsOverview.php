<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\User;

class StatsOverview extends BaseWidget
{
    public bool $readyToLoad = false;

    public function loadData()
    {
        $this->readyToLoad = true;
    }

    protected function getCards(): array
    {
        if (! $this->readyToLoad) {
            return [
                Card::make('Total', 'loading...'),
            ];
        }

        sleep (5);

        return [
            Card::make('Total Users', User::all()->count()),
            Card::make('Administrators', User::where('user_privilege', '=', 'Admin User')->count()),
        ];
    }
}
