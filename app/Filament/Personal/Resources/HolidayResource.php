<?php

namespace App\Filament\Personal\Resources;

use App\Filament\Personal\Resources\HolidayResource\Pages;
use App\Filament\Personal\Resources\HolidayResource\RelationManagers;
use App\Models\Holiday;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class HolidayResource extends Resource
{
    protected static ?string $model = Holiday::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    // public static function getNavigationLabel(): string
    // {
    //     return __('Holidays');
    // }

    public static function getPluralLabel(): string
    {
        return __('Holidays');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::user()->id);
    }

    protected function getTitle(): string
    {
        return __('Create Holiday');
    }

    public static function getNavigationBadge(): ?string
    {
        return parent::getEloquentQuery()->where('user_id', Auth::user()->id)->where('type','pending')->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('calendar_id')
                    ->relationship(name: 'calendar', titleAttribute: 'name')
                    ->label(__('Calendar'))
                    ->required(),
                // Forms\Components\Select::make('user_id')
                //     ->relationship(name: 'user', titleAttribute: 'name')
                //     ->required(),
                // Forms\Components\Select::make('type')
                //     ->options([
                //         'declined' => 'Declined',
                //         'aproved' => 'Aproved',
                //         'pending' => 'Pending',
                //     ])
                //     ->required(),
                Forms\Components\DatePicker::make('day')
                    ->label(__('Day'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('calendar.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('day')
                    ->date()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'declined' => 'danger',
                        'aproved' => 'success',
                        'pending' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                SelectFilter::make('type')
                    ->options([
                        'declined' => 'Declined',
                        'aproved' => 'Aproved',
                        'pending' => 'Pending',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListHolidays::route('/'),
            'create' => Pages\CreateHoliday::route('/create'),
            'edit' => Pages\EditHoliday::route('/{record}/edit'),
        ];
    }
}
