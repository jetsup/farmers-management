<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB;

enum PERIOD_RANGE
{
    const DAY = 0;
    const WEEK = 1;
    const MONTH = 2;
    const YEAR = 3;
    const ALL = 4;
}

class FarmerController extends Controller
{
    public function getFarmers()
    {
        $farmers = DB::table('farmers')
            ->join('users', 'farmers.user_id', '=', 'users.id')
            ->join('farmer_cows', 'farmers.id', '=', 'farmer_cows.farmer_id')
            ->select(
                'users.name AS farmer_name',
                'users.email',
                'users.created_at',
                'farmers.phone',
                'farmers.location',
                'farmers.is_verified',
                'farmers.payment_method',
                DB::raw('COUNT(farmer_cows.breed_id) AS breeds_owned')
            )
            ->groupBy(
                'users.name',
                'users.email',
                'farmers.phone',
                'farmers.location',
                'users.created_at',
                'farmers.payment_method',
                'farmers.is_verified'
            )->get();

        // echo $farmers;

        return $farmers;
    }

    /**
     * Get the total number of farmers registered within a given period to today
     * @param int $period_range The period range to get the farmers count (DAY, WEEK, MONTH, YEAR, ALL)
     * @return int The total number of farmers registered since the given period
     */
    public function getFarmersCount(int $period_range = PERIOD_RANGE::DAY)
    {
        $farmers_count = 0;

        if ($period_range == PERIOD_RANGE::DAY) {
            $today = Carbon::today()->toDateString();
            $farmers_count = DB::table('farmers')
                ->join('users', 'farmers.user_id', '=', 'users.id')
                ->where('users.created_at', "=", $today)
                ->count();
        } elseif ($period_range == PERIOD_RANGE::WEEK) {
            $last_week = Carbon::today()->subWeeks()->toDateString();
            $today = Carbon::today()->toDateString();

            $farmers_count = DB::table('farmers')
                ->join('users', 'farmers.user_id', '=', 'users.id')
                ->whereBetween('users.created_at', [$last_week, $today])
                ->count();
        } elseif ($period_range == PERIOD_RANGE::MONTH) {
            $this_month_start = Carbon::today()->startOfMonth()->toDateString();
            $today = Carbon::today();

            $farmers_count = DB::table('farmers')
                ->join('users', 'farmers.user_id', '=', 'users.id')
                ->whereBetween('users.created_at', [$this_month_start, $today])
                ->count();

        } elseif ($period_range == PERIOD_RANGE::YEAR) {
            $this_year_start = Carbon::today()->startOfYear()->toDateString();
            $today = Carbon::today()->subYear()->endOfYear()->toDateString();

            $farmers_count = DB::table('farmers')
                ->join('users', 'farmers.user_id', '=', 'users.id')
                ->whereBetween('users.created_at', [$this_year_start, $today])
                ->count();
        } elseif ($period_range == PERIOD_RANGE::ALL) {
            $farmers_count = DB::table('farmers')
                ->join('users', 'farmers.user_id', '=', 'users.id')
                ->count();
        }

        return $farmers_count;
    }

