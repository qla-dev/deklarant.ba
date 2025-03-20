@extends('layouts.layouts-horizontal')

@section('title')
    @lang('translation.horizontal')
@endsection

@section('css')
    <link href="{{ URL::asset('build/libs/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet">
    <style>

        .my-alert {
            
            transition: background-color 0.6s ease-in-out, color 0.6s ease-in-out;
        }
        .my-card:hover .my-alert {
            background-color: #299cdb !important; /* bg-info */
            
            color: white !important;
        }
        


    </style>
@endsection

@section('content')


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
                        <h6 class="text-muted text-uppercase fs-11 mb-1">Ukupno faktura</h6>
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="ri-file-text-line fs-1 text-info mb-1"></i>
                            <h3 class="mb-0 ms-2"><span class="counter-value" data-target="128"></span></h3>
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
                        <h6 class="text-muted text-uppercase fs-11 mb-1">Ukupno skeniranja</h6>
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="ri-scan-2-line fs-1 text-info mb-1"></i>
                            <h3 class="mb-0 ms-2"><span class="counter-value" data-target="128"></span></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Srednji dio sa avatarom -->
            <div class="col-md-4 border-end d-flex align-items-center border-0 rounded-0 alert alert-light p-1 m-0">
                <div class="p-2 text-center d-flex flex-column h-100 w-100 justify-content-center align-items-center">
                    <div class="card-body text-center">
                        <img src="@if (Auth::user()->avatar != ''){{ URL::asset('images/' . Auth::user()->avatar) }}@else{{ URL::asset('build/images/users/avatar-1.jpg') }}@endif"
                            class="rounded-circle shadow-sm mb-1" width="60" height="60" alt="Korisnički avatar">
                        <h6 class="fw-bold text-dark mb-1">Dobrodošli na eDeklarant, {{ Auth::user()->name }}!</h6>
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
                            <h3 class="mb-0 ms-2"><span class="counter-value" data-target="128"></span></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="d-flex flex-column h-100">
                    
                    <!-- Sadržaj centriran ispod -->
                    <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center p-2">
                        <h6 class="text-muted text-uppercase fs-11 mb-1">Vrijeme skeniranja</h6>
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="ri-time-line fs-1 text-info"></i>
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
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Dostupna skeniranja</p>
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
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Broj faktura</p>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            <span class="counter-value" data-target="45">0</span>
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


     

    

    
    






@endsection

@section('script')
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/dashboard-analytics.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script src="{{ URL::asset('build/libs/chart.js/chart.umd.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/chartjs.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script src="{{ URL::asset('build/js/pages/dashboard-nft.init.js') }}"></script>


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

@endsection
