<?php

namespace App\Livewire;

use App\Http\Controllers\FarmerController;
use App\Http\Controllers\PERIOD_RANGE;
use App\Models\Farmer;
use DateTime;
use Livewire\Component;

class IndexAdmin extends Component
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
        $recent_farmers_payments = (new FarmerController())->getRecentPayments(5);

        // Format the last_delivery_time for each payment
        foreach ($recent_farmers_payments as &$payment) {
            $payment->last_delivery_time = $this->formatTimeDifference($payment->last_delivery_time);
        }

        // Get farmers' data
        $total_farmers = Farmer::count();
        $farmers_growth_rate = (new FarmerController())->getFarmersGrowthRate(PERIOD_RANGE::DAY);

        // Get milk's data
        $daily_milk = (new FarmerController())->getMilkDelivered(PERIOD_RANGE::DAY);
        $daily_milk_growth_rate = (new FarmerController())->getMilkGrowthRate(PERIOD_RANGE::DAY);

        // Get revenue's data... // TODO: work on the logic for calculating revenue
        $total_revenue = (new FarmerController())->getTotalRevenue(PERIOD_RANGE::ALL);
        $revenue_growth_rate = (new FarmerController())->getRevenueGrowthRate(PERIOD_RANGE::DAY);
        $monthly_revenue = (new FarmerController())->getTotalRevenue(PERIOD_RANGE::MONTH);
        $last_revenue_received_at = (new FarmerController())->getLastPaymentMade(PERIOD_RANGE::MONTH);

        if ($last_revenue_received_at != null) {
            $last_revenue_received_at = $last_revenue_received_at->last_payment_time;
        } else {
            $last_revenue_received_at = 'No revenue received yet';
        }

        // Get expenses' data
        // TODO: Implement based on where expenses are stored

        // Get monthly transaction history
        $monthly_farmers_payments = (new FarmerController())->getTotalRevenue(PERIOD_RANGE::MONTH);
        $last_farmer_paid_at = (new FarmerController())->getLastPaymentMade(PERIOD_RANGE::MONTH);

        if ($last_farmer_paid_at != null) {
            $last_farmer_paid_at = $last_farmer_paid_at->last_payment_time;
        } else {
            $last_farmer_paid_at = 'No payment made yet';
        }


        return view('livewire.index-admin', [
            // farmers data
            'total_farmers' => $total_farmers,
            'farmers_growth_rate' => $farmers_growth_rate,
            // milk
            'daily_milk' => $daily_milk,
            'daily_milk_growth_rate' => $daily_milk_growth_rate,
            // revenue
            'total_revenue' => $total_revenue,
            'revenue_growth_rate' => $revenue_growth_rate,
            // expenses
            'total_expenses' => 1235070,
            'expenses_growth_rate' => -2.05,

            // monthly transaction history
            'monthly_farmers_payments' => $monthly_farmers_payments,
            'last_farmer_paid_at' => $last_farmer_paid_at,
            'monthly_revenue' => $monthly_revenue,
            'last_revenue_received_at' => $last_revenue_received_at,
            'monthly_expenses' => 750000,
            'last_expense_paid_at' => '2024-11-26 09:44:40',

            // recent farmers payments
            'recent_farmers_payments' => $recent_farmers_payments,
        ])->layout('layouts.admin')->title('Dashboard');
    }

    public function getApiData()
    {
        $total_farmers = (new FarmerController())->getFarmersCount();
        return [
            'total_farmers' => $total_farmers,
            'farmers_growth_rate' => 10.1,
            'daily_milk' => 1200,
            'daily_milk_growth_rate' => 5.5,
            'total_revenue' => 123987,
            'revenue_growth_rate' => -2.45,
            'total_expenses' => 123987,
            'expenses_growth_rate' => -2.05,
            'monthly_farmers_payments' => 125000,
            'last_farmer_paid_at' => '2024-09-01 13:04:40',
            'monthly_revenue' => 20400,
            'last_revenue_received_at' => '2024-10-01 03:04:40',
            'monthly_expenses' => 75000,
            'last_expense_paid_at' => '2024-11-26 09:44:40',
        ];
    }
}
