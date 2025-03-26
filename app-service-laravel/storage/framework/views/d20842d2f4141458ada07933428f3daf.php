

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.horizontal'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(URL::asset('build/libs/jsvectormap/jsvectormap.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>


    <div class="row">
        <div class="col-xxl-5">
            <div class="d-flex flex-column h-100">
                <div class="row h-100">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="alert alert-info border-0 rounded-0 m-0 d-flex align-items-center"
                                    role="alert">
                                    
                                    <div class="flex-grow-1 text-truncate">
                                        Vaš trenutni paket je <b>Starter</b>
                                    </div>
                                    <div class="flex-shrink-0">
                                    <button type="button" class="btn btn-soft-info btn-sm shadow-none pt-1 pb-1">
                                    Unaprijedi paket
                                </button>
                                    </div>
                                </div>

                                <div class="row align-items-end">
                                    <div class="col-sm-8">
                                        <div class="p-3">
                                            <p class="fs-16 lh-base">Dobro došli <span class="fw-semibold"><?php echo e(Auth::user()->name); ?>,
                                                    </span> <br> na platformu eDEKLARANT!</p>
                                            <div class="mt-3">
                                                <a href="pages-pricing" class="btn btn-info"><img style="margin-top: -2px; margin-right: 3px;" src="<?php echo e(URL::asset('build/images/svg-icons/scan.svg')); ?>"
                                                >Smart Scan
                                                    </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="px-2">
                                            <img src="<?php echo e(URL::asset('build/images/user-illustarator-1.png')); ?>"
                                                class="img-fluid" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end card-body-->
                        </div>
                    </div> <!-- end col-->
                </div> <!-- end row-->

                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="fw-medium text-muted mb-0">Dostupna skeniranja</p>
                                        <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                                data-target="28">0</span></h2>
                                        <p class="mb-0 text-muted"><span class="badge bg-light text-success mb-0">
                                                <i class="ri-arrow-up-line align-middle"></i> 16.24 %
                                            </span> vs. previous month</p>
                                    </div>
                                    <div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                            <img src="<?php echo e(URL::asset('build/images/svg-icons/scan-info.svg')); ?>" class="img-fluid"
                                            alt="">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div> <!-- end card-->
                    </div> <!-- end col-->

                    <div class="col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="fw-medium text-muted mb-0">Broj skeniranih faktura</p>
                                        <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                                data-target="27.66">0</span>k</h2>
                                        <p class="mb-0 text-muted"><span class="badge bg-light text-danger mb-0">
                                                <i class="ri-arrow-down-line align-middle"></i> 3.96 %
                                            </span> vs. previous month</p>
                                    </div>
                                    <div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                            <i data-feather="file-text" class="text-info"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                </div> <!-- end row-->

                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="fw-medium text-muted mb-0">Prosječno vrijeme scana</p>
                                        <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                                data-target="0">0</span>m
                                            <span class="counter-value" data-target="47">0</span>sec
                                        </h2>
                                        <p class="mb-0 text-muted"><span class="badge bg-light text-danger mb-0">
                                                <i class="ri-arrow-down-line align-middle"></i> 0.24 %
                                            </span> vs. previous month</p>
                                    </div>
                                    <div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                <i data-feather="clock" class="text-info"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div> <!-- end card-->
                    </div> <!-- end col-->

                    <div class="col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="fw-medium text-muted mb-0">Broj dobavljača</p>
                                        <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value"
                                                data-target="33.48">0</span>%</h2>
                                        <p class="mb-0 text-muted"><span class="badge bg-light text-success mb-0">
                                                <i class="ri-arrow-up-line align-middle"></i> 7.05 %
                                            </span> vs. previous month</p>
                                    </div>
                                    <div>
                                        <div class="avatar-sm flex-shrink-0">
                                            <span class="avatar-title bg-info-subtle rounded-circle fs-2">
                                                <i data-feather="truck" class="text-info"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div> <!-- end card-->
                    </div> <!-- end col-->
                </div> <!-- end row-->
            </div>
        </div> <!-- end col-->

        <div class="col-xxl-7">
            <div class="row h-100">
                <div class="col-xl-6">
                    <div class="card card-height-100">
                    <div class="alert alert-info border-0 rounded-0 m-0 d-flex align-items-center"
                                    role="alert">
                                    
                                    <div class="flex-grow-1 text-truncate">
                                        Zemlje svijeta sa kojim <b>najviše surađujem</b>
                                    </div>
                                    <div class="flex-shrink-0">
                                    <button type="button" class="btn btn-soft-info btn-sm shadow-none pt-1 pb-1">
                                    Vidi sve
                                </button>
                                    </div>
                                </div>
                     

                        <!-- card body -->
                        <div class="card-body">

                            <div id="users-by-country" data-colors='["--vz-light"]' class="text-center"
                                style="height: 252px"></div>

                            <div class="table-responsive table-card mt-3">
                                <table
                                    class="table table-borderless table-sm table-centered align-middle table-nowrap mb-1">
                                    <thead
                                        class="text-muted border-dashed border border-start-0 border-end-0 bg-light-subtle">
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
                    <div class="alert alert-info border-0 rounded-0 m-0 d-flex align-items-center"
                                    role="alert">
                                    
                                    <div class="flex-grow-1 text-truncate">
                                        Kompanije sa kojim <b>najviše surađujem</b>
                                    </div>
                                    <div class="flex-shrink-0">
                                    <button type="button" class="btn btn-soft-info btn-sm shadow-none pt-1 pb-1">
                                    Vidi sve
                                </button>
                                    </div>
                                </div>
                        <div class="card-body p-0">
                            <div>
                                <div id="countries_charts"
                                    data-colors='["--vz-info", "--vz-info", "--vz-info", "--vz-info", "--vz-danger", "--vz-info", "--vz-info", "--vz-info", "--vz-info", "--vz-info"]'
                                    class="apex-charts" dir="ltr"></div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div> <!-- end col-->

            </div> <!-- end row-->
        </div><!-- end col -->
    </div> <!-- end row-->

    <div class="row">
        <div class="col-xl-12">
            <div class="card crm-widget">
                <div class="card-body p-0">
                    <div class="row row-cols-xxl-5 row-cols-md-3 row-cols-1 g-0">
                        <div class="col">
                            <div class="py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">Campaign Sent <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i></h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ri-space-ship-line display-6 text-muted"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0"><span class="counter-value" data-target="197">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col">
                            <div class="mt-3 mt-md-0 py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">Annual Profit <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i></h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ri-exchange-dollar-line display-6 text-muted"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0">$<span class="counter-value" data-target="489.4">0</span>k</h2>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col">
                            <div class="mt-3 mt-md-0 py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">Lead Conversation <i class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i></h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ri-pulse-line display-6 text-muted"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0"><span class="counter-value" data-target="32.89">0</span>%</h2>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col">
                            <div class="mt-3 mt-lg-0 py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">Daily Average Income <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i></h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ri-trophy-line display-6 text-muted"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0">$<span class="counter-value" data-target="1596.5">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col">
                            <div class="mt-3 mt-lg-0 py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">Annual Deals <i class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i></h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ri-service-line display-6 text-muted"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0"><span class="counter-value" data-target="2659">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                    </div><!-- end row -->
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
    
    <div class="row">
        <div class="col-lg-12">
            <div class="swiper cryptoSlider">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="card">
                            <div class="card-body">
                                <div class="float-end">
                                    <div class="dropdown">
                                        <a class="text-reset" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <span class="text-muted fs-18"><i class="mdi mdi-dots-horizontal"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Details</a>
                                            <a class="dropdown-item" href="#">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo e(URL::asset('build/images/svg/crypto-icons/btc.svg')); ?>"
                                        class="bg-light rounded-circle p-1 avatar-xs img-fluid shadow" alt="">
                                    <h6 class="ms-2 mb-0 fs-14">Bitcoin</h6>
                                </div>
                                <div class="row align-items-end g-0">
                                    <div class="col-6">
                                        <h5 class="mb-1 mt-4">$1,523,647</h5>
                                        <p class="text-success fs-13 fw-medium mb-0">+13.11%<span
                                                class="text-muted ms-2 fs-10 text-uppercase">(btc)</span>
                                        </p>
                                    </div><!-- end col -->
                                    <div class="col-6">
                                        <div class="apex-charts crypto-widget"
                                            data-colors='["--vz-success" , "--vz-transparent"]'
                                            id="bitcoin_sparkline_charts" dir="ltr"></div>
                                    </div><!-- end col -->
                                </div><!-- end row -->
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end -->

                    <div class="swiper-slide">
                        <div class="card">
                            <div class="card-body">
                                <div class="float-end">
                                    <div class="dropdown">
                                        <a class="text-reset" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <span class="text-muted fs-18"><i class="mdi mdi-dots-horizontal"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Details</a>
                                            <a class="dropdown-item" href="#">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo e(URL::asset('build/images/svg/crypto-icons/ltc.svg')); ?>"
                                        class="bg-light rounded-circle p-1 avatar-xs img-fluid shadow" alt="">
                                    <h6 class="ms-2 mb-0 fs-14">Litecoin</h6>
                                </div>
                                <div class="row align-items-end g-0">
                                    <div class="col-6">
                                        <h5 class="mb-1 mt-4">$2,145,687</h5>
                                        <p class="text-success fs-13 fw-medium mb-0">+15.08%<span
                                                class="text-muted ms-2 fs-10 text-uppercase">(ltc)</span>
                                        </p>
                                    </div><!-- end col -->
                                    <div class="col-6">
                                        <div class="apex-charts crypto-widget"
                                            data-colors='["--vz-success", "--vz-transparent"]'
                                            id="litecoin_sparkline_charts" dir="ltr"></div>
                                    </div><!-- end col -->
                                </div><!-- end row -->
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end -->

                    <div class="swiper-slide">
                        <div class="card">
                            <div class="card-body">
                                <div class="float-end">
                                    <div class="dropdown">
                                        <a class="text-reset" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <span class="text-muted fs-18"><i class="mdi mdi-dots-horizontal"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Details</a>
                                            <a class="dropdown-item" href="#">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo e(URL::asset('build/images/svg/crypto-icons/etc.svg')); ?>"
                                        class="bg-light rounded-circle p-1 avatar-xs img-fluid shadow" alt="">
                                    <h6 class="ms-2 mb-0 fs-14">Eathereum</h6>
                                </div>
                                <div class="row align-items-end g-0">
                                    <div class="col-6">
                                        <h5 class="mb-1 mt-4">$3,312,870</h5>
                                        <p class="text-success fs-13 fw-medium mb-0">+08.57%<span
                                                class="text-muted ms-2 fs-10 text-uppercase">(etc)</span>
                                        </p>
                                    </div><!-- end col -->
                                    <div class="col-6">
                                        <div class="apex-charts crypto-widget"
                                            data-colors='["--vz-success", "--vz-transparent"]'
                                            id="eathereum_sparkline_charts" dir="ltr"></div>
                                    </div><!-- end col -->
                                </div><!-- end row -->
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end -->

                    <div class="swiper-slide">
                        <div class="card">
                            <div class="card-body">
                                <div class="float-end">
                                    <div class="dropdown">
                                        <a class="text-reset" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <span class="text-muted fs-18"><i class="mdi mdi-dots-horizontal"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Details</a>
                                            <a class="dropdown-item" href="#">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo e(URL::asset('build/images/svg/crypto-icons/bnb.svg')); ?>"
                                        class="bg-light rounded-circle p-1 avatar-xs img-fluid shadow" alt="">
                                    <h6 class="ms-2 mb-0 fs-14">Binance</h6>
                                </div>
                                <div class="row align-items-end g-0">
                                    <div class="col-6">
                                        <h5 class="mb-1 mt-4">$1,820,045</h5>
                                        <p class="text-danger fs-13 fw-medium mb-0">-09.21%<span
                                                class="text-muted ms-2 fs-10 text-uppercase">(bnb)</span>
                                        </p>
                                    </div><!-- end col -->
                                    <div class="col-6">
                                        <div class="apex-charts crypto-widget"
                                            data-colors='["--vz-danger", "--vz-transparent"]' id="binance_sparkline_charts"
                                            dir="ltr"></div>
                                    </div><!-- end col -->
                                </div><!-- end row -->
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end -->

                    <div class="swiper-slide">
                        <div class="card">
                            <div class="card-body">
                                <div class="float-end">
                                    <div class="dropdown">
                                        <a class="text-reset" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <span class="text-muted fs-18"><i class="mdi mdi-dots-horizontal"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Details</a>
                                            <a class="dropdown-item" href="#">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo e(URL::asset('build/images/svg/crypto-icons/dash.svg')); ?>"
                                        class="bg-light rounded-circle p-1 avatar-xs img-fluid shadow" alt="">
                                    <h6 class="ms-2 mb-0 fs-14">Dash</h6>
                                </div>
                                <div class="row align-items-end g-0">
                                    <div class="col-6">
                                        <h5 class="mb-1 mt-4">$9,458,153</h5>
                                        <p class="text-success fs-13 fw-medium mb-0">+12.07%<span
                                                class="text-muted ms-2 fs-10 text-uppercase">(dash)</span>
                                        </p>
                                    </div><!-- end col -->
                                    <div class="col-6">
                                        <div class="apex-charts crypto-widget"
                                            data-colors='["--vz-success", "--vz-transparent"]' id="dash_sparkline_charts"
                                            dir="ltr"></div>
                                    </div><!-- end col -->
                                </div><!-- end row -->
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end -->

                    <div class="swiper-slide">
                        <div class="card">
                            <div class="card-body">
                                <div class="float-end">
                                    <div class="dropdown">
                                        <a class="text-reset" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <span class="text-muted fs-18"><i class="mdi mdi-dots-horizontal"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Details</a>
                                            <a class="dropdown-item" href="#">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo e(URL::asset('build/images/svg/crypto-icons/usdt.svg')); ?>"
                                        class="bg-light rounded-circle p-1 avatar-xs img-fluid shadow" alt="">
                                    <h6 class="ms-2 mb-0 fs-14">Tether</h6>
                                </div>
                                <div class="row align-items-end g-0">
                                    <div class="col-6">
                                        <h5 class="mb-1 mt-4">$5,201,458</h5>
                                        <p class="text-success fs-13 fw-medium mb-0">+14.99%<span
                                                class="text-muted ms-2 fs-10 text-uppercase">(usdt)</span>
                                        </p>
                                    </div><!-- end col -->
                                    <div class="col-6">
                                        <div class="apex-charts crypto-widget"
                                            data-colors='["--vz-success", "--vz-transparent"]' id="tether_sparkline_charts"
                                            dir="ltr"></div>
                                    </div><!-- end col -->
                                </div><!-- end row -->
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end -->

                    <div class="swiper-slide">
                        <div class="card">
                            <div class="card-body">
                                <div class="float-end">
                                    <div class="dropdown">
                                        <a class="text-reset" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <span class="text-muted fs-18"><i class="mdi mdi-dots-horizontal"></i></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Details</a>
                                            <a class="dropdown-item" href="#">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo e(URL::asset('build/images/svg/crypto-icons/neo.svg')); ?>"
                                        class="bg-light rounded-circle p-1 avatar-xs img-fluid shadow" alt="">
                                    <h6 class="ms-2 mb-0 fs-14">NEO</h6>
                                </div>
                                <div class="row align-items-end g-0">
                                    <div class="col-6">
                                        <h5 class="mb-1 mt-4">$6,147,957</h5>
                                        <p class="text-danger fs-13 fw-medium mb-0">-05.07%<span
                                                class="text-muted ms-2 fs-10 text-uppercase">(neo)</span>
                                        </p>
                                    </div><!-- end col -->
                                    <div class="col-6">
                                        <div class="apex-charts crypto-widget"
                                            data-colors='["--vz-danger", "--vz-transparent"]' id="neo_sparkline_charts"
                                            dir="ltr"></div>
                                    </div><!-- end col -->
                                </div><!-- end row -->
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end -->
                </div><!-- end swiper wrapper -->
            </div><!-- end swiper -->
        </div><!-- end col -->
    </div><!-- end row -->
    
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header border-0 align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Audiences Metrics</h4>
                    <div>
                        <button type="button" class="btn btn-soft-secondary btn-sm shadow-none">
                            ALL
                        </button>
                        <button type="button" class="btn btn-soft-secondary btn-sm shadow-none">
                            1M
                        </button>
                        <button type="button" class="btn btn-soft-secondary btn-sm shadow-none">
                            6M
                        </button>
                        <button type="button" class="btn btn-soft-info btn-sm shadow-none">
                            1Y
                        </button>
                    </div>
                </div><!-- end card header -->
                <div class="card-header p-0 border-0 bg-light-subtle">
                    <div class="row g-0 text-center">
                        <div class="col-6 col-sm-4">
                            <div class="p-3 border border-dashed border-start-0">
                                <h5 class="mb-1"><span class="counter-value" data-target="854">0</span>
                                    <span class="text-success ms-1 fs-12">49%<i
                                            class="ri-arrow-right-up-line ms-1 align-middle"></i></span>
                                </h5>
                                <p class="text-muted mb-0">Avg. Session</p>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-6 col-sm-4">
                            <div class="p-3 border border-dashed border-start-0">
                                <h5 class="mb-1"><span class="counter-value" data-target="1278">0</span>
                                    <span class="text-success ms-1 fs-12">60%<i
                                            class="ri-arrow-right-up-line ms-1 align-middle"></i></span>
                                </h5>
                                <p class="text-muted mb-0">Conversion Rate</p>
                            </div>
                        </div>
                        <!--end col-->
                        <div class="col-6 col-sm-4">
                            <div class="p-3 border border-dashed border-start-0 border-end-0">
                                <h5 class="mb-1"><span class="counter-value" data-target="3">0</span>m
                                    <span class="counter-value" data-target="40">0</span>sec
                                    <span class="text-success ms-1 fs-12">37%<i
                                            class="ri-arrow-right-up-line ms-1 align-middle"></i></span>
                                </h5>
                                <p class="text-muted mb-0">Avg. Session Duration</p>
                            </div>
                        </div>
                        <!--end col-->
                    </div>
                </div><!-- end card header -->
                <div class="card-body p-0 pb-2">
                    <div>
                        <div id="audiences_metrics_charts" data-colors='["--vz-success", "--vz-light"]'
                            class="apex-charts" dir="ltr"></div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Audiences Sessions by Country</h4>
                    <div class="flex-shrink-0">
                        <div class="dropdown card-header-dropdown">
                            <a class="text-reset" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <span class="fw-semibold text-uppercase fs-12">Sort by: </span><span
                                    class="text-muted">Current Week<i class="mdi mdi-chevron-down ms-1"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">Today</a>
                                <a class="dropdown-item" href="#">Last Week</a>
                                <a class="dropdown-item" href="#">Last Month</a>
                                <a class="dropdown-item" href="#">Current Year</a>
                            </div>
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body p-0">
                    <div>
                        <div id="audiences-sessions-country-charts" data-colors='["--vz-success", "--vz-info"]'
                            class="apex-charts" dir="ltr">
                        </div>
                    </div>
                </div><!-- end cardbody -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    <div class="row">
        <div class="col-xl-4">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Users by Device</h4>
                    <div class="flex-shrink-0">
                        <div class="dropdown card-header-dropdown">
                            <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <span class="text-muted fs-16"><i class="mdi mdi-dots-vertical align-middle"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">Today</a>
                                <a class="dropdown-item" href="#">Last Week</a>
                                <a class="dropdown-item" href="#">Last Month</a>
                                <a class="dropdown-item" href="#">Current Year</a>
                            </div>
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div id="user_device_pie_charts" data-colors='["--vz-info", "--vz-warning", "--vz-info"]'
                        class="apex-charts" dir="ltr"></div>

                    <div class="table-responsive mt-3">
                        <table class="table table-borderless table-sm table-centered align-middle table-nowrap mb-0">
                            <tbody class="border-0">
                                <tr>
                                    <td>
                                        <h4 class="text-truncate fs-14 fs-medium mb-0"><i
                                                class="ri-stop-fill align-middle fs-18 text-info me-2"></i>Desktop
                                            Users</h4>
                                    </td>
                                    <td>
                                        <p class="text-muted mb-0"><i data-feather="users"
                                                class="me-2 icon-sm"></i>78.56k</p>
                                    </td>
                                    <td class="text-end">
                                        <p class="text-success fw-medium fs-12 mb-0"><i
                                                class="ri-arrow-up-s-fill fs-5 align-middle"></i>2.08%
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h4 class="text-truncate fs-14 fs-medium mb-0"><i
                                                class="ri-stop-fill align-middle fs-18 text-warning me-2"></i>Mobile
                                            Users</h4>
                                    </td>
                                    <td>
                                        <p class="text-muted mb-0"><i data-feather="users"
                                                class="me-2 icon-sm"></i>105.02k</p>
                                    </td>
                                    <td class="text-end">
                                        <p class="text-danger fw-medium fs-12 mb-0"><i
                                                class="ri-arrow-down-s-fill fs-5 align-middle"></i>10.52%
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h4 class="text-truncate fs-14 fs-medium mb-0"><i
                                                class="ri-stop-fill align-middle fs-18 text-info me-2"></i>Tablet
                                            Users</h4>
                                    </td>
                                    <td>
                                        <p class="text-muted mb-0"><i data-feather="users"
                                                class="me-2 icon-sm"></i>42.89k</p>
                                    </td>
                                    <td class="text-end">
                                        <p class="text-danger fw-medium fs-12 mb-0"><i
                                                class="ri-arrow-down-s-fill fs-5 align-middle"></i>7.36%
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-4 col-md-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Top Referrals Pages</h4>
                    <div class="flex-shrink-0">
                        <button type="button" class="btn btn-soft-info btn-sm shadow-none">
                            Export Report
                        </button>
                    </div>
                </div>

                <div class="card-body">

                    <div class="row align-items-center">
                        <div class="col-6">
                            <h6 class="text-muted text-uppercase fw-semibold text-truncate fs-12 mb-3">
                                Total Referrals Page</h6>
                            <h4 class="fs- mb-0">725,800</h4>
                            <p class="mb-0 mt-2 text-muted"><span class="badge bg-success-subtle text-success mb-0">
                                    <i class="ri-arrow-up-line align-middle"></i> 15.72 %
                                </span> vs. previous month</p>
                        </div><!-- end col -->
                        <div class="col-6">
                            <div class="text-center">
                                <img src="<?php echo e(URL::asset('build/images/illustrator-1.png')); ?>" class="img-fluid"
                                    alt="">
                            </div>
                        </div><!-- end col -->
                    </div><!-- end row -->
                    <div class="mt-3 pt-2">
                        <div class="progress progress-lg rounded-pill">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 25%"
                                aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            <div class="progress-bar bg-info" role="progressbar" style="width: 18%" aria-valuenow="18"
                                aria-valuemin="0" aria-valuemax="100"></div>
                            <div class="progress-bar bg-success" role="progressbar" style="width: 22%"
                                aria-valuenow="22" aria-valuemin="0" aria-valuemax="100"></div>
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 16%"
                                aria-valuenow="16" aria-valuemin="0" aria-valuemax="100"></div>
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 19%" aria-valuenow="19"
                                aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div><!-- end -->

                    <div class="mt-3 pt-2">
                        <div class="d-flex mb-2">
                            <div class="flex-grow-1">
                                <p class="text-truncate text-muted fs-14 mb-0"><i
                                        class="mdi mdi-circle align-middle text-info me-2"></i>www.google.com
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <p class="mb-0">24.58%</p>
                            </div>
                        </div><!-- end -->
                        <div class="d-flex mb-2">
                            <div class="flex-grow-1">
                                <p class="text-truncate text-muted fs-14 mb-0"><i
                                        class="mdi mdi-circle align-middle text-info me-2"></i>www.youtube.com
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <p class="mb-0">17.51%</p>
                            </div>
                        </div><!-- end -->
                        <div class="d-flex mb-2">
                            <div class="flex-grow-1">
                                <p class="text-truncate text-muted fs-14 mb-0"><i
                                        class="mdi mdi-circle align-middle text-success me-2"></i>www.meta.com
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <p class="mb-0">23.05%</p>
                            </div>
                        </div><!-- end -->
                        <div class="d-flex mb-2">
                            <div class="flex-grow-1">
                                <p class="text-truncate text-muted fs-14 mb-0"><i
                                        class="mdi mdi-circle align-middle text-warning me-2"></i>www.medium.com
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <p class="mb-0">12.22%</p>
                            </div>
                        </div><!-- end -->
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-truncate text-muted fs-14 mb-0"><i
                                        class="mdi mdi-circle align-middle text-danger me-2"></i>Other
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <p class="mb-0">17.58%</p>
                            </div>
                        </div><!-- end -->
                    </div><!-- end -->

                    <div class="mt-2 text-center">
                        <a href="javascript:void(0);" class="text-muted text-decoration-underline">Show
                            All</a>
                    </div>

                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-4 col-md-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Top Pages</h4>
                    <div class="flex-shrink-0">
                        <div class="dropdown card-header-dropdown">
                            <a class="text-reset" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <span class="text-muted fs-16"><i class="mdi mdi-dots-vertical align-middle"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">Today</a>
                                <a class="dropdown-item" href="#">Last Week</a>
                                <a class="dropdown-item" href="#">Last Month</a>
                                <a class="dropdown-item" href="#">Current Year</a>
                            </div>
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table align-middle table-borderless table-centered table-nowrap mb-0">
                            <thead class="text-muted table-light">
                                <tr>
                                    <th scope="col" style="width: 62;">Active Page</th>
                                    <th scope="col">Active</th>
                                    <th scope="col">Users</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <a href="javascript:void(0);">/themesbrand/skote-25867</a>
                                    </td>
                                    <td>99</td>
                                    <td>25.3%</td>
                                </tr><!-- end -->
                                <tr>
                                    <td>
                                        <a href="javascript:void(0);">/dashonic/chat-24518</a>
                                    </td>
                                    <td>86</td>
                                    <td>22.7%</td>
                                </tr><!-- end -->
                                <tr>
                                    <td>
                                        <a href="javascript:void(0);">/skote/timeline-27391</a>
                                    </td>
                                    <td>64</td>
                                    <td>18.7%</td>
                                </tr><!-- end -->
                                <tr>
                                    <td>
                                        <a href="javascript:void(0);">/themesbrand/minia-26441</a>
                                    </td>
                                    <td>53</td>
                                    <td>14.2%</td>
                                </tr><!-- end -->
                                <tr>
                                    <td>
                                        <a href="javascript:void(0);">/dashon/dashboard-29873</a>
                                    </td>
                                    <td>33</td>
                                    <td>12.6%</td>
                                </tr><!-- end -->
                                <tr>
                                    <td>
                                        <a href="javascript:void(0);">/doot/chats-29964</a>
                                    </td>
                                    <td>20</td>
                                    <td>10.9%</td>
                                </tr><!-- end -->
                                <tr>
                                    <td>
                                        <a href="javascript:void(0);">/minton/pages-29739</a>
                                    </td>
                                    <td>10</td>
                                    <td>07.3%</td>
                                </tr><!-- end -->
                            </tbody><!-- end tbody -->
                        </table><!-- end table -->
                    </div><!-- end -->
                </div><!-- end cardbody -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('build/libs/apexcharts/apexcharts.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/jsvectormap/jsvectormap.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/jsvectormap/maps/world-merc.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/dashboard-analytics.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>


<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.horizontal'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(URL::asset('build/libs/jsvectormap/jsvectormap.min.css')); ?>" rel="stylesheet">
    <style>

        .my-alert {
            
            transition: background-color 0.6s ease-in-out, color 0.6s ease-in-out;
        }
        .my-card:hover .my-alert {
            background-color: #299cdb !important; /* bg-info */
            
            color: white !important;
        }
        


    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<!-- Top part -->
<div class="col-xl-12">
    <div class="card card-animate border-0 shadow-sm h-100">
        <div class="row g-0">
            
            <!-- Lijevi elementi (2 kolone) -->
            <div class="col-md-2 border-end border-3">
                <div class="d-flex flex-column h-100">

                    <!-- Obavijest u gornjem lijevom uglu -->
                    <div class="bg-info text-white text-center py-1 rounded-0">
                        <i class="ri-alert-line me-1"></i>
                        <span>Testna poruka <b>123</b> test.</span>
                    </div>

                    <!-- Sadržaj centriran ispod -->
                    <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center p-2">
                        <h6 class="text-muted text-uppercase fs-11 mb-1">Broj skeniranih faktura</h6>
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="ri-file-text-line fs-1 text-info mb-1"></i>
                            <!-- Here we will insert the API value dynamically -->
                            <h3 class="mb-0 ms-2"><span id="usedScans" class="counter-value">0</span></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2 border-end border-3">
                <div class="d-flex flex-column h-100">
                    
                    <!-- Obavijest (isti naslov za drugu kolonu) -->
                    <div class="bg-info text-white text-center py-1 rounded-0">
                        <i class="ri-alert-line me-1"></i>
                        <span>Testna poruka <b>123</b> test.</span>
                    </div>

                    <!-- Sadržaj centriran ispod -->
                    <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center p-2">
                        <h6 class="text-muted text-uppercase fs-11 mb-1">Dostupna skeniranja</h6>
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="ri-scan-2-line fs-1 text-info mb-1"></i>
                            <h3 class="mb-0 ms-2"><span class="counter-value" id="remainScans">0</span></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Srednji dio sa avatarom -->
            <div class="col-md-4 border-end d-flex align-items-center border-0 rounded-0 alert alert-light p-1 m-0">
                <div class="p-2 text-center d-flex flex-column h-100 w-100 justify-content-center align-items-center">
                    <div class="card-body text-center">
                        <img src="<?php if(Auth::user()->avatar != ''): ?><?php echo e(URL::asset('images/' . Auth::user()->avatar)); ?><?php else: ?><?php echo e(URL::asset('build/images/users/avatar-1.jpg')); ?><?php endif; ?>"
                            class="rounded-circle shadow-sm mb-1" width="60" height="60" alt="Korisnički avatar">
                        <h6 class="fw-bold text-dark mb-1">Dobrodošli na eDeklarant, <?php echo e(Auth::user()->name); ?>!</h6>
                        <p class="fw-semibold fs-7 mb-1 text-dark">Vaš trenutni paket je <b>Starter</b></p>
                    </div>

                    <!-- Dugmad poredana jedno pored drugog -->
                    <div class="card-footer bg-transparent border-0 w-100">
                        <div class="d-flex justify-content-center gap-2 w-100">
                            <a href="pages-pricing" class="btn btn-info text-white w-50 btn-sm">
                                <i class="ri-arrow-up-circle-line fs-14 me-1"></i> Nadogradite paket
                            </a>
                            <a href="pages-scan" class="btn btn-info w-50 animated-btn btn-sm">
                                <i class="ri-qr-scan-2-line fs-14 me-1"></i> Pokreni skeniranje
                            </a>   
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desni elementi (2 kolone) -->
            <div class="col-md-2 border-end">
                <div class="d-flex flex-column h-100">
                    
                    <!-- Sadržaj centriran ispod -->
                    <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center p-2">
                        <h6 class="text-muted text-uppercase fs-11 mb-1">Ukupno dobavljača</h6>
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="ri-truck-line fs-1 text-info"></i>
                            <h3 class="mb-0 ms-2"><span class="counter-value" id="totalSuppliers">0</span></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="d-flex flex-column h-100">
                    
                    <!-- Sadržaj centriran ispod -->
                    <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center p-2">
                        <h6 class="text-muted text-uppercase fs-11 mb-1">Broj carinskih tarifa</h6>
                        <div class="d-flex align-items-center justify-content-center">
                        <i class="ri-barcode-box-line fs-1 text-info"></i>

                            <h3 class="mb-0 ms-2"><span class="counter-value" data-target="128"></span></h3>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- Kraj reda -->
    </div> <!-- Kraj kartice -->
</div>









<!-- Mid part of layout -->


<div class="row g-2  mt-2 w-100">
    <!-- Card 1 -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate overflow-hidden">
            <div class="position-absolute start-0" style="z-index: 0;">
                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120" width="200" height="120">
                    <style>
                        .s0 { opacity: .05; fill: var(--vz-info) }
                    </style>
                    <path id="Shape 8" class="s0"
                        d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4z" />
                </svg>
            </div>
            <div class="card-body" style="z-index:1;">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Izvršena skeniranja</p>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            <span class="counter-value" data-target="28">0</span>
                        </h4>
                    </div>
                    <div class="flex-shrink-0">
                        <canvas id="doughnut1" width="80" height="80"></canvas>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div>

    <!-- Card 2 -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate overflow-hidden">
            <div class="position-absolute start-0" style="z-index: 0;">
                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120" width="200" height="120">
                    <style>
                        .s0 { opacity: .05; fill: var(--vz-info) }
                    </style>
                    <path id="Shape 8" class="s0"
                        d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4z" />
                </svg>
            </div>
            <div class="card-body" style="z-index:1;">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3" >Broj faktura</p>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            <span class="counter-value" data-target="45" id="totalInvoices">0</span>
                        </h4>
                    </div>
                    <div class="flex-shrink-0">
                        <canvas id="doughnut2" width="80" height="80"></canvas>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div>

    <!-- Card 3 -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate overflow-hidden">
            <div class="position-absolute start-0" style="z-index: 0;">
                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120" width="200" height="120">
                    <style>
                        .s0 { opacity: .05; fill: var(--vz-info) }
                    </style>
                    <path id="Shape 8" class="s0"
                        d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4z" />
                </svg>
            </div>
            <div class="card-body" style="z-index:1;">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Broj dobavljača</p>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            <span class="counter-value" data-target="19">0</span>
                        </h4>
                    </div>
                    <div class="flex-shrink-0">
                        <canvas id="doughnut3" width="80" height="80"></canvas>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div>

    <!-- Card 4 -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate overflow-hidden">
            <div class="position-absolute start-0" style="z-index: 0;">
                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120" width="200" height="120">
                    <style>
                        .s0 { opacity: .05; fill: var(--vz-info) }
                    </style>
                    <path id="Shape 8" class="s0"
                        d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4z" />
                </svg>
            </div>
            <div class="card-body" style="z-index:1;">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Vrijeme Skeniranja</p>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            <span class="counter-value" data-target="10">0</span> sec
                        </h4>
                    </div>
                    <div class="flex-shrink-0">
                        <canvas id="doughnut4" width="80" height="80"></canvas>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div>
</div>

<!-- Bottom part of layout -->

<div class="row g-1 ">
    <!-- Card 1 -->
    <div class="col-xl-2 col-md-6">
        <div class="card card-animate overflow-hidden">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Vrijeme Skeniranja</p>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            <span class="counter-value" data-target="10">0</span> sec
                        </h4>
                    </div>
                    <div class="flex-shrink-0">
                        <canvas class="scan-chart" width="80" height="80"></canvas>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div>

    

    <!-- Card 2 -->
    <div class="col-xl-2 col-md-6">
        <div class="card card-animate overflow-hidden">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Dostupna Skeniranja</p>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            <span class="counter-value" data-target="28">0</span>
                        </h4>
                    </div>
                    <div class="flex-shrink-0">
                        <canvas class="scan-chart" width="80" height="80"></canvas>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div>


    <div class="col-xl-2 col-md-6">
        <div class="card card-animate overflow-hidden">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Dostupna Skeniranja</p>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            <span class="counter-value" data-target="28">0</span>
                        </h4>
                    </div>
                    <div class="flex-shrink-0">
                        <canvas class="scan-chart" width="80" height="80"></canvas>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div>


    <!-- Card 3 -->
    <div class="col-xl-2 col-md-6">
        <div class="card card-animate overflow-hidden">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Broj Faktura</p>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            <span class="counter-value" data-target="320">0</span>
                        </h4>
                    </div>
                    <div class="flex-shrink-0">
                        <canvas class="scan-chart" width="80" height="80"></canvas>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div>
    <div class="col-xl-2 col-md-6">
        <div class="card card-animate overflow-hidden">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Dostupna Skeniranja</p>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            <span class="counter-value" data-target="28">0</span>
                        </h4>
                    </div>
                    <div class="flex-shrink-0">
                        <canvas class="scan-chart" width="80" height="80"></canvas>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div>


    <!-- Card 4 -->
    <div class="col-xl-2 col-md-6">
        <div class="card card-animate overflow-hidden">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Broj Dobavljača</p>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            <span class="counter-value" data-target="56">0</span>
                        </h4>
                    </div>
                    <div class="flex-shrink-0">
                        <canvas class="scan-chart" width="80" height="80"></canvas>
                    </div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div>
</div>







    <!-- Bottom part -->


     

    

    
    






<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('build/libs/apexcharts/apexcharts.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/dashboard-analytics.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/chart.js/chart.umd.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/chartjs.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>

    <script src="<?php echo e(URL::asset('build/js/pages/dashboard-nft.init.js')); ?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    


    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // Custom plugin to render text inside the chart
            const centerTextPlugin = {
                id: "centerText",
                beforeDraw: function (chart) {
                    const width = chart.width,
                          height = chart.height,
                          ctx = chart.ctx;

                    ctx.restore();
                    const fontSize = (height / 8).toFixed(2);
                    ctx.font = fontSize + "px sans-serif";
                    ctx.textBaseline = "middle";
                    ctx.textAlign = "center";

                    // Get the percentage from dataset
                    const dataset = chart.data.datasets[0];
                    const total = dataset.data.reduce((acc, val) => acc + val, 0);
                    const percentage = Math.round((dataset.data[0] / total) * 100);

                    const text = percentage + "%";
                    const textX = Math.round(width / 2);
                    const textY = Math.round(height / 2);

                    ctx.fillStyle = "#0dcaf0"; // Info color
                    ctx.fillText(text, textX, textY);
                    ctx.save();
                }
            };

            function createDoughnutChart(canvasId, usedPercentage) {
                var ctx = document.getElementById(canvasId).getContext("2d");
                var remaining = 100 - usedPercentage;

                new Chart(ctx, {
                    type: "doughnut",
                    data: {
                        labels: ["Used", "Remaining"],
                        datasets: [{
                            data: [usedPercentage, remaining],
                            backgroundColor: ["#299cdb", "#d6f0fa"],
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: "70%", // Makes the doughnut shape
                        plugins: {
                            legend: { display: false },
                            tooltip: { enabled: false },
                        }
                    },
                    plugins: [centerTextPlugin] // Add the custom plugin
                });
            }

            createDoughnutChart("doughnut1", 16.24);
            createDoughnutChart("doughnut2", 3.96);
            createDoughnutChart("doughnut3", 9.32);
            createDoughnutChart("doughnut4", 4.21);
        });
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        function counter() {
            var counterElements = document.querySelectorAll(".counter-num");
            var speed = 10000000; // Adjust the speed (higher = slower animation)

            counterElements.forEach((counter) => {
                function updateCount() {
                    var target = +counter.getAttribute("data-target");
                    var count = +counter.innerText || 0;
                    var increment = target / speed;

                    if (increment < 1) increment = 1;

                    if (count < target) {
                        counter.innerText = (count + increment).toFixed(0);
                        setTimeout(updateCount, 15); // Delay for a smoother effect
                    } else {
                        counter.innerText = numberWithCommas(target);
                    }
                }
                updateCount();
            });
        }

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        counter(); // Run the counter function
    });

    document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".scan-chart").forEach((canvas, index) => {
        var ctx = canvas.getContext("2d");

        new Chart(ctx, {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                datasets: [{
                    data: [30, 50, 80, 40, 70, 20, 50], // Sample data
                    backgroundColor: "#d6f0fa", // Light green background
                    borderColor: "#299cdb", // info color
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4 // Smooth curve
                    
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { display: false }, // Hide X axis
                    y: { display: false }  // Hide Y axis
                },
                plugins: { legend: { display: false } } // Hide legend
            }
        });
    });
});
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const USER_ID = <?php echo e(auth()->id() ?? 'null'); ?>;
    const BASE_URL = "<?php echo e(url('')); ?>"; 
    const API_URL = `${BASE_URL}/api/statistics/users/${USER_ID}`;

    // Fetch data from API using Axios
    axios.get(API_URL)
        .then(response => {
            if (response.data) {
                // List of fields to update
                const fields = {
                    totalSuppliers: response.data.total_suppliers ?? 0,  // Default to 0 if undefined
                    totalInvoices: response.data.total_invoices ?? 0,
                    usedScans: response.data.used_scans ?? 0,
                    remainScans: response.data.remaining_scans ?? 0
                };

                // Loop through each field and update the respective HTML element
                for (const [id, value] of Object.entries(fields)) {
                    const element = document.getElementById(id);
                    if (element !== null) {
                        element.innerText = value;
                    } else {
                        console.warn(`Element with ID '${id}' not found in DOM.`);
                    }
                }
            } else {
                console.error("API response does not contain expected data.");
            }
        })
        .catch(error => {
            console.error("Error fetching API data:", error);
        });
});
</script> 



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.layouts-horizontal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\edeklarant\app-service-laravel\resources\views/layouts-horizontal.blade.php ENDPATH**/ ?>