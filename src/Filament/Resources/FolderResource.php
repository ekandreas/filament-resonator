<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Filament\Resources;

use EkAndreas\Resonator\Filament\Resources\FolderResource\Pages;
use EkAndreas\Resonator\Models\ResonatorFolder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FolderResource extends Resource
{
    protected static ?string $model = ResonatorFolder::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return config('resonator.navigation.group', 'Resonator');
    }

    public static function getNavigationLabel(): string
    {
        return __('resonator::resonator.navigation.folders');
    }

    public static function getModelLabel(): string
    {
        return __('resonator::resonator.labels.folder');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resonator::resonator.navigation.folders');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('resonator::resonator.labels.name'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('slug')
                    ->label(__('resonator::resonator.labels.slug'))
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->disabled(fn ($record) => $record?->is_system)
                    ->helperText(__('resonator::resonator.helpers.slug')),

                Forms\Components\Select::make('icon')
                    ->label(__('resonator::resonator.labels.icon'))
                    ->searchable()
                    ->options([
                        'heroicon-o-inbox' => 'Inbox',
                        'heroicon-o-paper-airplane' => 'Paper Airplane',
                        'heroicon-o-archive-box' => 'Archive',
                        'heroicon-o-shield-exclamation' => 'Shield',
                        'heroicon-o-trash' => 'Trash',
                        'heroicon-o-folder' => 'Folder',
                        'heroicon-o-star' => 'Star',
                        'heroicon-o-flag' => 'Flag',
                        'heroicon-o-tag' => 'Tag',
                        'heroicon-o-bookmark' => 'Bookmark',
                    ]),

                Forms\Components\Select::make('color')
                    ->label(__('resonator::resonator.labels.color'))
                    ->options([
                        'gray' => 'Gray',
                        'primary' => 'Primary',
                        'success' => 'Success',
                        'info' => 'Info',
                        'warning' => 'Warning',
                        'danger' => 'Danger',
                    ]),

                Forms\Components\TextInput::make('sort_order')
                    ->label(__('resonator::resonator.labels.sort_order'))
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('icon')
                    ->icon(fn ($state) => $state)
                    ->color(fn ($record) => $record->color ?? 'gray'),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('resonator::resonator.labels.name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color(fn ($record) => $record->color),

                Tables\Columns\TextColumn::make('threads_count')
                    ->counts('threads')
                    ->badge()
                    ->label(''),

                Tables\Columns\TextColumn::make('slug')
                    ->label(__('resonator::resonator.labels.slug'))
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('is_system')
                    ->label(__('resonator::resonator.labels.system'))
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state
                        ? __('resonator::resonator.labels.system')
                        : __('resonator::resonator.labels.custom'))
                    ->color(fn ($state) => $state ? 'info' : 'gray'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(fn ($record) => $record->is_system),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn ($record) => $record->is_system),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageFolders::route('/'),
        ];
    }
}
