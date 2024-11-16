<?php

namespace App\Filament\Widgets;

use App\Models\Holiday;
use App\Models\Timesheet;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalEmployees = User::count();
        $totalHolidays = Holiday::count();
        $totalTimesheets = Timesheet::count();
        $totalHolidaysDeclined = Holiday::where('type', 'declined')->count();
        $totalHolidaysAproved = Holiday::where('type', 'aproved')->count();
        $totalHolidaysPending = Holiday::where('type', 'pending')->count();

        return [
            //
            Stat::make('Employees', $totalEmployees),
            Stat::make('Timesheets', $totalTimesheets),
            Stat::make('Holidays', $totalHolidays),
            Stat::make('Holidays Declined', $totalHolidaysDeclined)
            ->description("Declined")
            ->descriptionIcon('heroicon-o-hand-thumb-down')
            ->color('danger'),
            Stat::make('Holidays Aproved', $totalHolidaysAproved)
            ->description("Aproved", $totalHolidaysAproved)
            ->descriptionIcon('heroicon-o-hand-thumb-up')
            ->color('success'),
            Stat::make('Holidays Pending', $totalHolidaysPending)
            ->description("Pending")
            ->descriptionIcon('heroicon-o-hand-raised')
            ->color('warning'),
        ];
    }
}
