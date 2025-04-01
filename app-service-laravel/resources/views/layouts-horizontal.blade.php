@extends('layouts.layouts-horizontal')

@section('title')
    @lang('translation.horizontal')
@endsection

@section('css')
    <link href="{{ URL::asset('build/libs/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>

        .my-alert {
            
            transition: background-color 0.6s ease-in-out, color 0.6s ease-in-out;
        }
        .my-card:hover .my-alert {
            background-color: #299cdb !important; /* bg-info */
            
            color: white !important;
        }
     
        /* Ensure the swiper-container behaves as expected */
        

        
        
        


        


    </style>
@endsection

@section('content')


<!-- Top part -->
 
<div class="col-xl-12 ">
    <div class="card card-animate border-0 shadow-sm h-100">
        <div class="row g-0">
            <!-- Left Columns -->
            <div class="col-md-2 border-end border-3">
                <div class="d-flex flex-column h-100">
                    <div class="bg-info text-white text-center py-1 rounded-0">
                        <i class="ri-alert-line me-1"></i>
                        <span>Testna poruka <b>123</b> test.</span>
                    </div>
                    <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center p-2">
                        <h6 class="text-muted text-uppercase fs-11 mb-1">Broj skeniranih faktura</h6>
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="ri-file-text-line fs-1 text-info mb-1"></i>
                            <h3 class="mb-0 ms-2"><span id="usedScans" class="counter-value">0</span></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2 border-end border-3">
                <div class="d-flex flex-column h-100">
                    <div class="bg-info text-white text-center py-1 rounded-0">
                        <i class="ri-alert-line me-1"></i>
                        <span>Testna poruka <b>123</b> test.</span>
                    </div>
                    <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center p-2">
                        <h6 class="text-muted text-uppercase fs-11 mb-1">Dostupna skeniranja</h6>
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="ri-scan-2-line fs-1 text-info mb-1"></i>
                            <h3 class="mb-0 ms-2"><span class="counter-value" id="remainScans">0</span></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Middle Avatar Section -->
            <div class="col-md-4 border-end d-flex align-items-center border-0 rounded-0 alert alert-light p-1 m-0">
                <div class="p-2 text-center d-flex flex-column h-100 w-100 justify-content-center align-items-center">
                    <div class="card-body text-center">
                        <img id="user-avatar" src="{{ URL::asset('build/images/users/avatar-1.jpg') }}" class="rounded-circle shadow-sm mb-1" width="60" height="60" alt="Korisnički avatar">
                        <h6 class="fw-bold text-dark mb-1" id="welcome-user">Dobrodošli na eDeklarant!</h6>
                        <p class="fw-semibold fs-7 mb-1 text-dark">Vaš trenutni paket je <b>Starter</b></p>
                    </div>
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

            <!-- Right Columns remain unchanged -->
            <div class="col-md-2 border-end">
                <div class="d-flex flex-column h-100">
                    <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center p-2">
                        <h6 class="text-muted text-uppercase fs-11 mb-1">Moji dobavljači</h6>
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="ri-truck-line fs-1 text-info"></i>
                            <h3 class="mb-0 ms-2"><span class="counter-value" id="totalSuppliers">0</span></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="d-flex flex-column h-100">
                    <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center p-2">
                        <h6 class="text-muted text-uppercase fs-11 mb-1">Broj carinskih tarifa</h6>
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="ri-barcode-box-line fs-1 text-info"></i>
                            <h3 class="mb-0 ms-2"><span class="counter-value" data-target="128"></span></h3>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Mid part of layout -->

<div class="row g-1 mt-2">
    <!-- Card 1: Izvršena skeniranja -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate overflow-hidden">
            <div class="position-absolute start-0" style="z-index: 0;">
                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120" width="200" height="120">
                    <style>.s0 { opacity: .05; fill: var(--vz-info); }</style>
                    <path class="s0" d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4z" />
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
            </div>
        </div>
    </div>

    <!-- Card 2: Broj faktura -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate overflow-hidden">
            <div class="position-absolute start-0" style="z-index: 0;">
                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120" width="200" height="120">
                    <style>.s0 { opacity: .05; fill: var(--vz-info); }</style>
                    <path class="s0" d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4z" />
                </svg>
            </div>
            <div class="card-body" style="z-index:1;">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Broj faktura</p>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            <span class="counter-value" id="totalInvoices" data-target="45">0</span>
                        </h4>
                    </div>
                    <div class="flex-shrink-0">
                        <canvas id="doughnut2" width="80" height="80"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Ukupan broj dobavljača -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate overflow-hidden">
            <div class="position-absolute start-0" style="z-index: 0;">
                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120" width="200" height="120">
                    <style>.s0 { opacity: .05; fill: var(--vz-info); }</style>
                    <path class="s0" d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4z" />
                </svg>
            </div>
            <div class="card-body" style="z-index:1;">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Ukupan broj dobavljača</p>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            <span class="counter-value" id="totalNumSup" data-target="19">0</span>
                        </h4>
                    </div>
                    <div style="width: 80px; height: 80px;" class="d-flex align-items-center justify-content-center">
                        <i class="ri-truck-line fs-1 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Vrijeme skeniranja -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate overflow-hidden">
            <div class="position-absolute start-0" style="z-index: 0;">
                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120" width="200" height="120">
                    <style>.s0 { opacity: .05; fill: var(--vz-info); }</style>
                    <path class="s0" d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4z" />
                </svg>
            </div>
            <div class="card-body" style="z-index:1;">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Vrijeme Skeniranja</p>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            <span class="counter-value" id="scanTimeValue">0.00</span>
                        </h4>
                    </div>
                    <div style="width: 80px; height: 80px;" class="d-flex align-items-center justify-content-center">
                        <i class="ri-timer-flash-line fs-1 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Bottom part of layout -->

