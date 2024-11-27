<?php

namespace App\Livewire;

use App\Http\Controllers\FarmerController;
use DateTime;
use Livewire\Component;

class Dashboard extends Component
{
    private function formatTimeDifference($datetimeStr)
    {
        $datetimeObj = new DateTime($datetimeStr);
        $now = new DateTime();
        $diff = $now->diff($datetimeObj);

        if ($diff->days > 1) {
            return $diff->days . ' days ago';
        } elseif ($diff->days == 1) {
            return 'Yesterday';
        } elseif ($diff->h > 0) {
            return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
        } elseif ($diff->i > 0) {
            return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
        } elseif ($diff->s > 0) {
            return $diff->s . ' second' . ($diff->s > 1 ? 's' : '') . ' ago';
        } else {
            return 'Just now';
        }
    }
    public function render()
    {
        $farmer = (new FarmerController)->getFarmerData(auth()->user()->id);
        $deliveries = (new FarmerController)->getFarmersDeliveries(auth()->user()->id, 4);

        // loop through the deliveries and format the last_delivery_time
        foreach ($deliveries as &$delivery) {
            $delivery->delivery_time = $this->formatTimeDifference($delivery->delivery_time);
        }

        return view('livewire.dashboard', [
            'farmer' => $farmer,
            'deliveries' => $deliveries
        ])->layout('layouts.farmer')->title('Dashboard');
    }

    public function getApiData()
    {
        $deliveries = (new FarmerController)->getFarmersDeliveriesStatus(auth()->user()->id);

        return response()->json([
            'deliveries' => $deliveries
        ]);
    }
}
