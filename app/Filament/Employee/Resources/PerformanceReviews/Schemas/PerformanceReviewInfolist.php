<?php

namespace App\Filament\Employee\Resources\PerformanceReviews\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PerformanceReviewInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('review_id')
                    ->numeric(),
                TextEntry::make('review_period'),
                TextEntry::make('quality_of_work')
                    ->numeric(),
                TextEntry::make('productivity')
                    ->numeric(),
                TextEntry::make('communication')
                    ->numeric(),
                TextEntry::make('teamwork')
                    ->numeric(),
                TextEntry::make('leadership')
                    ->numeric(),
                TextEntry::make('overall_rating')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
