<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xxl-12">
        <div class="d-flex flex-column h-100">
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="fw-medium text-muted mb-0">PayIn Commission</h5>
                                    <h2 class="mt-4 ff-secondary fw-semibold">₹<span class="counter-value" data-target="{{$agentPayinCommision}}">0</span></h2>
                                </div>
                                <div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-info-subtle rounded-circle fs-2" style="color: #299CDB!important;">₹</span>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div> <!-- end card-->
                </div> <!-- end col-->
                <div class="col-md-4">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="fw-medium text-muted mb-0">PayOut Commission</h5>
                                    <h2 class="mt-4 ff-secondary fw-semibold">₹<span class="counter-value" data-target="{{$agentPayOutCommision}}">0</span></h2>
                                </div>
                                <div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-info-subtle rounded-circle fs-2" style="color: #299CDB!important;">₹</span>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div> <!-- end card-->
                </div> <!-- end col-->
                <div class="col-md-4">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="fw-medium text-muted mb-0">Total Commission</h5>
                                    <h2 class="mt-4 ff-secondary fw-semibold">₹<span class="counter-value" data-target="{{$totalCommission}}">0</span></h2>
                                </div>
                                <div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-info-subtle rounded-circle fs-2" style="color: #299CDB!important;">₹</span>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div> <!-- end card-->
                </div> <!-- end col-->
               
            </div>
        </div>
    </div>
    <div class="col-xxl-12">
        <div class="row h-100">
            <div class="col-xl-6">
                <div class="card card-height-100">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Live Users By Country</h4>
                        <div class="flex-shrink-0">
                            <button type="button" class="btn btn-soft-primary btn-sm">Export Report</button>
                        </div>
                    </div><!-- end card header -->
                    <!-- card body -->
                    <div class="card-body">
                        <div id="users-by-country" data-colors='["--vz-light"]' class="text-center" style="height: 252px"></div>
                        <div class="table-responsive table-card mt-3">
                            <table class="table table-borderless table-sm table-centered align-middle table-nowrap mb-1">
                                <thead class="text-muted border-dashed border border-start-0 border-end-0 bg-light-subtle">
                                    <tr>
                                        <th>Duration (Secs)</th>
                                        <th style="width: 30%;">Sessions</th>
                                        <th style="width: 30%;">Views</th>
                                    </tr>
                                </thead>
                                <tbody class="border-0">
                                    <tr>
                                        <td>0-30</td>
                                        <td>2,250</td>
                                        <td>4,250</td>
                                    </tr>
                                    <tr>
                                        <td>31-60</td>
                                        <td>1,501</td>
                                        <td>2,050</td>
                                    </tr>
                                    <tr>
                                        <td>61-120</td>
                                        <td>750</td>
                                        <td>1,600</td>
                                    </tr>
                                    <tr>
                                        <td>121-240</td>
                                        <td>540</td>
                                        <td>1,040</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
            <div class="col-xl-6">
                <div class="card card-height-100">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Sessions by Countries</h4>
                        <div>
                            <button type="button" class="btn btn-soft-secondary btn-sm">ALL</button>
                            <button type="button" class="btn btn-soft-primary btn-sm">1M</button>
                            <button type="button" class="btn btn-soft-secondary btn-sm">6M</button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div>
                            <div id="countries_charts" data-colors='["--vz-info", "--vz-info", "--vz-info", "--vz-info", "--vz-danger", "--vz-info", "--vz-info", "--vz-info", "--vz-info", "--vz-info"]' class="apex-charts" dir="ltr"></div>
                        </div>
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div> <!-- end col-->
        </div> <!-- end row-->
    </div><!-- end col -->
</div>