    public function getFarmerData(int $user_id, bool $include_metadata = false)
    {
        // Get the farmer details, amount of milk sold, amount of money earned, number of cows owned, etc.
        $farmer = DB::table('users')
            ->where('users.id', $user_id)
            ->join('farmers', 'farmers.user_id', '=', 'users.id')
            ->join('farmer_cows', 'farmers.id', '=', 'farmer_cows.farmer_id')
            ->join('milk_delivery', 'farmers.id', '=', 'milk_delivery.farmer_id')
            ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
            ->join('cow_breeds', 'rates.breed_id', '=', 'cow_breeds.id')
            ->select(
                'users.name AS farmer_name',
                'users.email',
                'users.created_at',
                'farmers.phone',
                'farmers.location',
                'farmers.is_verified',
                'farmers.payment_method',
                DB::raw('(SELECT SUM(milk_capacity) FROM milk_delivery WHERE milk_delivery.farmer_id = farmers.id) AS total_milk_sold'),
                DB::raw('SUM(milk_capacity * rate) AS total_earnings'),
                DB::raw('(SELECT COUNT(*) FROM milk_delivery WHERE milk_delivery.farmer_id = farmers.id) AS total_deliveries'),
                DB::raw('(SELECT COUNT(*) FROM milk_delivery WHERE milk_delivery.farmer_id = farmers.id AND is_paid = 0) AS unpaid_deliveries'),
                DB::raw('(SELECT COUNT(*) FROM milk_delivery WHERE milk_delivery.farmer_id = farmers.id AND had_issues = 1) AS issues'),
                DB::raw('(SELECT COUNT(*) FROM farmer_cows WHERE farmer_cows.farmer_id = farmers.id) AS breeds_owned'),
                DB::raw('COUNT(CASE WHEN milk_delivery.is_paid = 1 THEN milk_delivery.farmer_id END) AS paid_deliveries'),
                'cow_breeds.breed AS breed',
                'rates.rate',
                'milk_delivery.created_at AS last_delivery_time'
            )
            // include farmer contact information such as email if include metadata is true
            ->when($include_metadata, function ($query) {
                return $query->addSelect(['users.email', 'farmers.id', 'farmers.phone', 'farmers.location']);
            })
            ->groupBy(
                'users.name',
                'users.email',
                'farmers.id',
                'farmers.phone',
                'farmers.location',
                'users.created_at',
                'farmers.payment_method',
                'farmers.is_verified',
                'cow_breeds.breed',
                'rates.rate',
                'milk_delivery.created_at'
            )
            ->first();

        return $farmer;
    }

    public function getFarmersDeliveries(int $user_id, int $limit = 0)
    {
        $deliveries = DB::table('milk_delivery')
            ->join('farmers', 'milk_delivery.farmer_id', '=', 'farmers.id')
            ->join('users', 'farmers.user_id', '=', 'users.id')
            ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
            ->join('cow_breeds', 'rates.breed_id', '=', 'cow_breeds.id')
            ->select(
                'users.name AS farmer_name',
                'cow_breeds.breed AS breed',
                'rates.rate',
                'milk_delivery.milk_capacity',
                'milk_delivery.is_paid',
                'milk_delivery.had_issues',
                'milk_delivery.created_at AS delivery_time'
            )
            ->where('users.id', $user_id)
            // apply limit if specified
            ->when($limit > 0, function ($query) use ($limit) {
                return $query->limit($limit);
            })
            ->get();

        // print_r($deliveries);
        // die(0);

        return $deliveries;
    }

    public function getFarmersDeliveriesStatus(int $user_id)
    {
        // get number of paid deliveries, unpaid and deliveries with issues for the specified farmer all summed up
        $deliveries = DB::table('milk_delivery')
            ->join('farmers', 'milk_delivery.farmer_id', '=', 'farmers.id')
            ->join('users', 'farmers.user_id', '=', 'users.id')
            ->select(
                DB::raw('COUNT(CASE WHEN milk_delivery.is_paid = 1 THEN milk_delivery.farmer_id END) AS paid_deliveries'),
                DB::raw('COUNT(CASE WHEN milk_delivery.is_paid = 0 THEN milk_delivery.farmer_id END) AS unpaid_deliveries'),
                DB::raw('COUNT(CASE WHEN milk_delivery.had_issues = 1 THEN milk_delivery.farmer_id END) AS issues')
            )
            ->where('users.id', $user_id)
            ->first();

        return $deliveries;
    }

