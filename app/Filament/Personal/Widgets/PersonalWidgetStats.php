<?php

namespace App\Filament\Personal\Widgets;

use App\Models\Holiday;
use App\Models\Timesheet;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PersonalWidgetStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make('Pending Holidays', $this->getPendingHolidays(Auth::user()))
                ->description("Pending")
                ->descriptionIcon('heroicon-o-hand-raised')
                ->color('warning'),
            Stat::make('Aproved Holidays', $this->getAprovedHolidays(Auth::user()))
                ->description("Aproved")
                ->descriptionIcon('heroicon-o-hand-thumb-up')
                ->color('success'),
            Stat::make('Total Work', $this->getTotalWork(Auth::user()))
        ];
    }

    protected function getPendingHolidays(User $user)
    {
        $totalPendingHolidays = Holiday::where('user_id', $user->id)
            ->where('type', 'pending')
            ->count();
        return $totalPendingHolidays;
    }

    protected function getAprovedHolidays(User $user)
    {
        $totalAprovedHolidays = Holiday::where('user_id', $user->id)
            ->where('type', 'aproved')
            ->count();
        return $totalAprovedHolidays;
    }

    protected function getTotalWork(User $user)
    {
        $timeSheets = Timesheet::where('user_id', $user->id)
            ->where('type', 'work')
            ->get();
        $sumSeconds = 0;
        foreach ($timeSheets as $timeSheet) {
            $start = Carbon::parse($timeSheet->day_in);
            $end = Carbon::parse($timeSheet->day_out);

            $totalDuration = $end->diffInHours($start);
            $sumSeconds += $totalDuration;
        }

        $timeFormat = gmdate("H:i:s", $sumSeconds);
        return $timeFormat;
    }
}
