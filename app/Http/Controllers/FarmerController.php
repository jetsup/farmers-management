<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\FarmerCows;
use App\Models\MilkDelivery;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

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
    public function registerFarmer(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:15',
            'location' => 'required|string|max:25',
            'payment_method' => 'required|string|max:25',
        ]);

        $farmer = new Farmer();
        $farmer->phone = $request->phone;
        $farmer->location = $request->location;
        $farmer->payment_method = $request->payment_method;
        $farmer->user_id = auth()->user()->id;
        $farmer->save();

        $user = auth()->user();
        $user->is_admin = 2;
        $user->save();

        return redirect()->route('dashboard-farmer');
    }

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
        $farmer = Farmer::where('id', $user_id)
            ->with('user')
            // join with farmer_cows table to get the number of cows owned by the farmer
            ->withCount('cowsCount')
            ->addSelect(DB::raw('(SELECT COUNT(DISTINCT breed_id) FROM farmer_cows WHERE farmer_id = farmers.id) as breeds_owned'))
            ->first();

        if ($farmer) {
            $milk_sold_by_farmer = MilkDelivery::where('farmer_id', '=', $farmer->id)
                ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                ->get();

            if ($milk_sold_by_farmer) {
                $farmer->total_milk_sold = $milk_sold_by_farmer->sum('milk_capacity');
                $farmer->total_earnings = $milk_sold_by_farmer->sum(function ($delivery) {
                    return $delivery->milk_capacity * $delivery->rate;
                });
                $farmer->issues = $milk_sold_by_farmer->sum('had_issues');
                $farmer->total_deliveries = $milk_sold_by_farmer->count();
                $farmer->unpaid_deliveries = $milk_sold_by_farmer->where('is_paid', 0)->count();
            }

            // milk delivery rate of this month as compared to last month and the revenue raised
            $last_month_revenue = 0;
            $this_month_revenue = 0;

            $last_month_delivery_rate_liters = MilkDelivery::
                whereBetween('milk_delivery.created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
                ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                ->get();
            if (count($last_month_delivery_rate_liters) > 0) {
                $last_month_revenue = $last_month_delivery_rate_liters->sum(function ($delivery) {
                    return $delivery->milk_capacity * $delivery->rate;
                });

                $last_month_delivery_rate_liters = $last_month_delivery_rate_liters->sum('milk_capacity');
            } else {
                $last_month_delivery_rate_liters = 0;
            }

            $this_month_delivery_rate_liters = MilkDelivery::
                whereBetween('milk_delivery.created_at', [Carbon::now()->startOfMonth(), Carbon::now()])
                ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
                ->get();
            if (count($this_month_delivery_rate_liters) > 0) {
                $this_month_revenue = $this_month_delivery_rate_liters->sum(function ($delivery) {
                    return $delivery->milk_capacity * $delivery->rate;
                });

                $this_month_delivery_rate_liters = $this_month_delivery_rate_liters->sum('milk_capacity');
            } else {
                $this_month_delivery_rate_liters = 0;
            }
            // dd($last_month_delivery_rate_liters, $this_month_delivery_rate_liters);
            $delivery_divisor = $last_month_delivery_rate_liters == 0 ? 1 : $last_month_delivery_rate_liters;
            $delivery_rate = (($this_month_delivery_rate_liters - $last_month_delivery_rate_liters) / $delivery_divisor) * 100;

            if ($delivery_rate > 100) {
                $delivery_rate = 100;
            } else if ($delivery_rate < 0) {
                if ($delivery_rate < -100) {
                    $delivery_rate = -100;
                } else {
                    $delivery_rate = $delivery_rate * -1;
                }
            }
            $farmer->delivery_rate = $delivery_rate;


            $revenue_divisor = $last_month_revenue == 0 ? 1 : $last_month_revenue;
            $revenue_rate = (($this_month_revenue - $last_month_revenue) / $revenue_divisor) * 100;

            if ($revenue_rate > 100) {
                $revenue_rate = 100;
            } else if ($revenue_rate < 0) {
                if ($revenue_rate < -100) {
                    $revenue_rate = -100;
                } else {
                    $revenue_rate = $revenue_rate * -1;
                }
            }
            $farmer->revenue_rate = $revenue_rate;

            // dd($farmer);
        }

        return $farmer;
    }

    public function getFarmersDeliveries(int $user_id, int $limit = 0)
    {
        /*
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
            */

        $deliveries = MilkDelivery::where('farmer_id', $user_id)
            ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
            ->join('cow_breeds', 'rates.breed_id', '=', 'cow_breeds.id')
            ->select(
                'cow_breeds.breed AS breed',
                'rates.rate',
                'milk_delivery.milk_capacity',
                'milk_delivery.is_paid',
                'milk_delivery.had_issues',
                'milk_delivery.created_at AS delivery_time'
            )
            ->when($limit > 0, function ($query) use ($limit) {
                return $query->limit($limit);
            })
            ->get();

        // dd($deliveries);

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
        } elseif ($previous_farmers_count < $farmers_count && $previous_farmers_count != 0) {
            $farmers_growth_rate = (($farmers_count - $previous_farmers_count) / $previous_farmers_count) * 100;
        }

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
        $milk_received_last_month = MilkDelivery::whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])->sum('milk_capacity');
        $milk_received_this_month = MilkDelivery::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()])->sum('milk_capacity');

        $divisor = $milk_received_last_month == 0 ? 1 : $milk_received_last_month;
        $milk_growth_rate = (($milk_received_this_month - $milk_received_last_month) / $divisor) * 100;

        // dd($milk_growth_rate);

        if ($milk_growth_rate > 100) {
            $milk_growth_rate = 100;
        } else if ($milk_growth_rate < 0) {
            if ($milk_growth_rate < -100) {
                $milk_growth_rate = -100;
            } else {
                $milk_growth_rate = $milk_growth_rate * -1;
            }
        }

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
        $money_raised_from_last_month_milk = MilkDelivery::whereBetween('milk_delivery.created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()])
            ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
            ->sum(DB::raw('milk_capacity * rate'));

        $money_raised_from_this_month_milk = MilkDelivery::whereBetween('milk_delivery.created_at', [Carbon::now()->startOfMonth(), Carbon::now()])
            ->join('rates', 'milk_delivery.rate_id', '=', 'rates.id')
            ->sum(DB::raw('milk_capacity * rate'));

        // dd($money_raised_from_last_month_milk, $money_raised_from_this_month_milk);

        $divisor = $money_raised_from_last_month_milk == 0 ? 1 : $money_raised_from_last_month_milk;
        $revenue_growth_rate = (($money_raised_from_this_month_milk - $money_raised_from_last_month_milk) / $divisor) * 100;

        if ($revenue_growth_rate > 100) {
            $revenue_growth_rate = 100;
        } else if ($revenue_growth_rate < 0) {
            if ($revenue_growth_rate < -100) {
                $revenue_growth_rate = -100;
            } else {
                $revenue_growth_rate = $revenue_growth_rate * -1;
            }
        }

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
                'milk_delivery.id',
                'milk_delivery.milk_capacity',
                'milk_delivery.is_paid',
                'milk_delivery.had_issues',
                'milk_delivery.created_at AS delivery_time'
            )
            ->get();

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

        // $recent_payments = DB::table('milk_delivery')->orderBy('created_at', 'desc')->limit($number_of_results)->get();
        return $recent_payments;
    }
}