    public function getFarmersGrowthRate(int $period_range = PERIOD_RANGE::DAY)
    {
        $farmers_growth_rate = 0.0;
        $previous_farmers_count = 0;
        $farmers_count = 0;

        switch ($period_range) {
            case PERIOD_RANGE::DAY:
                $today = Carbon::today()->toDateString();
                $yesterday = Carbon::yesterday()->toDateString();

                $farmers_count = DB::table('farmers')
                    ->join('users', 'farmers.user_id', '=', 'users.id')
                    ->whereDate('users.created_at', $today)
                    ->count();

                $previous_farmers_count = DB::table('farmers')
                    ->join('users', 'farmers.user_id', '=', 'users.id')
                    ->whereDate('users.created_at', $yesterday)
                    ->count();
                break;

            case PERIOD_RANGE::WEEK:
                $this_week_start = Carbon::today()->startOfWeek()->toDateString();
                $last_week_start = Carbon::today()->subWeek()->startOfWeek()->toDateString();

                $farmers_count = DB::table('farmers')
                    ->join('users', 'farmers.user_id', '=', 'users.id')
                    ->whereBetween('users.created_at', [$this_week_start, Carbon::today()->toDateString()])
                    ->count();

                $previous_farmers_count = DB::table('farmers')
                    ->join('users', 'farmers.user_id', '=', 'users.id')
                    ->whereBetween('users.created_at', [$last_week_start, $this_week_start])
                    ->count();
                break;

            case PERIOD_RANGE::MONTH:
                $this_month_start = Carbon::today()->startOfMonth()->toDateString();
                $last_month_start = Carbon::today()->subMonth()->startOfMonth()->toDateString();

                $farmers_count = DB::table('farmers')
                    ->join('users', 'farmers.user_id', '=', 'users.id')
                    ->whereBetween('users.created_at', [$this_month_start, Carbon::today()->toDateString()])
                    ->count();

                $previous_farmers_count = DB::table('farmers')
                    ->join('users', 'farmers.user_id', '=', 'users.id')
                    ->whereBetween('users.created_at', [$last_month_start, $this_month_start])
                    ->count();
                break;

            case PERIOD_RANGE::YEAR:
                $this_year_start = Carbon::today()->startOfYear()->toDateString();
                $last_year_start = Carbon::today()->subYear()->startOfYear()->toDateString();

                $farmers_count = DB::table('farmers')
                    ->join('users', 'farmers.user_id', '=', 'users.id')
                    ->whereBetween('users.created_at', [$this_year_start, Carbon::today()->toDateString()])
                    ->count();

                $previous_farmers_count = DB::table('farmers')
                    ->join('users', 'farmers.user_id', '=', 'users.id')
                    ->whereBetween('users.created_at', [$last_year_start, $this_year_start])
                    ->count();
                break;

            case PERIOD_RANGE::ALL:
                // Should never be called (logic remains the same)
                $farmers_count = DB::table('farmers')
                    ->join('users', 'farmers.user_id', '=', 'users.id')
                    ->count();
                return $farmers_count; // No growth rate calculation for ALL
        }

        // Calculate growth rate (avoid division by zero)
        // FIXME: fix the logic for calculating growth rate
        if ($previous_farmers_count > $farmers_count) {
            $farmers_growth_rate = (($previous_farmers_count - $farmers_count) / $previous_farmers_count) * 100;
        } elseif ($previous_farmers_count < $farmers_count) {
            $farmers_growth_rate = (($farmers_count - $previous_farmers_count) / $previous_farmers_count) * 100;
        }

        // echo $farmers_count . "  " . $previous_farmers_count . "  " . $farmers_growth_rate;

        return $farmers_growth_rate;
    }

    public function getMilkDelivered(int $delivery_period = PERIOD_RANGE::DAY)
    {
        $milk_delivered = 0;

        switch ($delivery_period) {
            case PERIOD_RANGE::DAY:
                $today = Carbon::today()->toDateString();
                $milk_delivered = DB::table('milk_delivery')
                    ->whereDate('created_at', $today)
                    ->sum('milk_capacity');
                break;

            case PERIOD_RANGE::WEEK:
                $this_week_start = Carbon::today()->startOfWeek()->toDateString();
                $milk_delivered = DB::table('milk_delivery')
                    ->whereBetween('created_at', [$this_week_start, Carbon::today()->toDateString()])
                    ->sum('milk_capacity');
                break;

            case PERIOD_RANGE::MONTH:
                $this_month_start = Carbon::today()->startOfMonth()->toDateString();
                $milk_delivered = DB::table('milk_delivery')
                    ->whereBetween('created_at', [$this_month_start, Carbon::today()->toDateString()])
                    ->sum('milk_capacity');
                break;

            case PERIOD_RANGE::YEAR:
                $this_year_start = Carbon::today()->startOfYear()->toDateString();
                $milk_delivered = DB::table('milk_delivery')
                    ->whereBetween('created_at', [$this_year_start, Carbon::today()->toDateString()])
                    ->sum('milk_capacity');
                break;

            case PERIOD_RANGE::ALL:
                $milk_delivered = DB::table('milk_delivery')->sum('milk_capacity');
                break;
        }

        return $milk_delivered;
    }

