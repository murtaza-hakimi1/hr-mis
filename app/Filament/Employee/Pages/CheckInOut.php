<?php

namespace App\Filament\Employee\Pages;

use App\Models\Attendance;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class CheckInOut extends Page
{
    use HasPageShield;
    protected string $view = 'filament.employee.pages.check-in-out';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowPath;

    protected static string | UnitEnum | null $navigationGroup = 'Attendances';

    public $todayAttendance;
    public $canCheckIn = false;
    public $canCheckOut = false;
    public $currentTime; 

    public function mount(){
        $this->loadAttendance();
        $this->currentTime = now()->format('H:i:s');
    }

    public function LoadAttendance(){
        $this->todayAttendance = Attendance::where('user_id', auth()->user()->id)
            ->where('date', today())
            ->first();

            $this->canCheckIn = !$this->todayAttendance || $this->todayAttendance->check_in === null;
            $this->canCheckOut = $this->todayAttendance && $this->todayAttendance->check_in !== null && $this->todayAttendance->check_out === null;

    }

    public function CheckIn(){

        try {
             if(!$this->canCheckIn){
            return;
        }

        $this->todayAttendance = Attendance::create(
            [
                'user_id'=> auth()->user()->id,
                'date'=> today(),
                'check_in'=> now(),
                'status'=> now()->format('H:i') > '08:00' ? 'late' : 'present',
            ]
        );
         
        Notification::make()
            ->title('Checked In  Successfully')
            ->body('Your check-in time: '.now()->format('h:i A'))
            ->send();
            
            $this->LoadAttendance();
        } catch (\Exception $e) {
            Notification::make()
            ->danger()
            ->title('Check-in Failed')
            ->body('You Have already checked in today')
            ->send();


        }
    }

    public function CheckOut(){
        
            if($this->todayAttendance){
                $this->todayAttendance->update([
                    'check_out' => now(),
                ]);

                  Notification::make()
                  ->success()
                  ->title('Checked out Successfully')
                  ->body('Your check-out time: '. now()->format('h:i A'))
                  ->send();

                  $this->LoadAttendance();

            }
    }

        protected function getHeaderActions():array{
            return [
                Action::make('checkIn')
                ->label('Check In')
                ->icon('heroicon-o-arrow-right-on-rectangle')
                ->color('success')
                ->visible(fn()=> $this->canCheckIn)
                ->requiresConfirmation()
                ->modalHeading('check in')
                ->modalDescription('Are you sure you want to check in now ?')
                ->modalSubmitActionLabel('Yes, check in ')
                ->action(fn() => $this->CheckIn()),

                Action::make('CheckOut')
                ->label('Check Out')
                ->icon('heroicon-o-arrow-left-on-rectangle')
                ->color('danger')
                ->visible(fn()=> $this->canCheckOut)
                ->requiresConfirmation()
                ->modalHeading('check out')
                ->modalDescription('Are you sure you want to check out now ?')
                ->modalSubmitActionLabel('Yes, check out ')
                ->action(fn() => $this->CheckOut())
            ];
        
    }


}

