<?php

namespace App\Filament\Hr\Resources\Payrolls\Tables;

use App\Jobs\GeneratePayrollJob;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

use Filament\Notifications\Notification;

class PayrollsTable
{
    public static function configure(Table $table): Table
    {
        return $table

        ->headerActions([
            Action::make("generate_payroll")
                ->label("Generate Payroll")
                ->icon("heroicon-o-cog")
                ->color("success")
                ->schema([
                    Select::make('month')
                        ->options([
                            'January'=> 'January',
                            'February' => 'February',
                            'March' => 'March',
                            'April' => 'April',
                            'May'   => 'May',
                            'June' => 'June',
                            'July' => 'July',
                            'August'    => 'August',
                            'September'=> 'September',
                            'October'=> 'October',
                            'November'=> 'November',
                            'December'=> 'December',
                        ])
                            ->default(now()->format('F'))
                            ->required(),
                    TextInput::make('year')
                        ->required()
                        ->numeric()
                        ->default(now()->year)
                        ->minValue(2020)
                        ->maxValue(now()->year +1),
                    Select::make('user_id')
                        ->label('Employee (Optional)')
                        ->placeholder('Generate fo all employees')
                        ->relationship('user' , 'name')
                        ->searchable()
                        ->preload(),


            ])
            ->action(function(array $data){
                GeneratePayrollJob::dispatch($data['month'], $data['year'], $data['user_id'] ?? null); 

                Notification::make()
                ->success()
                ->title('Payroll Generation started')
                ->body('Payroll is being generated in the background. You will receive will be notified once completed.')
                ->send();
            })
        ])
            ->columns([
                TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                    TextColumn::make('user.employee_id')
                    ->label('Employee Code')
                    ->searchable(),
                TextColumn::make('month')
                    ->searchable(),
                TextColumn::make('year')
                    // ->numeric()
                    ->sortable(),
                TextColumn::make('basic_salary')
                    ->money('USD')
                    ->sortable(),
                // TextColumn::make('allowances')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('deduction')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('bonus')
                //     ->numeric()
                //     ->sortable(),
                TextColumn::make('net_salary')
                    ->money('USD')
                    ->sortable(),
                 BadgeColumn::make('status')
                ->colors([
                    'success' => 'paid',
                    'warning' => 'processed',
                     'danger'=> 'draft',
                     ]),
                TextColumn::make('paid_at')
                    ->date()
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
                //
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