    public function getMilkGrowthRate(int $delivery_period = PERIOD_RANGE::DAY)
    {
        $milk_growth_rate = 0.0;
        $previous_milk_delivered = 0;
        $milk_delivered = 0;

        switch ($delivery_period) {
            case PERIOD_RANGE::DAY:
                $today = Carbon::today()->toDateString();
                $yesterday = Carbon::yesterday()->toDateString();

                $milk_delivered = DB::table('milk_delivery')
                    ->whereDate('created_at', $today)
                    ->sum('milk_capacity');

                $previous_milk_delivered = DB::table('milk_delivery')
                    ->whereDate('created_at', $yesterday)
                    ->sum('milk_capacity');
                break;

            case PERIOD_RANGE::WEEK:
                $this_week_start = Carbon::today()->startOfWeek()->toDateString();
                $last_week_start = Carbon::today()->subWeek()->startOfWeek()->toDateString();

                $milk_delivered = DB::table('milk_delivery')
                    ->whereBetween('created_at', [$this_week_start, Carbon::today()->toDateString()])
                    ->sum('milk_capacity');

                $previous_milk_delivered = DB::table('milk_delivery')
                    ->whereBetween('created_at', [$last_week_start, $this_week_start])
                    ->sum('milk_capacity');
                break;

            case PERIOD_RANGE::MONTH:
                $this_month_start = Carbon::today()->startOfMonth()->toDateString();
                $last_month_start = Carbon::today()->subMonth()->startOfMonth()->toDateString();

                $milk_delivered = DB::table('milk_delivery')
                    ->whereBetween('created_at', [$this_month_start, Carbon::today()->toDateString()])
                    ->sum('milk_capacity');

                $previous_milk_delivered = DB::table('milk_delivery')
                    ->whereBetween('created_at', [$last_month_start, $this_month_start])
                    ->sum('milk_capacity');
                break;

            case PERIOD_RANGE::YEAR:
                $this_year_start = Carbon::today()->startOfYear()->toDateString();
                $last_year_start = Carbon::today()->subYear()->startOfYear()->toDateString();

                $milk_delivered = DB::table('milk_delivery')
                    ->whereBetween('created_at', [$this_year_start, Carbon::today()->toDateString()])
                    ->sum('milk_capacity');

                $previous_milk_delivered = DB::table('milk_delivery')
                    ->whereBetween('created_at', [$last_year_start, $this_year_start])
                    ->sum('milk_capacity');
                break;

            case PERIOD_RANGE::ALL:
                $milk_delivered = DB::table('milk_delivery')->sum('milk_capacity');
                return $milk_delivered; // No growth rate calculation for ALL
        }

        // Calculate growth rate (avoid division by zero)

        if ($previous_milk_delivered > $milk_delivered) {
            $milk_growth_rate = (($previous_milk_delivered - $milk_delivered) / $previous_milk_delivered) * 100;
        } elseif ($previous_milk_delivered < $milk_delivered) {
            $milk_growth_rate = (($milk_delivered - $previous_milk_delivered) / $previous_milk_delivered) * 100;
        }

        // echo $milk_delivered . "  " . $previous_milk_delivered . "  " . $milk_growth_rate;

        return $milk_growth_rate;
    }

