<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Filament\Resources;

use EkAndreas\Resonator\Filament\Resources\SpamFilterResource\Pages;
use EkAndreas\Resonator\Models\ResonatorSpamFilter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SpamFilterResource extends Resource
{
    protected static ?string $model = ResonatorSpamFilter::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-exclamation';

    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup(): ?string
    {
        return config('resonator.navigation.group', 'Resonator');
    }

    public static function getNavigationLabel(): string
    {
        return __('resonator::resonator.navigation.spam_filters');
    }

    public static function getModelLabel(): string
    {
        return __('resonator::resonator.navigation.spam_filters');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resonator::resonator.navigation.spam_filters');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->label(__('resonator::resonator.labels.email'))
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder(__('resonator::resonator.placeholders.enter_email')),

                Forms\Components\Textarea::make('reason')
                    ->label(__('resonator::resonator.labels.reason'))
                    ->placeholder(__('resonator::resonator.placeholders.enter_reason'))
                    ->rows(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->label(__('resonator::resonator.labels.email'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('reason')
                    ->label(__('resonator::resonator.labels.reason'))
                    ->limit(50)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label(__('resonator::resonator.misc.handled_by'))
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('resonator::resonator.labels.date'))
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSpamFilters::route('/'),
        ];
    }
}
