<div>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                @if (isset($farmer->total_milk_sold))
                                    <div class="d-flex align-items-center align-self-start">
                                        <h3 class="mb-0">{{ number_format($farmer->total_milk_sold, 0, '.', ',') }}
                                            liters
                                        </h3>
                                        <p
                                            class="{{ $farmer->delivery_rate > 0 ? 'text-success' : 'text-danger' }} ml-2 mb-0 font-weight-medium">
                                            {{ $farmer->delivery_rate }}%</p>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center align-self-start">
                                        <h3 class="mb-0">{{ number_format(0, 0, '.', ',') }}
                                            liters
                                        </h3>
                                        <p class="text-muted ml-2 mb-0 font-weight-medium"> 0.00%</p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-success ">
                                    <span
                                        class="mdi {{ $farmer->delivery_rate > 0 ? 'mdi-arrow-top-right' : 'mdi-arrow-bottom-left' }} icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Milk Sold</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                @if (isset($farmer->total_earnings))
                                    <div class="d-flex align-items-center align-self-start">
                                        <h3 class="mb-0">KES.
                                            {{ number_format($farmer->total_earnings, 2, '.', ',') }}
                                        </h3>
                                        <p
                                            class="{{ $farmer->revenue_rate > 0 ? 'text-success' : 'text-danger' }} ml-2 mb-0 font-weight-medium">
                                            {{ $farmer->revenue_rate }}%
                                        </p>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center align-self-start">
                                        <h3 class="mb-0">KES.
                                            {{ number_format(0, 2, '.', ',') }}
                                        </h3>
                                        <p class="text-muted ml-2 mb-0 font-weight-medium">0.00% </p>
                                    </div>
                                @endif
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-success">
                                    <span class="mdi mdi-arrow-top-right icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Revenue Earned</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                @if (isset($farmer->total_deliveries))
                                    <div class="d-flex align-items-center align-self-start">
                                        <h3 class="mb-0">{{ $farmer->total_deliveries }} made /
                                            {{ $farmer->unpaid_deliveries }} unpaid</h3>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center align-self-start">
                                        <h3 class="mb-0">N/A made /
                                            N/A unpaid</h3>
                                    </div>
                                @endif
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-success ">
                                    <span
                                        class="mdi {{ $farmer->delivery_rate > 0 ? 'mdi-arrow-top-right' : 'mdi-arrow-bottom-left' }} icon-item"></span>
                                </div>
                            </div>
                        </div>
                        
                        <h6 class="text-muted font-weight-normal">Deliveries Made</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                @if (isset($farmer->issues))
                                    <div class="d-flex align-items-center align-self-start">
                                        <h3 class="mb-0">{{ $farmer->issues }}</h3>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center align-self-start">
                                        <h3 class="mb-0">N/A</h3>
                                    </div>
                                @endif
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-success ">
                                    <span class="mdi mdi-arrow-expand icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Issues Raised</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Monthly Transaction History</h4>
                        <canvas id="deliveries-history" class="deliveries-chart"></canvas>
                        <div
                            class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                            @if (isset($farmer->breeds_owned))
                                <div class="text-md-center text-xl-left">
                                    <h6 class="mb-1">Breeds Owned</h6>
                                    <p class="text-muted mb-0">{{ $farmer->breeds_owned }}</p>
                                </div>
                            @else
                                <div class="text-md-center text-xl-left">
                                    <h6 class="mb-1">Breeds Owned</h6>
                                    <p class="text-muted mb-0">N/A</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row justify-content-between">
                            <h4 class="card-title mb-1">Recent Milk Deliveries</h4>
                            <p class="text-muted mb-1">Delivery Time</p>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="preview-list">
                                    @if (count($deliveries) > 0)
                                        @foreach ($deliveries as $delivery)
                                            <div class="preview-item border-bottom">
                                                <div class="preview-thumbnail">
                                                    <div class="preview-icon bg-success">
                                                        <i class="mdi mdi-truck-delivery"></i>
                                                    </div>
                                                </div>
                                                <div class="preview-item-content d-sm-flex flex-grow">
                                                    <div class="flex-grow">
                                                        <h6 class="preview-subject">Delivered
                                                            {{ $delivery->milk_capacity }} litres of
                                                            {{ ucwords($delivery->breed) }}
                                                            milk</h6>
                                                        @if ($delivery->is_paid)
                                                            <p class="mb-0 text-success">Paid KES.
                                                                {{ number_format($delivery->rate * $delivery->milk_capacity, 2, '.', ',') }}
                                                            </p>
                                                        @else
                                                            <p class="mb-0 text-warning">Pending payment of KES.
                                                                {{ number_format($delivery->rate * $delivery->milk_capacity, 2, '.', ',') }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                                                        <p class="text-muted">{{ $delivery->delivery_time }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="preview-item-content d-sm-flex flex-grow">
                                            <div class="flex-grow">
                                                <h6 class="preview-subject text-center text-warning">No deliveries made
                                                    yet</h6>
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
    </div>
</div>