    public function getTotalRevenue(int $revenue_period = PERIOD_RANGE::DAY)
    {
        $total_revenue = 0;

        switch ($revenue_period) {
            case PERIOD_RANGE::DAY:
                $today = Carbon::today()->toDateString();
                $total_revenue = DB::table('milk_delivery')
                    ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                    ->whereDate('milk_delivery.created_at', $today)
                    ->sum(DB::raw('milk_capacity * rate'));
                break;

            case PERIOD_RANGE::WEEK:
                $this_week_start = Carbon::today()->startOfWeek()->toDateString();
                $total_revenue = DB::table('milk_delivery')
                    ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                    ->whereBetween('milk_delivery.created_at', [$this_week_start, Carbon::today()->toDateString()])
                    ->sum(DB::raw('milk_capacity * rate'));
                break;

            case PERIOD_RANGE::MONTH:
                $this_month_start = Carbon::today()->startOfMonth()->toDateString();
                $total_revenue = DB::table('milk_delivery')
                    ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                    ->whereBetween('milk_delivery.created_at', [$this_month_start, Carbon::today()->toDateString()])
                    ->sum(DB::raw('milk_capacity * rate'));
                break;

            case PERIOD_RANGE::YEAR:
                $this_year_start = Carbon::today()->startOfYear()->toDateString();
                $total_revenue = DB::table('milk_delivery')
                    ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                    ->whereBetween('milk_delivery.created_at', [$this_year_start, Carbon::today()->toDateString()])
                    ->sum(DB::raw('milk_capacity * rate'));
                break;

            case PERIOD_RANGE::ALL:
                $total_revenue = DB::table('milk_delivery')
                    ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                    ->sum(DB::raw('milk_capacity * rate'));
                break;
        }

        return $total_revenue;
    }

    public function getRevenueGrowthRate(int $revenue_period = PERIOD_RANGE::DAY)
    {
        $revenue_growth_rate = 0.0;
        $previous_revenue = 0;
        $total_revenue = 0;

        switch ($revenue_period) {
            case PERIOD_RANGE::DAY:
                $today = Carbon::today()->toDateString();
                $yesterday = Carbon::yesterday()->toDateString();

                $total_revenue = DB::table('milk_delivery')
                    ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                    ->whereDate('milk_delivery.created_at', $today)
                    ->sum(DB::raw('milk_capacity * rate'));

                $previous_revenue = DB::table('milk_delivery')
                    ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                    ->whereDate('milk_delivery.created_at', $yesterday)
                    ->sum(DB::raw('milk_capacity * rate'));
                break;

            case PERIOD_RANGE::WEEK:
                $this_week_start = Carbon::today()->startOfWeek()->toDateString();
                $last_week_start = Carbon::today()->subWeek()->startOfWeek()->toDateString();

                $total_revenue = DB::table('milk_delivery')
                    ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                    ->whereBetween('milk_delivery.created_at', [$this_week_start, Carbon::today()->toDateString()])
                    ->sum(DB::raw('milk_capacity * rate'));

                $previous_revenue = DB::table('milk_delivery')
                    ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                    ->whereBetween('milk_delivery.created_at', [$last_week_start, $this_week_start])
                    ->sum(DB::raw('milk_capacity * rate'));
                break;

            case PERIOD_RANGE::MONTH:
                $this_month_start = Carbon::today()->startOfMonth()->toDateString();
                $last_month_start = Carbon::today()->subMonth()->startOfMonth()->toDateString();

                $total_revenue = DB::table('milk_delivery')
                    ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                    ->whereBetween('milk_delivery.created_at', [$this_month_start, Carbon::today()->toDateString()])
                    ->sum(DB::raw('milk_capacity * rate'));

                $previous_revenue = DB::table('milk_delivery')
                    ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                    ->whereBetween('milk_delivery.created_at', [$last_month_start, $this_month_start])
                    ->sum(DB::raw('milk_capacity * rate'));
                break;

            case PERIOD_RANGE::YEAR:
                $this_year_start = Carbon::today()->startOfYear()->toDateString();
                $last_year_start = Carbon::today()->subYear()->startOfYear()->toDateString();

                $total_revenue = DB::table('milk_delivery')
                    ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                    ->whereBetween('milk_delivery.created_at', [$this_year_start, Carbon::today()->toDateString()])
                    ->sum(DB::raw('milk_capacity * rate'));

                $previous_revenue = DB::table('milk_delivery')
                    ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                    ->whereBetween('milk_delivery.created_at', [$last_year_start, $this_year_start])
                    ->sum(DB::raw('milk_capacity * rate'));
                break;

            case PERIOD_RANGE::ALL:
                $total_revenue = DB::table('milk_delivery')
                    ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                    ->sum(DB::raw('milk_capacity * rate'));
                return $total_revenue; // No growth rate calculation for ALL
        }

        // Calculate growth rate (avoid division by zero)
        if ($previous_revenue > $total_revenue) {
            $revenue_growth_rate = (($previous_revenue - $total_revenue) / $previous_revenue) * 100;
        } elseif ($previous_revenue < $total_revenue) {
            $revenue_growth_rate = (($total_revenue - $previous_revenue) / $previous_revenue) * 100;
        }

        // echo $total_revenue . "  " . $previous_revenue . "  " . $revenue_growth_rate;

        return $revenue_growth_rate;
    }

