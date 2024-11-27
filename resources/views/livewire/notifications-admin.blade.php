<div>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Notifications</h4>
                        <div class="row">
                            <div class="col-12">
                                <div class="preview-list">
                                    <div class="preview-item border-bottom">
                                        <div class="preview-thumbnail">
                                            <div class="preview-icon bg-primary">
                                                <i class="mdi mdi-file-document"></i>
                                            </div>
                                        </div>
                                        <div class="preview-item-content d-sm-flex flex-grow">
                                            <div class="flex-grow">
                                                <h6 class="preview-subject">Admin dashboard design</h6>
                                                <p class="text-muted mb-0">Broadcast web app mockup</p>
                                            </div>
                                            <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                                                <p class="text-muted">15 minutes ago</p>
                                                <p class="text-muted mb-0">30 tasks, 5 issues </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-item border-bottom">
                                        <div class="preview-thumbnail">
                                            <div class="preview-icon bg-success">
                                                <i class="mdi mdi-cloud-download"></i>
                                            </div>
                                        </div>
                                        <div class="preview-item-content d-sm-flex flex-grow">
                                            <div class="flex-grow">
                                                <h6 class="preview-subject">Wordpress Development</h6>
                                                <p class="text-muted mb-0">Upload new design</p>
                                            </div>
                                            <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                                                <p class="text-muted">1 hour ago</p>
                                                <p class="text-muted mb-0">23 tasks, 5 issues </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-item border-bottom">
                                        <div class="preview-thumbnail">
                                            <div class="preview-icon bg-info">
                                                <i class="mdi mdi-clock"></i>
                                            </div>
                                        </div>
                                        <div class="preview-item-content d-sm-flex flex-grow">
                                            <div class="flex-grow">
                                                <h6 class="preview-subject">Project meeting</h6>
                                                <p class="text-muted mb-0">New project discussion</p>
                                            </div>
                                            <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                                                <p class="text-muted">35 minutes ago</p>
                                                <p class="text-muted mb-0">15 tasks, 2 issues</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-item border-bottom">
                                        <div class="preview-thumbnail">
                                            <div class="preview-icon bg-danger">
                                                <i class="mdi mdi-email-open"></i>
                                            </div>
                                        </div>
                                        <div class="preview-item-content d-sm-flex flex-grow">
                                            <div class="flex-grow">
                                                <h6 class="preview-subject">Broadcast Mail</h6>
                                                <p class="text-muted mb-0">Sent release details to team</p>
                                            </div>
                                            <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                                                <p class="text-muted">55 minutes ago</p>
                                                <p class="text-muted mb-0">35 tasks, 7 issues </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="preview-item">
                                        <div class="preview-thumbnail">
                                            <div class="preview-icon bg-warning">
                                                <i class="mdi mdi-chart-pie"></i>
                                            </div>
                                        </div>
                                        <div class="preview-item-content d-sm-flex flex-grow">
                                            <div class="flex-grow">
                                                <h6 class="preview-subject">UI Design</h6>
                                                <p class="text-muted mb-0">New application planning</p>
                                            </div>
                                            <div class="mr-auto text-sm-right pt-2 pt-sm-0">
                                                <p class="text-muted">50 minutes ago</p>
                                                <p class="text-muted mb-0">27 tasks, 4 issues </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row justify-content-between">
                            <h4 class="card-title mb-1">Send Notification</h4>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <form class="forms-sample" action="{{ route('notifications-admin') }}" method="POST">
                                    <div class="form-group row">
                                        <label for="dedicated-to" class="col-sm-3 col-form-label">Recipients</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="dedicated-to" name="dedicated-to">
                                                <option value="farmer">Specific Farmer</option>
                                                <option value="client">Specific Client</option>
                                                <option value="farmers">All Farmers</option>
                                                <option value="clients">All Clients</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="send-to" class="col-sm-3 col-form-label">Recipient</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="send-to"
                                                placeholder="Specific Recipient">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="message-title" class="col-sm-3 col-form-label">Title</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="message-title"
                                                name="message-title" placeholder="Title">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="message" class="col-sm-3 col-form-label">Message</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="message" name="message" rows="4"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="attachment" class="col-sm-3 col-form-label">Attachment</label>
                                        <div class="col-sm-9">
                                            <input type="file" class="form-control" id="attachment"
                                                name="attachment">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="send" class="col-sm-3 col-form-label"></label>
                                        <div class="col-sm-9">
                                            <button type="submit" class="btn btn-outline-success mr-2 float-end">Send</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
