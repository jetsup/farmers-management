<div>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-7 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row justify-content-between">
                            <h4 class="card-title mb-1">Receive Milk</h4>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <form class="forms-sample" wire:submit.prevent="receiveMilk">
                                    <div class="form-group row">
                                        <label for="rate" class="col-sm-4 col-form-label">Rate</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="rate" name="rate" required
                                                wire:model="rate">
                                                <option value="">Select Breed</option>
                                                @if (count($rates) > 0)
                                                    @foreach ($rates as $rate)
                                                        <option value="{{ $rate->id }}">
                                                            {{ ucwords($rate->breed) . ' - (KES. ' . $rate->rate . ')' }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="farmer-id" class="col-sm-4 col-form-label">Farmer ID</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" id="farmer-id" name="farmer-id"
                                                placeholder="Specific Recipient" wire:model="farmer_id">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="milk-capacity" class="col-sm-4 col-form-label">Quantity
                                            (litres)</label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" id="milk-capacity"
                                                name="milk-capacity" placeholder="Specific Recipient"
                                                wire:model="milk_capacity">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="is-paid" class="col-sm-4 col-form-label">Payment</label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="is-paid" name="is-paid"
                                                wire:model="is_paid" required>
                                                <option value="">Select Payment Status</option>
                                                <option value="1">Not Paid</option>
                                                <option value="2">Paid</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="send" class="col-sm-3 col-form-label"></label>
                                        <div class="col-sm-9">
                                            <button type="submit"
                                                class="btn btn-outline-success mr-2 float-end">Add</button>
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
                        <h4 class="card-title">Reception Updates</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="preview-list">
                                    @if (count($milk_deliveries) > 0)
                                        @foreach ($milk_deliveries as $milk_delivery)
                                            <div class="preview-item border-bottom">
                                                <div class="preview-thumbnail">
                                                    <div class="preview-icon bg-primary">
                                                        <i class="mdi mdi-account"></i>
                                                    </div>
                                                </div>
                                                <div class="preview-item-content d-sm-flex flex-grow">
                                                    <div class="flex-grow">
                                                        <h6 class="preview-subject">{{ $milk_delivery->farmer_name }}
                                                        </h6>
                                                        <p class="text-muted mb-0">{{ $milk_delivery->milk_capacity }}
                                                            litres ({{ $milk_delivery->breed_name }})</p>
                                                    </div>
                                                    <div class="mr-auto text-sm-right text-center">
                                                        <p class="text-muted mb-0">
                                                            {{ date('d-m-Y', strtotime($milk_delivery->created_at)) }}
                                                        </p>
                                                        <p class="text-muted mb-0">
                                                            {{ date('H:i', strtotime($milk_delivery->created_at)) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="preview-item border-bottom">
                                            <div class="preview-item-content d-sm-flex flex-grow">
                                                <div class="flex-grow">
                                                    <h6 class="preview-subject text-center text-warning">No Milk
                                                        Deliveries</h6>
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