    public function getLastPaymentMade(int $payment_period = PERIOD_RANGE::DAY)
    {
        $last_payment = null;

        switch ($payment_period) {
            case PERIOD_RANGE::DAY:
                $last_payment = DB::table('milk_delivery')
                    ->join('farmers', 'milk_delivery.farmer_id', '=', 'farmers.id')
                    ->join('users', 'farmers.user_id', '=', 'users.id')
                    ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                    ->join('cow_breeds', 'rates.breed_id', '=', 'cow_breeds.id')
                    ->select(
                        'users.name AS farmer_name',
                        'cow_breeds.breed AS breed',
                        'rates.rate',
                        'milk_delivery.milk_capacity',
                        'milk_delivery.is_paid',
                        'milk_delivery.had_issues',
                        'milk_delivery.created_at AS last_payment_time'
                    )
                    ->orderBy('milk_delivery.created_at', 'desc')
                    ->first();
                break;

            case PERIOD_RANGE::WEEK:
                $last_payment = DB::table('milk_delivery')
                    ->join('farmers', 'milk_delivery.farmer_id', '=', 'farmers.id')
                    ->join('users', 'farmers.user_id', '=', 'users.id')
                    ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                    ->join('cow_breeds', 'rates.breed_id', '=', 'cow_breeds.id')
                    ->select(
                        'users.name AS farmer_name',
                        'cow_breeds.breed AS breed',
                        'rates.rate',
                        'milk_delivery.milk_capacity',
                        'milk_delivery.is_paid',
                        'milk_delivery.had_issues',
                        'milk_delivery.created_at AS last_payment_time'
                    )
                    ->orderBy('milk_delivery.created_at', 'desc')
                    ->first();
                break;

            case PERIOD_RANGE::MONTH:
                $last_payment = DB::table('milk_delivery')
                    ->join('farmers', 'milk_delivery.farmer_id', '=', 'farmers.id')
                    ->join('users', 'farmers.user_id', '=', 'users.id')
                    ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                    ->join('cow_breeds', 'rates.breed_id', '=', 'cow_breeds.id')
                    ->select(
                        'users.name AS farmer_name',
                        'cow_breeds.breed AS breed',
                        'rates.rate',
                        'milk_delivery.milk_capacity',
                        'milk_delivery.is_paid',
                        'milk_delivery.had_issues',
                        'milk_delivery.created_at AS last_payment_time'
                    )
                    ->orderBy('milk_delivery.created_at', 'desc')
                    ->first();
                break;

            case PERIOD_RANGE::YEAR:
                $last_payment = DB::table('milk_delivery')
                    ->join('farmers', 'milk_delivery.farmer_id', '=', 'farmers.id')
                    ->join('users', 'farmers.user_id', '=', 'users.id')
                    ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                    ->join('cow_breeds', 'rates.breed_id', '=', 'cow_breeds.id')
                    ->select(
                        'users.name AS farmer_name',
                        'cow_breeds.breed AS breed',
                        'rates.rate',
                        'milk_delivery.milk_capacity',
                        'milk_delivery.is_paid',
                        'milk_delivery.had_issues',
                        'milk_delivery.created_at AS last_payment_time'
                    )
                    ->orderBy('milk_delivery.created_at', 'desc')
                    ->first();
                break;

            case PERIOD_RANGE::ALL:
                $last_payment = DB::table('milk_delivery')
                    ->join('farmers', 'milk_delivery.farmer_id', '=', 'farmers.id')
                    ->join('users', 'farmers.user_id', '=', 'users.id')
                    ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                    ->join('cow_breeds', 'rates.breed_id', '=', 'cow_breeds.id')
                    ->select(
                        'users.name AS farmer_name',
                        'cow_breeds.breed AS breed',
                        'rates.rate',
                        'milk_delivery.milk_capacity',
                        'milk_delivery.is_paid',
                        'milk_delivery.had_issues',
                        'milk_delivery.created_at AS last_payment_time'
                    )
                    ->orderBy('milk_delivery.created_at', 'desc')
                    ->first();
                break;
        }

        // print_r($last_payment);
        // die(0);

        return $last_payment;
    }

