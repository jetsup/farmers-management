<div>
    <script>
        function formatTimeDifference(datetimeStr) {
            const datetimeObj = new Date(datetimeStr);
            const now = new Date();
            const diffMs = now - datetimeObj;

            const seconds = Math.floor(diffMs / 1000);
            const minutes = Math.floor(seconds / 60);
            const hours = Math.floor(minutes / 60);
            const days = Math.floor(hours / 24);

            if (days > 1) {
                return `${days} days ago`;
            } else if (days === 1) {
                return "Yesterday";
            } else if (hours > 0) {
                return `${hours} hour${hours > 1 ? 's' : ''} ago`;
            } else if (minutes > 0) {
                return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
            } else {
                return "Just now";
            }
        }

        // Example usage:
        const datetimeStr = "2024-11-27 10:45:19";
        const formattedTime = formatTimeDifference(datetimeStr);
        console.log(formattedTime); // Output: 1 day ago (assuming today is 2024-11-28)
    </script>

    <div class="content-wrapper">
        <div class="row">
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0">{{ number_format($total_farmers, 0, '.', ',') }}</h3>
                                    @if ($farmers_growth_rate < 0)
                                        <p class="text-danger ml-2 mb-0 font-weight-medium">{{ $farmers_growth_rate }}%
                                        </p>
                                    @elseif($farmers_growth_rate > 0)
                                        <p class="text-success ml-2 mb-0 font-weight-medium">
                                            +{{ $farmers_growth_rate }}%</p>
                                    @else
                                        <p class="text-muted ml-2 mb-0 font-weight-medium">--</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-success ">
                                    <span class="mdi mdi-arrow-top-right icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Total Farmers</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0">KES. {{ number_format($total_revenue, 2, '.', ',') }}</h3>
                                    @if ($revenue_growth_rate < 0)
                                        <p class="text-danger ml-2 mb-0 font-weight-medium">{{ $revenue_growth_rate }}%
                                        </p>
                                    @elseif($revenue_growth_rate > 0)
                                        <p class="text-success ml-2 mb-0 font-weight-medium">
                                            +{{ $revenue_growth_rate }}%</p>
                                    @else
                                        <p class="text-muted ml-2 mb-0 font-weight-medium">--</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-success">
                                    <span class="mdi mdi-arrow-top-right icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Current Revenue</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0">{{ number_format($daily_milk, 0, '.', ',') }} Liters</h3>
                                    @if ($daily_milk_growth_rate < 0)
                                        <p class="text-danger ml-2 mb-0 font-weight-medium">
                                            {{ $daily_milk_growth_rate }}%
                                        </p>
                                    @elseif($daily_milk_growth_rate > 0)
                                        <p class="text-success ml-2 mb-0 font-weight-medium">
                                            +{{ $daily_milk_growth_rate }}%</p>
                                    @else
                                        <p class="text-muted ml-2 mb-0 font-weight-medium">--</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-danger">
                                    <span class="mdi mdi-arrow-bottom-left icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Daily Reception</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0">KES. {{ number_format($total_expenses, 2, '.', ',') }}</h3>
                                    @if ($expenses_growth_rate < 0)
                                        <p class="text-danger ml-2 mb-0 font-weight-medium">
                                            {{ $expenses_growth_rate }}%
                                        </p>
                                    @elseif($expenses_growth_rate > 0)
                                        <p class="text-success ml-2 mb-0 font-weight-medium">
                                            +{{ $expenses_growth_rate }}%</p>
                                    @else
                                        <p class="text-muted ml-2 mb-0 font-weight-medium">--</p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-success ">
                                    <span class="mdi mdi-arrow-top-right icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Current Expense</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Monthly Transaction History</h4>
                        <canvas id="transaction-history" class="transaction-chart"></canvas>
                        <div
                            class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                            <div class="text-md-center text-xl-left">
                                <h6 class="mb-1">Farmers Payment</h6>
                                <p class="text-muted mb-0">{{ $last_farmer_paid_at }}</p>
                            </div>
                            <div
                                class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                                <h6 class="font-weight-bold mb-0">
                                    {{ number_format($monthly_farmers_payments, 2, '.', ',') }}</h6>
                            </div>
                        </div>
                        <div
                            class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                            <div class="text-md-center text-xl-left">
                                <h6 class="mb-1">Revenue Received</h6>
                                <p class="text-muted mb-0">{{ $last_revenue_received_at }}</p>
                            </div>
                            <div
                                class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                                <h6 class="font-weight-bold mb-0">{{ number_format($monthly_revenue, 2, '.', ',') }}
                                </h6>
                            </div>
                        </div>
                        <div
                            class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                            <div class="text-md-center text-xl-left">
                                <h6 class="mb-1">Expenses Paid</h6>
                                <p class="text-muted mb-0">{{ $last_expense_paid_at }}</p>
                            </div>
                            <div
                                class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                                <h6 class="font-weight-bold mb-0">{{ number_format($monthly_expenses, 2, '.', ',') }}
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row justify-content-between">
                            <h4 class="card-title mb-1">Recent Milk Deliveries</h4>
                            <p class="text-muted mb-1">status</p>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="preview-list">

                                    @if (count($recent_farmers_payments) > 0)
                                        @foreach ($recent_farmers_payments as $payment)
                                            <div class="preview-item border-bottom">
                                                <div class="preview-thumbnail">
                                                    <div class="preview-icon bg-success">
                                                        <i class="mdi mdi-truck-delivery"></i>
                                                    </div>
                                                </div>
                                                <div class="preview-item-content d-sm-flex flex-grow">
                                                    <div class="flex-grow">
                                                        <h6 class="preview-subject">{{ $payment->farmer_name }}
                                                            delivered
                                                            {{ $payment->milk_capacity }} litres of
                                                            {{ $payment->breed }}
                                                            milk
                                                        </h6>
                                                        <p
                                                            class="mb-0 {{ $payment->is_paid ? 'text-success' : 'text-warning' }}">
                                                            {{ $payment->is_paid ? 'Paid' : 'Awaiting payment of ' }}
                                                            KES.
                                                            {{ number_format($payment->milk_capacity * $payment->rate, 2, '.', ',') }}
                                                        </p>
                                                    </div>
                                                    <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                                                        <p class="text-muted" value="JJ">{{ $payment->last_delivery_time }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="preview-thumbnail">
                                            <div class="preview-icon bg-primary">
                                                <i class="mdi mdi-account"></i>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row">
            <div class="col-sm-4 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h5>Revenue</h5>
                        <div class="row">
                            <div class="col-8 col-sm-12 col-xl-8 my-auto">
                                <div class="d-flex d-sm-block d-md-flex align-items-center">
                                    <h2 class="mb-0">$32123</h2>
                                    <p class="text-success ml-2 mb-0 font-weight-medium">+3.5%</p>
                                </div>
                                <h6 class="text-muted font-weight-normal">11.38% Since last month</h6>
                            </div>
                            <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                                <i class="icon-lg mdi mdi-codepen text-primary ml-auto"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h5>Sales</h5>
                        <div class="row">
                            <div class="col-8 col-sm-12 col-xl-8 my-auto">
                                <div class="d-flex d-sm-block d-md-flex align-items-center">
                                    <h2 class="mb-0">$45850</h2>
                                    <p class="text-success ml-2 mb-0 font-weight-medium">+8.3%</p>
                                </div>
                                <h6 class="text-muted font-weight-normal"> 9.61% Since last month</h6>
                            </div>
                            <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                                <i class="icon-lg mdi mdi-wallet-travel text-danger ml-auto"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h5>Purchase</h5>
                        <div class="row">
                            <div class="col-8 col-sm-12 col-xl-8 my-auto">
                                <div class="d-flex d-sm-block d-md-flex align-items-center">
                                    <h2 class="mb-0">$2039</h2>
                                    <p class="text-danger ml-2 mb-0 font-weight-medium">-2.1% </p>
                                </div>
                                <h6 class="text-muted font-weight-normal">2.27% Since last month</h6>
                            </div>
                            <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                                <i class="icon-lg mdi mdi-monitor text-success ml-auto"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</div>
