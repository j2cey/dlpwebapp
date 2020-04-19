<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Autorisation;
use Carbon\Carbon;

class AutorisationSetExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autorisation:setexpired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Traque et met à jour les autorisations échues';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Carbon::setLocale('fr');
        
        $date_now = Carbon::now();
        $date_now->addHours(1);

        //$nb_autorisations_echues = Autorisation::where('is_active', 1)->where('date_fin', '<', Carbon::now())->count();
        $nb_autorisations_echues = Autorisation::where('is_active', 1)->where('date_fin', '<', $date_now)
          ->update([
              'is_active' => 0
        ]);
        //dd($nb_autorisations_echues);
        $this->info('Command autorisation:setexpired executee avec succes!');
        \Log::info($nb_autorisations_echues . " autorisation(s) echue(s).");
    }
}
