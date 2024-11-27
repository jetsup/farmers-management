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
                                        <th> Farmer's Name </th>
                                        <th> Breeds Owned </th>
                                        <th> Payment Mode </th>
                                        <th> Member Since </th>
                                        <th> Verification Status </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($farmers) > 0)
                                        @foreach($farmers as $farmer)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="assets/images/faces/face1.jpg" alt="image" />
                                                        <span class="pl-2">{{ ucwords($farmer->farmer_name) }}</span>
                                                    </div>
                                                </td>
                                                <td> {{ $farmer->breeds_owned }} </td>
                                                <td> {{ ucwords($farmer->payment_method) }} </td>
                                                <td> {{ $farmer->created_at }} </td>
                                                <td>
                                                    <div class="badge {{ $farmer->is_verified ? 'badge-outline-success' : 'badge-outline-danger' }}">{{ $farmer->is_verified ? 'Verified' : 'Not Verified' }}</div>
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
</div>
