<?php

namespace App\Filament\Resources\HolidayResource\Pages;

use App\Filament\Resources\HolidayResource;
use App\Mail\HolidayAproved;
use App\Mail\HolidayDeclined;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class EditHoliday extends EditRecord
{
    protected static string $resource = HolidayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        if ($record->type == 'aproved') {
            $user = User::find($record->user_id);
            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'day' => $record->day
            ];

            Mail::to($user)->send(new HolidayAproved($data));

            $recipient = $user;

            Notification::make()
                ->title('Solicitud de Vacaciones')
                ->body("El día " . $data['day'] . ' ha sido aprovado')
                ->sendToDatabase($recipient);
        } else if ($record->type == 'declined') {
            $user = User::find($record->user_id);
            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'day' => $record->day
            ];

            Mail::to($user)->send(new HolidayDeclined($data));

            $recipient = $user;

            Notification::make()
                ->title('Solicitud de Vacaciones')
                ->body("El día " . $data['day'] . ' ha sido rechazado')
                ->sendToDatabase($recipient);
        }

        return $record;
    }
}
