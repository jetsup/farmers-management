<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME', "Farmer's") }} - {{ $title }}</title>

    <!-- Vendor CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/jvectormap/jquery-jvectormap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/owl-carousel-2/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/owl-carousel-2/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="assets/css/style.css">
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>
{{-- style="width: 25cm; height: 29.7cm; margin: 0 auto;" --}}

<body class="bg-white text-dark">
    <div class="main-panel">
        <div class="text-center align-content-center">
            <img src="assets/images/logo.svg" alt="logo" class="rounded mx-auto mb-2" width="100px">
            <h4>{{ env('APP_NAME', "Farmer's") }}</h4>
            <h5>{{ env('MAIL_FROM_ADDRESS', 'farmers@email.com') }}</h5>
            <h5><b>Tel:</b> {{ env('OUR_TELEPHONE', '0700000000') }}</h5>
            </br>
            <h5><b>Motto:</b> {{ env('OUR_MOTTO', "Farmer's") }}</h5>
            <h5><b>Vision:</b> {{ env('OUR_VISION', "Farmer's") }}</h5>
        </div>
        <br>
        <div>
            <h3 class="text-center underline">Farmer's Report</h3>
            <hr>
            <br>
            <div class="row" style="align-items: center;">
                <div style="display: inline-block; width: 40%;">
                    <h5><b>Name:</b> {{ $farmer->farmer_name }}</h5>
                    <h5><b>Email:</b> {{ $farmer->email }}</h5>
                    <h5><b>Phone:</b> {{ $farmer->phone }}</h5>
                </div>
                <div style="display: inline-block; width: 40%;margin-left: 15%">
                    <h5><b>Farmer ID:</b> #{{ $farmer->id }}</h5>
                    <h5><b>Address:</b> {{ $farmer->location }}</h5>
                    <h5><b>Verification Status</b> {{ $farmer->is_verified ? 'Verified' : 'Not Verified' }}</h5>
                </div>
            </div>
            <hr>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th> Date </th>
                            <th> Quantity </th>
                            <th> Rate </th>
                            <th> Total Income </th>
                            <th> Payment Status </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($deliveries) > 0)
                            @foreach ($deliveries as $delivery)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="pl-2">{{ ucwords($delivery->delivery_time) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td> {{ $delivery->milk_capacity }} </td>
                                    <td> {{ ucwords($delivery->rate) }} </td>
                                    <td> {{ number_format($delivery->milk_capacity * $delivery->rate, 2, '.', '.') }}
                                    </td>
                                    <td>
                                        <div
                                            class="badge w-100 {{ $delivery->is_paid ? 'badge-outline-success' : 'badge-outline-danger' }}">
                                            {{ $delivery->is_paid ? 'Paid' : 'Payment Pending' }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-danger text-center">No farmers found</td>
                            </tr>
                        @endif
                    </tbody>
                    {{-- footer with total quantity and total amount --}}
                    <tfoot>
                        <tr>
                            <td colspan="1" class="text-left"><b>Total</b></td>
                            <td><b>{{ number_format($total_milk_amount, 0, '.', ',') }}</b></td>
                            <td></td>
                            <td><b>{{ number_format($total_amount, 2, '.', '.') }}</b></td>
                            <td></td>
                        </tr>
                </table>

            </div>

        </div>
</body>

</html>
