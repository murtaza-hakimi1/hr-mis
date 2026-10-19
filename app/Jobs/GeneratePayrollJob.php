<?php

namespace App\Jobs;

use App\Models\Payroll;
use App\Models\User;
use Filament\Actions\Concerns\InteractsWithActions;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Pest\Mutate\Event\Events\Test\Outcome\Timeout;

class GeneratePayrollJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;


    public $timeout = 300;
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $month, public int $year, public $userId = null)
    {
        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $query = User::where('status', 'active')->whereNotNull('salary');

        if($this->userId){
            $query->where('user_id', $this->userId);
        }

        $employees = $query->get();

        foreach($employees as $employee)
            try{
            $existingPayroll = Payroll::where('user_id', $employee->id)
            ->where('month', $this->month)
            ->where('year', $this->year)
            ->first();

            if($existingPayroll){
                Log::info("Payroll already exist for {$employee->name} - {$this->month} - {$this->year}");
                continue;
            }


            $allowances = 500;
            $bonus = 200;
            $deductions = 100;

            $basicSalary = $employee->salary;
            $netSalary = $basicSalary + $allowances + $bonus + $deductions;

            Payroll::create([
                'user_id'=> $employee->id,
                'month'=> $this->month,
                'year'=> $this->year,
                'basic_salary'=> $basicSalary,
                'allowances'=> $allowances,
                'bonus'=> $bonus,
                'deduction'=> $deductions,
                'net_salary'=> $netSalary,
                'status'=> 'draft',
            ]);

            Log::info("Payroll Generated for {$employee->name} - {$this->month} - {$this->year}");


        }catch(\Throwable $e){
          Log::error("Error Generating payroll for {$employee->name} - {$this->month} - {$this->year} - {$e->getMessage()}");
          continue;
        }

    }
}
