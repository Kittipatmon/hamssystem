<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetVisitorParking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parking:reset-visitor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset visitor parking slots daily at 19:00';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $settings = \Illuminate\Support\Facades\Storage::exists('settings.json') ? json_decode(\Illuminate\Support\Facades\Storage::get('settings.json'), true) : [];
        if (isset($settings['parking_auto_reset']) && $settings['parking_auto_reset'] === false) {
            $this->info('Visitor parking reset is disabled in settings.');
            return;
        }

        $reservations = \App\Models\parking\VisitorReservation::where('status', 'checked_in')
            ->where('is_locked', false)
            ->get();

        foreach ($reservations as $reservation) {
            $reservation->status = 'checked_out';
            if (!$reservation->checkout_datetime) {
                $reservation->checkout_datetime = \Carbon\Carbon::now();
            }
            $reservation->save();

            if ($reservation->slot) {
                $reservation->slot->status = 'available';
                $reservation->slot->save();
            }
        }
        
        $this->info('Visitor parking slots reset successfully.');
    }
}
