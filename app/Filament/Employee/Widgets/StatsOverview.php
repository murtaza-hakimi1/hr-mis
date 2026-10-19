<?php

namespace App\Filament\Employee\Widgets;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $userId = auth()->user()->id;
        return [
            Stat::make('My Leave Request', 
                LeaveRequest::where('user_id', $userId)->count(),

            )

                ->description('Total leave requests')
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('info'),

                Stat::make('Pending Leaves',
                LeaveRequest::where('user_id', $userId)->where('status', 'pending')->count(),
                )
                ->description('Awaiting Approval')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

                Stat::make('This Month Attendance',
                    Attendance::where('user_id', $userId)
                    ->whereMonth('date', date('m'))
                    ->whereYear('date', date('Y'))
                    ->count()
                )

                ->description('Days Recorded')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('primary'),

                Stat::make('Last Payroll',
                    Payroll::where('user_id', $userId)
                    ->where('status', 'paid')
                    ->latest()
                    ->first()
                    ?->net_salary ?? 0

                )

                ->description('Last Payment')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),


        ];
    }
}
