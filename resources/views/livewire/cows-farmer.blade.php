<div>
    <div class="content-wrapper">
        <div class="row ">
            <div class="col-8 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Registered Cows</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th> Registration Date </th>
                                        <th> ID </th>
                                        <th> Name </th>
                                        <th> Breed </th>
                                        <th> Action </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($cows) > 0)
                                        @foreach ($cows as $cow)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="pl-2">{{ ucwords($cow->created_at) }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td> {{ $cow->id }} </td>
                                                <td> {{ ucwords($cow->name) }} </td>
                                                <td> {{ ucwords($cow->breed) }} </td>
                                                
                                                <td>
                                                    <button class="btn btn-outline-danger btn-sm"
                                                        wire:click="deleteCow({{ $cow->id }})">Delete
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-danger text-center">No cows registered</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Register Cow</h4>
                        <form class="form-sample" wire:submit.prevent="addCow">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Breed</label>
                                        <select class="form-control col-sm-8" name="breed" wire:model="breed">
                                            <option value="">Select Breed</option>
                                            @foreach ($breeds as $breed)
                                                <option value="{{ $breed->id }}">{{ ucwords($breed->breed) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Name</label>
                                        <input type="text" class="form-control col-sm-8" placeholder="Name"
                                            wire:model="name">
                                        @error('name')
                                            <span class="text-danger text-center">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-9 offset-3">
                                            <button type="submit" class="btn btn-success mr-2">Register</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
    </div>
</div>
