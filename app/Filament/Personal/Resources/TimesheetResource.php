<?php

namespace App\Filament\Personal\Resources;

use App\Filament\Personal\Resources\TimesheetResource\Pages;
use App\Filament\Personal\Resources\TimesheetResource\RelationManagers;
use App\Models\Timesheet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class TimesheetResource extends Resource
{
    protected static ?string $model = Timesheet::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';
    public static function getPluralLabel(): string
    {
        return 'Asistencias';
    }

    protected function getTitle(): string
    {
        return 'Crear Asistencia';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::user()->id)->orderBy('id', 'desc');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\Select::make('calendar_id')
                    ->label('Calendario')
                    ->relationship(name: 'calendar', titleAttribute: 'name')
                    ->required(),
                // Forms\Components\Select::make('user_id')
                //     ->relationship(name: 'user', titleAttribute: 'name')
                //     ->required(),
                Forms\Components\Select::make('type')
                    ->label('Tipo')
                    ->options([
                        'work' => 'Working',
                        'pause' => 'In Pause',
                    ])
                    ->required(),
                Forms\Components\DateTimePicker::make('day_in')
                    ->label('Entrada')
                    ->required(),
                Forms\Components\DateTimePicker::make('day_out')
                    ->label('Salida')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('calendar.name')
                    ->label('Calendario')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'work' => 'success',
                        'pause' => 'warning',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('day_in')
                    ->label('Entrada')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('day_out')
                    ->label('Salida')
                    ->dateTime()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado el')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                SelectFilter::make('type')
                    ->options([
                        'work' => 'Working',
                        'pause' => 'In Pause',
                    ]),
                Filter::make('calendar_id')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])

            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ExportBulkAction::make()
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimesheets::route('/'),
            'create' => Pages\CreateTimesheet::route('/create'),
            'edit' => Pages\EditTimesheet::route('/{record}/edit'),
        ];
    }
}
