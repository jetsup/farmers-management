<div>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-7 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row justify-content-between">
                            <h4 class="card-title mb-1">Milk Rate</h4>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <form class="forms-sample" wire:submit.prevent="createRate">
                                    <div class="form-group row">
                                        <label for="breed" class="col-sm-3 col-form-label">Breed</label>
                                        <div class="col-sm-9">
                                            <select type="text" class="form-control" id="breed" name="breed"
                                                required wire:model="breed">
                                                <option value="">Select Breed</option>
                                                @if (count($breeds) > 0)
                                                    @foreach ($breeds as $breed)
                                                        <option value="{{ $breed->id }}">
                                                            {{ ucwords($breed->breed) }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="rate" class="col-sm-3 col-form-label">Rate</label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" id="rate" name="rate"
                                                placeholder="Price Per Liter" required wire:model="rate">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="send" class="col-sm-3 col-form-label"></label>
                                        <div class="col-sm-9">
                                            <button type="submit"
                                                class="btn btn-outline-success mr-2 float-end">Create</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Milk Rates</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="preview-list">
                                    @if (count($rates) > 0)
                                        @foreach ($rates as $rate)
                                            <div class="preview-item border-bottom">
                                                <div class="preview-thumbnail">
                                                    <div class="preview-icon bg-primary">
                                                        <i class="mdi mdi-account"></i>
                                                    </div>
                                                </div>
                                                <div class="preview-item-content d-sm-flex flex-grow">
                                                    <div class="flex-grow">
                                                        <h6 class="preview-subject">KES. {{ ucwords($rate->rate) }} per
                                                            liter</h6>
                                                        <p class="text-muted mb-0">{{ ucwords($rate->breed) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="preview-item border-bottom">
                                            <div class="preview-item-content d-sm-flex flex-grow">
                                                <div class="flex-grow">
                                                    <h6 class="preview-subject text-center text-warning">No rates set
                                                    </h6>
                                                </div>
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
