<div>
    <div class="content-wrapper">
        <div class="row ">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Farmers</h4>
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
                                                <td> {{ number_format($delivery->milk_capacity * $delivery->rate, 2, '.', ',') }}
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
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <button class="btn rounded-75 btn-outline-success" style="position: fixed;top: 120px;right: 70px;z-index: 999;">
            <a href="{{ route('reports-farmer-download') }}" class="text-decoration-none text-success">
                <i class="mdi mdi-download"></i> Download Your Reports
            </a>
        </button>
    </div>
</div>
