<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Note;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('All registered users')
                ->icon('heroicon-o-user-group')
                ->color('success'),
            Stat::make('Total Notes', Note::count())
                ->description('All registered users')
                ->icon('heroicon-o-user-group')
                ->color('success'),
        ];
    }
}
