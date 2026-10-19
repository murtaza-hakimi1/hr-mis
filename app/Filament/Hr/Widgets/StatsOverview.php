<?php

namespace App\Filament\Hr\Widgets;

use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Active Employees', value: User::where('status', 'active')->count())
            ->description('Currently Active')
            ->descriptionIcon('heroicon-o-users')
            ->color('success'),

             Stat::make('Pending Leaves', value: LeaveRequest::where('status', 'pending')->count())
            ->description('Requires action')
            ->descriptionIcon('heroicon-o-calendar-days')
            ->color('warning'),

            Stat::make('Today\ s Attendance', value: Attendance::whereDate('date', today())->where('status', 'present')->count())
            ->description('Present Day')
            ->descriptionIcon('heroicon-o-clock')
            ->color('primary'),

            Stat::make('This Month Payroll', value: Payroll::where('month',  date('F'))
            ->where('year', date('Y'))
            ->where('status', 'paid')
            ->count())
            ->description('Processed This Month')
            ->descriptionIcon('heroicon-o-banknotes')
            ->color('info'),
            
        ];
    }
}
