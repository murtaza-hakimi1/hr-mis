<?php

namespace App\Filament\Employee\Resources\LeaveRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(auth()->user()->id)
                    ->disabled()
                    ->dehydrated()
                    ->required(),
                Select::make('leave_type_id')
                    ->relationship('leaveType', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('start_date')
                    ->minDate(now()->subDay())
                    ->live()
                    ->required()
                    ->afterStateUpdated(fn($state, Set $set, Get $get) =>
                    self::calculateDays($set, $get)
                    ),
                DatePicker::make('end_date')
                    ->minDate(now())    
                    ->live()    
                    ->afterStateUpdated(fn($state, Set $set, Get $get) =>
                        self::calculateDays($set, $get)
                        )
                    ->required(),
                TextInput::make('days')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ,
                Textarea::make('reasons')
                    ->required()
                    ->columnSpanFull(),
                // Select::make('status')
                //     ->options(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'])
                //     ->default('pending')
                //     ->required(),

                Hidden::make('status')
                ->default('pending')
            ]);
    }

    protected static function calculateDays(Set $set, Get $get){
        $start = $get('start_date');
        $end = $get('end_date');

        if($start && $end){
            $startDate = Carbon::parse($start);
            $endDate = Carbon::parse($end);

            $days = $startDate->diffInDays($endDate) + 1;

            $set('days', $days);
            
        }
    }
}
