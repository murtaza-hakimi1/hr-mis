<?php

namespace App\Filament\Hr\Resources\PerformanceReviews\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PerformanceReviewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                    TextColumn::make('user.employee_id')
                    ->label('Employee ID')
                    ->searchable(),
                TextColumn::make('reviewer.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('review_period')
                    ->searchable(),
                
                TextColumn::make('overall_rating')
                    ->numeric()
                    ->badge()
                    ->suffix(' / 10')
                    ->sortable()
                    ->colors([
                        'danger' => fn($state) => $state < 5,
                        'warning' => fn($state) => $state >= 5 && $state < 8,
                        'success'=> fn($state) => $state >= 8,
                    ]),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->preload(), 

                SelectFilter::make('review_id')
                ->relationship('reviewer', 'name')
                ->searchable()
                ->preload()
            ])
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
