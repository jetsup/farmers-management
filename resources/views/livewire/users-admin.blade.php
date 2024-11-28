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
                                        <th> Full Name </th>
                                        <th> Role </th>
                                        <th> Member Since </th>
                                        <th> Verification Status </th>
                                        <th> Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($users) > 0)
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="assets/images/faces/face1.jpg" alt="image" />
                                                        <span class="pl-2">{{ ucwords($user->name) }}</span>
                                                    </div>
                                                </td>
                                                <td> {{ $user->is_admin === 1 ? 'Admin' : 'Farmer' }} </td>
                                                <td> {{ $user->created_at }} </td>
                                                <td>
                                                    <div
                                                        class="badge {{ $user->is_verified ? 'badge-outline-success' : 'badge-outline-danger' }}">
                                                        {{ $user->is_verified ? 'Verified' : 'Not Verified' }}
                                                    </div>
                                                </td>
                                                {{-- <td> {{ ucwords($user->payment_method) }} </td> --}}
                                                <td>
                                                    <div class="badge ">
                                                        <a href="{{ route('convert-user-admin', ['userID' => $user->id]) }}"
                                                            class="btn {{ $user->is_admin === 1 ? 'btn-outline-success' : 'btn-outline-warning' }}">
                                                            {{ $user->is_admin === 1 ? 'Remove Admin' : 'Make Admin' }}
                                                        </a>
                                                    </div>
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