<div class="swiper mySwiper mt-2">
    <div class="swiper-wrapper" id="supplierCardsContainer">
        <!-- Dynamic cards will be injected here as .swiper-slide -->
    </div>
    
    
</div>

<div class="row g-3 mt-0">
    <!-- Left Column (2 rows, each with 2 cards) -->
    <div class="col-md-6">
    <!-- First Row of Cards -->
    <div class="row g-2">
        <div class="col-md-6 d-flex">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Moji dokumenti</h5>
                        <a data-bs-toggle="tab" href="#activities" id="viewAllDocuments" class="text-info fs-13">View all</a>
                    </div>
                    <div class="row g-2">
                        <!-- Documents in the same row -->
                        <div class="col-6 col-sm-3">
                            <div class="text-center">
                                <i class="ri-file-pdf-2-line fs-24 text-info"></i>
                                <p class="fs-6 text-muted mt-1 mb-0">Dokument.pdf</p>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="text-center">
                                <i class="ri-file-image-line fs-24 text-info"></i>
                                <p class="fs-6 text-muted mt-1 mb-0">Slika.jpg</p>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="text-center">
                                <i class="ri-file-image-line fs-24 text-info"></i>
                                <p class="fs-6 text-muted mt-1 mb-0">Grafika.png</p>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="text-center">
                                <i class="ri-file-excel-2-line fs-24 text-info"></i>
                                <p class="fs-6 text-muted mt-1 mb-0">Tabela.xlsx</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 d-flex">
            <div class="card w-100 ">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Moji dokumenti</h5>
                        <a data-bs-toggle="tab" href="#activities" id="viewAllDocuments" class="text-info fs-13">View all</a>
                    </div>
                    <div class="row g-2">
                        <!-- Documents in the same row -->
                        <div class="col-6 col-sm-3">
                            <div class="text-center">
                                <i class="ri-file-pdf-2-line fs-24 text-info"></i>
                                <p class="fs-6 text-muted mt-1 mb-0">Dokument.pdf</p>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="text-center">
                                <i class="ri-file-image-line fs-24 text-info"></i>
                                <p class="fs-6 text-muted mt-1 mb-0">Grafika.png</p>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="text-center">
                                <i class="ri-file-image-line fs-24 text-info"></i>
                                <p class="fs-6 text-muted mt-1 mb-0">Grafika.png</p>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="text-center">
                                <i class="ri-file-excel-2-line fs-24 text-info"></i>
                                <p class="fs-6 text-muted mt-1 mb-0">Tabela.xlsx</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row of Cards -->
    <div class="row g-2 mt-2">
        <div class="col-md-6 d-flex">
            <div class="card w-100 ">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Moji dokumenti</h5>
                        <a data-bs-toggle="tab" href="#activities" id="viewAllDocuments" class="text-info fs-13">View all</a>
                    </div>
                    <div class="row g-2">
                        <!-- Documents in the same row -->
                        <div class="col-6 col-sm-3">
                            <div class="text-center">
                                <i class="ri-file-pdf-2-line fs-24 text-info"></i>
                                <p class="fs-6 text-muted mt-1 mb-0">Dokument.pdf</p>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="text-center">
                                <i class="ri-file-image-line fs-24 text-info"></i>
                                <p class="fs-6 text-muted mt-1 mb-0">Slika.jpg</p>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="text-center">
                                <i class="ri-file-image-line fs-24 text-info"></i>
                                <p class="fs-6 text-muted mt-1 mb-0">Grafika.png</p>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="text-center">
                                <i class="ri-file-excel-2-line fs-24 text-info"></i>
                                <p class="fs-6 text-muted mt-1 mb-0">Tabela.xlsx</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 d-flex">
            <div class="card w-100 ">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Moji dokumenti</h5>
                        <a data-bs-toggle="tab" href="#activities" id="viewAllDocuments" class="text-info fs-13">View all</a>
                    </div>
                    <div class="row g-2">
                        <!-- Documents in the same row -->
                        <div class="col-6 col-sm-3">
                            <div class="text-center">
                                <i class="ri-file-pdf-2-line fs-24 text-info"></i>
                                <p class="fs-6 text-muted mt-1 mb-0">Dokument.pdf</p>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="text-center">
                                <i class="ri-file-image-line fs-24 text-info"></i>
                                <p class="fs-6 text-muted mt-1 mb-0">Grafika.png</p>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="text-center">
                                <i class="ri-file-image-line fs-24 text-info"></i>
                                <p class="fs-6 text-muted mt-1 mb-0">Grafika.png</p>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="text-center">
                                <i class="ri-file-excel-2-line fs-24 text-info"></i>
                                <p class="fs-6 text-muted mt-1 mb-0">Tabela.xlsx</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- Right Column (2 cards with lists) -->
    <div class="col-md-6">
        <div class="row g-3">
            <!-- First card for "Zadnje korišteni dobavljači" -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Zadnje korišteni dobavljači</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="fs-14 mb-0">Hifa</p>
                                <p class="fs-12 text-muted mb-0">Owner: John Doe</p>
                            </div>
                            <div class="fs-14">75%</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="fs-14 mb-0">Orbico</p>
                                <p class="fs-12 text-muted mb-0">Owner: Jane Smith</p>
                            </div>
                            <div class="fs-14">85%</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="fs-14 mb-0">DHL</p>
                                <p class="fs-12 text-muted mb-0">Owner: Michael Brown</p>
                            </div>
                            <div class="fs-14">90%</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <p class="fs-14 mb-0">Samsung</p>
                                <p class="fs-12 text-muted mb-0">Owner: Sarah Lee</p>
                            </div>
                            <div class="fs-14">80%</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second card for "Zadnje korišteni dobavljači" -->
            <div class="col-md-6">
                <div class="card ">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1">Zadnje korišteni dobavljači</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="fs-14 mb-0">Hifa</p>
                                <p class="fs-12 text-muted mb-0">Owner: John Doe</p>
                            </div>
                            <div class="fs-14">75%</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="fs-14 mb-0">Orbico</p>
                                <p class="fs-12 text-muted mb-0">Owner: Jane Smith</p>
                            </div>
                            <div class="fs-14">85%</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="fs-14 mb-0">DHL</p>
                                <p class="fs-12 text-muted mb-0">Owner: Michael Brown</p>
                            </div>
                            <div class="fs-14">90%</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <p class="fs-14 mb-0">Samsung</p>
                                <p class="fs-12 text-muted mb-0">Owner: Sarah Lee</p>
                            </div>
                            <div class="fs-14">80%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>



