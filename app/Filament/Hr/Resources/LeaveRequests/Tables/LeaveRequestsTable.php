<?php

namespace App\Filament\Hr\Resources\LeaveRequests\Tables;

use App\Models\LeaveRequest;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class LeaveRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('leaveType.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('days')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                ->badge()
                ->colors([
                    'success' => 'approved',
                        'warning' => 'pending',
                        'danger' => 'rejected',
                    ]),
                TextColumn::make('approver.name')
                    
                    ->sortable(),
                TextColumn::make('approved_at')
                    ->dateTime()
                    ->sortable(),
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
                SelectFilter::make('status')
                ->options([
                    'pending' =>    'Pending', 
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ])
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (LeaveRequest $record) => $record->status === 'pending')
                ->action(function(LeaveRequest $record) {
                    $record->update([
                        'status' => 'approved',
                        'approved_by' => Auth::user()->id,
                        'approved_at' => now(),
                    ]);

                    Notification::make()
                    ->success()
                    ->title(    'Leave approved')
                    ->send();

                }),

                 Action::make('reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (LeaveRequest $record) => $record->status === 'pending')
                ->schema([  
                    Textarea::make('rejection_reason')
                    ->required()
                    ->rows(3)
            ])
                ->action(function(LeaveRequest $record, array $data) {
                    $record->update([
                        'status' => 'rejected',
                        'approved_by' => Auth::user()->id,
                        'approved_at' => now(),
                        'rejection_reason' => $data['rejection_reason'],
                    ]);

                    Notification::make()
                    ->success()
                    ->title(    'Leave rejected')
                    ->send();

                })
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }
}
