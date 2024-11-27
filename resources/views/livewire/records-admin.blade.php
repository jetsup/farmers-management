<div>
    <div class="content-wrapper">
        <div class="row ">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Farmers Payment Records</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th> Farmer Name </th>
                                        <th> Cow Breed </th>
                                        <th> Quantity </th>
                                        <th> Rate </th>
                                        <th> Income </th>
                                        <th> Time </th>
                                        <th> Status </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($farmers_records) > 0)
                                        @foreach ($farmers_records as $record)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="assets/images/faces/face1.jpg" alt="image" />
                                                        <span class="pl-2">{{ $record->farmer_name }}</span>
                                                    </div>
                                                </td>
                                                <td> {{ $record->breed }} </td>
                                                <td> {{ number_format($record->milk_capacity, 0, '.', ',') }} </td>
                                                <td> {{ $record->rate }} </td>
                                                <td> {{ number_format($record->rate * $record->milk_capacity, 2, '.', ',') }}
                                                </td>
                                                <td> {{ $record->delivery_time }} </td>
                                                <td>
                                                    <div class="badge {{ $record->is_paid ? 'badge-outline-success' : 'badge-outline-warning' }}">
                                                        {{ $record->is_paid ? 'Paid' : 'Not Paid' }}</div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7">
                                                <div class="alert text-danger text-center" role="alert">
                                                    No records found
                                                </div>
                                            </td>
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
</div>