<!-- Test div -->



@endsection

@section('script')
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/dashboard-analytics.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script src="{{ URL::asset('build/libs/chart.js/chart.umd.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/chartjs.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

  

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Swiper CSS -->


    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    


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
    </script>

    <script>
    function renderScanCharts() {
        document.querySelectorAll(".scan-chart").forEach((canvas) => {
            const ctx = canvas.getContext("2d");

            new Chart(ctx, {
                type: "line",
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                    datasets: [{
                        data: [30, 50, 80, 40, 70, 20, 50],
                        backgroundColor: "#d6f0fa",
                        borderColor: "#299cdb",
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { display: false },
                        y: { display: false }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    }
    </script>



<script>
document.addEventListener("DOMContentLoaded", async function () {
    const user = JSON.parse(localStorage.getItem("user"));
    const token = localStorage.getItem("auth_token");

    if (!user || !token) {
        console.warn("User or token missing in localStorage.");
        return;
    }

    const API_URL = `/api/statistics/users/${user.id}`;

    try {
        const response = await axios.get(API_URL, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });

        const stats = response.data || {};
        const fields = {
            totalSuppliers: stats.total_suppliers ?? 0,
            totalInvoices: stats.total_invoices ?? 0,
            usedScans: stats.used_scans ?? 0,
            remainScans: stats.remaining_scans ?? 0
        };

        Object.entries(fields).forEach(([id, value]) => {
            const el = document.getElementById(id);
            if (el) el.innerText = value;
        });

    } catch (error) {
        console.error("Error fetching statistics:", error);
    }
});
</script>

<script>
document.addEventListener("DOMContentLoaded", async function () {
    

    const API_URL = `/api/statistics`;

    try {
        const response = await axios.get(API_URL, {
            
        });

        const stats = response.data || {};
        const fields = {
            totalNumSup: stats.total_suppliers ?? 0,
           
        };

        Object.entries(fields).forEach(([id, value]) => {
            const el = document.getElementById(id);
            if (el) el.innerText = value;
        });

    } catch (error) {
        console.error("Error fetching statistics:", error);
    }
});
</script>




<script>
document.addEventListener("DOMContentLoaded", function () {
    const user = JSON.parse(localStorage.getItem("user"));

    if (user) {
        const welcome = document.getElementById("welcome-user");
        if (welcome) {
            welcome.innerText = `Dobrodošli na eDeklarant, ${user.username}!`;
        }

        const avatar = document.getElementById("user-avatar");
        if (avatar) {
            const avatarUrl = `/storage/uploads/avatars/${user.avatar}`;
            // Check if the image loads correctly, fallback if not
            const testImg = new Image();
            testImg.onload = function () {
                avatar.src = avatarUrl;
            };
            testImg.onerror = function () {
                avatar.src = "/build/images/users/avatar-1.jpg";
            };
            testImg.src = avatarUrl;
        }
    }
});

</script>

<script>
document.addEventListener("DOMContentLoaded", async function () {
    const user = JSON.parse(localStorage.getItem("user"));
    const token = localStorage.getItem("auth_token");

    if (!user || !token) {
        console.warn("User or token missing in localStorage.");
        return;
    }

    const API_URL = `/api/invoices/users/${user.id}`;

    try {
        const response = await axios.get(API_URL, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });

        const invoices = response.data || [];

        if (Array.isArray(invoices) && invoices.length > 0) {
            const validScanTimes = invoices
                .map(inv => parseFloat(inv.scan_time))
                .filter(time => !isNaN(time));

            const totalScanTime = validScanTimes.reduce((acc, val) => acc + val, 0);
            const avgScanTime = validScanTimes.length > 0 ? totalScanTime / validScanTimes.length : 0;

            const scanTimeEl = document.getElementById("scanTimeValue");
            if (scanTimeEl) {
                scanTimeEl.innerText = `${avgScanTime.toFixed(2)} sec`;
            }
        }

    } catch (error) {
        console.error("Error fetching average scan time:", error);
    }
});
</script>


<script>
document.addEventListener("DOMContentLoaded", async function () {
    const token = localStorage.getItem("auth_token");

    if (!token) {
        console.warn("No token found in localStorage.");
        return;
    }

    try {
        const response = await axios.get("/api/suppliers", {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });

        const rawData = response.data;
        let suppliers = Array.isArray(rawData) ? rawData : rawData.data || [];

        // Fallback if less than 10
        const defaultSuppliers = [
            { name: "Generic Co.", address: "Sarajevo", fallback: true },
            { name: "Example Inc.", address: "Mostar", fallback: true },
            { name: "Placeholder Ltd.", address: "Tuzla", fallback: true },
            { name: "Test Supplier", address: "Zenica", fallback: true },
            { name: "Demo Group", address: "Bihać", fallback: true },
            { name: "ACME Corp", address: "Travnik", fallback: true }
        ];

        if (suppliers.length < 10) {
            suppliers = suppliers.concat(defaultSuppliers.slice(0, 10 - suppliers.length));
        } else {
            suppliers = suppliers.slice(-10);
        }

        const container = document.getElementById("supplierCardsContainer");

        suppliers.forEach(supplier => {
            const slide = document.createElement("div");
            slide.className = "swiper-slide";

            slide.innerHTML = `
                <div class="card card-animate overflow-hidden">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                 
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-3">${supplier.name}
                                <div class="flex-shrink-0 d-flex align-items-center ml-2">
                                     <img id="user-avatar" src="{{ URL::asset('build/images/users/orbico.png') }}" class="rounded-circle shadow-sm mb-1" width="40" height="40" alt="Korisnički avatar">
                                 </div></p>
                                 
                                <h4 class="fs-10 fw-semibold ff-secondary mb-0">
                                    <i class="ri-map-pin-line text-info me-1"></i>${supplier.address}
                                </h4>
                            </div>
                            <div class="flex-shrink-0 d-flex align-items-center">
                                <canvas class="scan-chart" width="80" height="80"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            container.appendChild(slide);
        });

        // Re-initialize scan charts
        renderScanCharts();

        // Initialize Swiper after DOM is populated
        const swiper = new Swiper(".mySwiper", {
            slidesPerView: 6,
            spaceBetween: 4,
            autoplay: {
                delay: 2000, // Slide every 3 seconds
                disableOnInteraction: false // Keeps autoplay running after manual slide
            },
            loop: true,
        
            
            navigation: false,
            breakpoints: {
                768: { slidesPerView: 2 },
                992: { slidesPerView: 4 },
                1200: { slidesPerView: 6 }
            }
        });

      
    } catch (error) {
        console.error("Error fetching suppliers:", error);
    }
});
</script>








@endsection
