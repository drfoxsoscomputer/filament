<?php

namespace App\Filament\Personal\Resources\TimesheetResource\Pages;

use App\Filament\Personal\Resources\TimesheetResource;
use App\Models\Timesheet;
use Carbon\Carbon;
use EightyNine\ExcelImport\ExcelImportAction;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListTimesheets extends ListRecords
{
    protected static string $resource = TimesheetResource::class;

    protected function getHeaderActions(): array
    {
        $lastTimesheet = Timesheet::where('user_id', Auth::user()->id)->orderBy('day_in', 'desc')->first();
        if (!$lastTimesheet) {
            return [
                Action::make('inWork')
                    ->label('Comenzar a trabajar')
                    ->color('success')
                    ->icon('heroicon-o-play-circle')
                    ->requiresConfirmation()
                    ->modalIcon('heroicon-o-play-circle')
                    ->action(function () {
                        $user = Auth::user();
                        $timesheet = new Timesheet();
                        $timesheet->calendar_id = 1;
                        $timesheet->user_id = $user->id;
                        $timesheet->day_in = Carbon::now();
                        $timesheet->type = 'work';
                        $timesheet->save();
                    }),
                // Actions\CreateAction::make()
            ];
        }
        return [
            Action::make('inWork')
                ->label('Comenzar a trabajar')
                ->color('success')
                ->icon('heroicon-o-play-circle')
                ->visible(!$lastTimesheet->day_out == null)
                ->disabled($lastTimesheet->day_out == null)
                ->requiresConfirmation()
                ->modalIcon('heroicon-o-play-circle')
                ->action(function () {
                    $user = Auth::user();
                    $timesheet = new Timesheet();
                    $timesheet->calendar_id = 1;
                    $timesheet->user_id = $user->id;
                    $timesheet->day_in = Carbon::now();
                    $timesheet->type = 'work';
                    $timesheet->save();

                    Notification::make()
                        ->title('Comenzaste a trabajar')
                        ->icon('heroicon-o-play-circle')
                        ->color('success')
                        ->success()
                        ->send();
                }),
            Action::make('stopWork')
                ->label('Parar de trabajar')
                ->color('success')
                ->icon('heroicon-o-stop-circle')
                ->visible($lastTimesheet->day_out == null && $lastTimesheet->type != 'pause')
                ->disabled(!$lastTimesheet->day_out == null)
                ->requiresConfirmation()
                ->modalIcon('heroicon-o-stop-circle')
                ->action(function () use ($lastTimesheet) {
                    $lastTimesheet->day_out = Carbon::now();
                    $lastTimesheet->save();

                    Notification::make()
                        ->title('Terminaste de trabajar')
                        ->icon('heroicon-o-stop-circle')
                        ->color('success')
                        ->success()
                        ->send();
                }),
            Action::make('inPause')
                ->label('Pausar trabajo')
                ->color('info')
                ->icon('heroicon-o-pause-circle')
                ->visible($lastTimesheet->day_out == null && $lastTimesheet->type != 'pause')
                ->disabled(!$lastTimesheet->day_out == null)
                ->requiresConfirmation()
                ->modalIcon('heroicon-o-pause-circle')
                ->action(function () use ($lastTimesheet) {
                    $lastTimesheet->day_out = Carbon::now();
                    $lastTimesheet->save();
                    $timesheet = new Timesheet();
                    $timesheet->calendar_id = 1;
                    $timesheet->user_id = Auth::user()->id;
                    $timesheet->day_in = Carbon::now();
                    $timesheet->type = 'pause';
                    $timesheet->save();

                    Notification::make()
                        ->title('Pausaste tu trabajo')
                        ->icon('heroicon-o-pause-circle')
                        ->color('info')
                        ->info()
                        ->send();
                }),
            Action::make('stopPause')
                ->label('Seguir Trabajando')
                ->color('info')
                ->icon('heroicon-o-play-circle')
                ->visible($lastTimesheet->day_out == null && $lastTimesheet->type == 'pause')
                ->disabled(!$lastTimesheet->day_out == null)
                ->requiresConfirmation()
                ->modalIcon('heroicon-o-play-circle')
                ->action(function () use ($lastTimesheet) {
                    $lastTimesheet->day_out = Carbon::now();
                    $lastTimesheet->save();
                    $timesheet = new Timesheet();
                    $timesheet->calendar_id = 1;
                    $timesheet->user_id = Auth::user()->id;
                    $timesheet->day_in = Carbon::now();
                    $timesheet->type = 'work';
                    $timesheet->save();

                    Notification::make()
                        ->title('Seguir trabajando')
                        ->icon('heroicon-o-play-circle')
                        ->color('info')
                        ->info()
                        ->send();
                }),
            Actions\CreateAction::make(),
            ExcelImportAction::make()
                ->color("info"),
        ];
    }
}
