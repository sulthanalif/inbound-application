@hasrole('Super Admin|Head Warehouse')
<div class="row">
    <!-- Sales Card -->
    <div class="col-xxl-3 col-md-3">
        <div class="card info-card sales-card">
            <div class="card-body">
                <h5 class="card-title">Pending Request <span>Outbound</span></h5>

                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                    <div class="ps-3">
                        <h6>{{ $stats['outbounds']['pending'] }}</h6>
                        {{-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}

                    </div>
                </div>
            </div>
        </div>
    </div><!-- End Sales Card -->
    <div class="col-xxl-3 col-md-3">
        <div class="card info-card sales-card">
            <div class="card-body">
                <h5 class="card-title">Pending Acc Delivery <span>Outbound</span></h5>

                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                    <div class="ps-3">
                        <h6>{{ $stats['outbounds']['accDeliv'] }}</h6>
                        {{-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}

                    </div>
                </div>
            </div>

        </div>
    </div><!-- End Sales Card -->
    <div class="col-xxl-3 col-md-3">
        <div class="card info-card customers-card">
            <div class="card-body">
                <h5 class="card-title">Rejected <span>Outbound</span></h5>

                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div class="ps-3">
                        <h6>{{ $stats['outbounds']['rejected'] }}</h6>
                        {{-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}

                    </div>
                </div>
            </div>

        </div>
    </div><!-- End Sales Card -->

    <!-- Revenue Card -->
    <div class="col-xxl-3 col-md-3">
        <div class="card info-card revenue-card">
            <div class="card-body">
                <h5 class="card-title">Success <span>Outbound</span></h5>

                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="ps-3">
                        <h6>{{ $stats['outbounds']['success'] }}</h6>
                    </div>
                </div>
            </div>

        </div>
    </div><!-- End Revenue Card -->
</div>
<div class="row">
    <div class="col-xxl-4 col-md-4">
        <div class="card info-card sales-card">
            <div class="card-body">
                <h5 class="card-title">Pending <span>Inbound</span></h5>

                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                    <div class="ps-3">
                        <h6>{{ $stats['inbounds']['pending'] }}</h6>
                        {{-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}

                    </div>
                </div>
            </div>

        </div>
    </div><!-- End Sales Card -->
    <div class="col-xxl-4 col-md-4">
        <div class="card info-card customers-card">
            <div class="card-body">
                <h5 class="card-title">Rejected <span>Inbound</span></h5>

                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div class="ps-3">
                        <h6>{{ $stats['inbounds']['rejected'] }}</h6>
                        {{-- <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span> --}}

                    </div>
                </div>
            </div>

        </div>
    </div><!-- End Sales Card -->
    <div class="col-xxl-4 col-md-4">
        <div class="card info-card revenue-card">
            <div class="card-body">
                <h5 class="card-title">Success <span>Inbound</span></h5>

                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-check"></i>
                    </div>
                    <div class="ps-3">
                        <h6>{{ $stats['inbounds']['success'] }}</h6>
                    </div>
                </div>
            </div>

        </div>
    </div><!-- End Revenue Card -->
</div>
@endhasrole
