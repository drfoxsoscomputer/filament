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
            Stat::make('Aproved Holidays', $this->getAprovedHolidays(Auth::user()))
                ->description("Aproved")
                ->descriptionIcon('heroicon-o-hand-thumb-up')
                ->color('success'),
            Stat::make('Pending Holidays', $this->getPendingHolidays(Auth::user()))
                ->description("Pending")
                ->descriptionIcon('heroicon-o-hand-raised')
                ->color('warning'),
            Stat::make('Declined Holidays', $this->getDeclinedHolidays(Auth::user()))
                ->description("Declined")
                ->descriptionIcon('heroicon-o-hand-thumb-down')
                ->color('danger'),
            Stat::make('Total Work', $this->getTotalWork(Auth::user()))
                ->description("Work")
                ->descriptionIcon('heroicon-o-play')
                ->color('success'),
            Stat::make('Total Hours Worked Today', $this->getTotalWorkedToday(Auth::user()))
                ->description("Work")
                ->descriptionIcon('heroicon-o-play')
                ->color('success'),
            Stat::make('Total Pause', $this->getTotalPause(Auth::user()))
                ->description("Pause")
                ->descriptionIcon('heroicon-o-pause')
                ->color('info'),
            Stat::make('Total Hours Paused Today', $this->getTotalPauseToday(Auth::user()))
                ->description("Pause")
                ->descriptionIcon('heroicon-o-pause')
                ->color('info'),
        ];
    }

    protected function getAprovedHolidays(User $user)
    {
        $totalAprovedHolidays = Holiday::where('user_id', $user->id)
            ->where('type', 'aproved')
            ->count();
        return $totalAprovedHolidays;
    }

    protected function getPendingHolidays(User $user)
    {
        $totalPendingHolidays = Holiday::where('user_id', $user->id)
            ->where('type', 'pending')
            ->count();
        return $totalPendingHolidays;
    }

    protected function getDeclinedHolidays(User $user)
    {
        $totalDeclinedHolidays = Holiday::where('user_id', $user->id)
            ->where('type', 'declined')
            ->count();
        return $totalDeclinedHolidays;
    }

    protected function getTotalWork(User $user)
    {
        $timesheets = Timesheet::where('user_id', $user->id)
            ->where('type', 'work')
            ->get();
        $sumSeconds = 0;
        foreach ($timesheets as $timesheet) {
            # code...
            $startTime = Carbon::parse($timesheet->day_in);
            $finishTime = Carbon::parse($timesheet->day_out);

            $totalDuration = $startTime->diffInSeconds($finishTime);
            $sumSeconds = $sumSeconds + $totalDuration;
        }
        $tiempoFormato = gmdate("H:i:s", $sumSeconds);

        return $tiempoFormato;
    }
    protected function getTotalWorkedToday(User $user)
    {
        $timesheets = Timesheet::where('user_id', $user->id)
            ->where('type', 'work')
            ->whereDate('created_at', Carbon::today())
            ->get();
        $sumSeconds = 0;
        foreach ($timesheets as $timesheet) {
            # code...
            $startTime = Carbon::parse($timesheet->day_in);
            $finishTime = Carbon::parse($timesheet->day_out);

            $totalDuration = $startTime->diffInSeconds($finishTime);
            $sumSeconds = $sumSeconds + $totalDuration;
        }
        $tiempoFormato = gmdate("H:i:s", $sumSeconds);

        return $tiempoFormato;
    }

    protected function getTotalPause(User $user)
    {
        $timesheets = Timesheet::where('user_id', $user->id)
            ->where('type', 'pause')
            ->get();
        $sumSeconds = 0;
        foreach ($timesheets as $timesheet) {
            # code...
            $startTime = Carbon::parse($timesheet->day_in);
            $finishTime = Carbon::parse($timesheet->day_out);

            $totalDuration = $startTime->diffInSeconds($finishTime);
            $sumSeconds = $sumSeconds + $totalDuration;
        }
        $tiempoFormato = gmdate("H:i:s", $sumSeconds);

        return $tiempoFormato;
    }

    protected function getTotalPauseToday(User $user)
    {
        $timesheets = Timesheet::where('user_id', $user->id)
            ->where('type', 'pause')
            ->whereDate('created_at', Carbon::today())
            ->get();
        $sumSeconds = 0;
        foreach ($timesheets as $timesheet) {
            # code...
            $startTime = Carbon::parse($timesheet->day_in);
            $finishTime = Carbon::parse($timesheet->day_out);

            $totalDuration = $startTime->diffInSeconds($finishTime);
            $sumSeconds = $sumSeconds + $totalDuration;
        }
        $tiempoFormato = gmdate("H:i:s", $sumSeconds);

        return $tiempoFormato;
    }
}
