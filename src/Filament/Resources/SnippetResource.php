<?php

declare(strict_types=1);

namespace EkAndreas\Resonator\Filament\Resources;

use EkAndreas\Resonator\Filament\Resources\SnippetResource\Pages;
use EkAndreas\Resonator\Models\ResonatorSnippet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SnippetResource extends Resource
{
    protected static ?string $model = ResonatorSnippet::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return config('resonator.navigation.group', 'Resonator');
    }

    public static function getNavigationLabel(): string
    {
        return __('resonator::resonator.navigation.snippets');
    }

    public static function getModelLabel(): string
    {
        return __('resonator::resonator.labels.snippet');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resonator::resonator.navigation.snippets');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('resonator::resonator.labels.name'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('resonator::resonator.placeholders.enter_name')),

                Forms\Components\TextInput::make('shortcut')
                    ->label(__('resonator::resonator.labels.shortcut'))
                    ->unique(ignoreRecord: true)
                    ->maxLength(50)
                    ->placeholder(__('resonator::resonator.placeholders.enter_shortcut'))
                    ->helperText(__('resonator::resonator.helpers.shortcut')),

                Forms\Components\TextInput::make('subject')
                    ->label(__('resonator::resonator.labels.subject'))
                    ->maxLength(255)
                    ->placeholder(__('resonator::resonator.placeholders.enter_subject')),

                Forms\Components\RichEditor::make('body')
                    ->label(__('resonator::resonator.labels.body'))
                    ->required()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'link',
                        'bulletList',
                        'orderedList',
                    ])
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('sort_order')
                    ->label(__('resonator::resonator.labels.sort_order'))
                    ->numeric()
                    ->default(0),

                Forms\Components\Toggle::make('is_active')
                    ->label(__('resonator::resonator.labels.active'))
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('resonator::resonator.labels.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('shortcut')
                    ->label(__('resonator::resonator.labels.shortcut'))
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('subject')
                    ->label(__('resonator::resonator.labels.subject'))
                    ->limit(30)
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('resonator::resonator.labels.active'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('resonator::resonator.labels.sort_order'))
                    ->toggleable(isToggledHiddenByDefault: true),
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
            ->reorderable('sort_order')
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSnippets::route('/'),
        ];
    }
}