    public function getFarmersRecords()
    {
        /** 
         * breeds(breed), rates(rate, breed_id), milk_delivery(farmer_id, rate_id, milk_capacity, is_paid, had_issues),
         * farmers(user_id, phone, location, payment_method)
         */
        $farmers_records = DB::table('milk_delivery')
            ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
            ->join('cow_breeds', 'rates.breed_id', '=', 'cow_breeds.id')
            ->join('farmers', 'milk_delivery.farmer_id', '=', 'farmers.id')
            ->join('users', 'farmers.user_id', '=', 'users.id')
            ->select(
                'users.name AS farmer_name',
                'farmers.payment_method',
                'cow_breeds.breed AS breed',
                'rates.rate',
                'milk_delivery.milk_capacity',
                'milk_delivery.is_paid',
                'milk_delivery.had_issues',
                'milk_delivery.created_at AS delivery_time'
            )
            ->get();

        // echo $farmers_records;

        return $farmers_records;
    }

    public function getRecentPayments(int $number_of_results)
    {
        // Get the most recent payments, count the 'farmer_id' and return only unique 'farmer_id' and its count
        $recent_payments = DB::table('milk_delivery')
            ->join('farmers', 'milk_delivery.farmer_id', '=', 'farmers.id')
            ->join('users', 'farmers.user_id', '=', 'users.id')
            ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
            ->join('cow_breeds', 'rates.breed_id', '=', 'cow_breeds.id')
            ->select(
                'milk_delivery.farmer_id',
                'milk_delivery.milk_capacity',
                'milk_delivery.is_paid',
                DB::raw('COUNT(milk_delivery.farmer_id) as total_deliveries'),
                // DB::raw('COUNT(CASE WHEN milk_delivery.is_paid = 1 THEN milk_delivery.farmer_id END) AS paid_deliveries'),
                DB::raw('SUM(had_issues) AS issues'),
                'users.name AS farmer_name',
                'rates.rate',
                'cow_breeds.breed AS breed',
                'milk_delivery.created_at AS last_delivery_time'
            )
            ->groupBy(
                'milk_delivery.farmer_id',
                'milk_delivery.had_issues',
                'milk_delivery.milk_capacity',
                'milk_delivery.is_paid',
                'milk_delivery.created_at',
                'users.name',
                'rates.rate',
                'cow_breeds.breed',
            )->orderBy('milk_delivery.created_at', 'desc')
            ->limit($number_of_results)->get();

        // echo $recent_payments;

        // $recent_payments = DB::table('milk_delivery')->orderBy('created_at', 'desc')->limit($number_of_results)->get();
        return $recent_payments;
    }
}
