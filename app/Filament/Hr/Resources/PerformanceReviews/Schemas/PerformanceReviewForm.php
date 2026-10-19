<?php

namespace App\Filament\Hr\Resources\PerformanceReviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PerformanceReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make("Review Information")
                ->columns(2)
                ->schema([
                        Select::make('user_id')
                            ->relationship('user' , 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('review_id')
                        ->relationship('reviewer','name')
                                ->searchable()
                                ->preload()
                                ->required(),
                        TextInput::make('review_period')
                            ->default(now()->format('Y-m-d H:i:s'))
                            ->placeholder('Select Review Period')
                            ->required(),
                ]),

                Section::make('Performance Metrics (1-10)')
                ->columns(2)
                ->schema([
                            TextInput::make('quality_of_work')
                                ->required()
                                ->minValue(1)
                                ->maxValue(10)
                                ->live()
                                ->afterStateUpdated(fn($state, Set $set, Get $get) => 
                                
                                    self::calculateOverallRating($set, $get))
                                ->numeric(),
                            TextInput::make('productivity')
                                ->required()
                                ->minValue(1)
                                ->maxValue(10)
                                ->live()
                                ->afterStateUpdated(fn($state, Set $set, Get $get) => 
                                
                                    self::calculateOverallRating($set, $get))
                                ->numeric(),
                            TextInput::make('communication')
                                ->required()
                                ->minValue(1)
                                ->maxValue(10)
                                ->live()
                                ->afterStateUpdated(fn($state, Set $set, Get $get) => 
                                
                                    self::calculateOverallRating($set, $get))
                                ->numeric(),
                            TextInput::make('teamwork')
                                ->required()
                                ->minValue(1)
                                ->maxValue(10)
                                ->live()
                                ->afterStateUpdated(fn($state, Set $set, Get $get) => 
                                
                                    self::calculateOverallRating($set, $get))
                                ->numeric(),
                            TextInput::make('leadership')
                                ->required()
                                ->minValue(1)
                                ->maxValue(10)
                                ->live()
                                ->afterStateUpdated(fn($state, Set $set, Get $get) => 
                                
                                    self::calculateOverallRating($set, $get))
                                ->numeric(),
                            TextInput::make('overall_rating')
                                ->required()
                                ->suffix(' / 10')
                                ->disabled()
                                ->dehydrated()
                                ->numeric(),
                ]),
               Section::make('Feedback and Goals')
               ->columns(2)
               ->columnSpanFull()
               ->schema([
                            Textarea::make('strengths')
                                ->default(null)
                                ->columnSpanFull(),
                            Textarea::make('areas_for_improvement')
                                ->default(null)
                                ->columnSpanFull(),
                            Textarea::make('goals')
                                ->default(null)
                                ->columnSpanFull(),
                            Textarea::make('comments')
                                ->default(null)
                                ->columnSpanFull(),
               ]),
               
              
            ]);
    }

    protected static function calculateOverallRating(Set $set, Get $get){
        $qualityOfWork = (int) $get('quality_of_work');
        $productivity =(int)  $get('productivity');
        $communication = (int) $get('communication');
        $teamwork = (int) $get('teamwork');
        $leadership = (int) $get('leadership');

        $overallRating = round(($qualityOfWork + $productivity + $communication + $teamwork + $leadership ) / 5, 2);

        $set('overall_rating', $overallRating);

    } 

}
