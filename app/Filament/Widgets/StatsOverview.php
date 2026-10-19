<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{

    protected ?string $pollingInterval = '10s';
    protected function getStats(): array
    {
        return [
            Stat::make('Total Employees', User::count())
            ->description('Active Employees')
            ->descriptionIcon('heroicon-o-users')
            ->color('success'),

            Stat::make('Departments', Department::count())
            ->description('Total Department')
            ->descriptionIcon('heroicon-o-building-office')
            ->color('info'),

            Stat::make('Pending Leave Requests', LeaveRequest::where('status','pending')->count())
            ->description('Awaiting Approval')
            ->descriptionIcon('heroicon-o-calendar-days')
            ->color('warning'),

            Stat::make('Today\'s Attendance', Attendance::whereDate('date','today')->count())
            ->description('Checked in today')
            ->descriptionIcon('heroicon-o-clock')
            ->color('primary'),
        ];
    }
}
