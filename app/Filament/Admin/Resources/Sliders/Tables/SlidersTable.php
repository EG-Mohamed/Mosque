<?php

namespace App\Filament\Admin\Resources\Sliders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use TinusG\FilamentHoverImageColumn\HoverImageColumn;

class SlidersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                HoverImageColumn::make('image')
                    ->label(__('Image')),

                TextColumn::make('media_type')
                    ->label(__('Type'))
                    ->badge(),

                TextColumn::make('title')
                    ->label(__('Title')),

                TextColumn::make('sort_order')
                    ->label(__('Sort Order'))
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label(__('Active')),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
