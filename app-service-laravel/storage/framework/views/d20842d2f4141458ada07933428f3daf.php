

<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.horizontal'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(URL::asset('build/libs/jsvectormap/jsvectormap.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="text-center my-4">
    <!-- User Avatar -->
    <img src="<?php if(Auth::user()->avatar != ''): ?><?php echo e(URL::asset('images/' . Auth::user()->avatar)); ?><?php else: ?><?php echo e(URL::asset('build/images/users/avatar-1.jpg')); ?><?php endif; ?>" 
         class="rounded-circle border border-info shadow-sm mb-3" width="100" height="100" alt="User Avatar">

    <!-- Welcome Message -->
    <h2 class="fw-bold text-info">Dobrodošli na eDeklarant, <?php echo e(Auth::user()->name); ?>!</h2>

    <!-- Subtitle -->
    <p class="text-muted fs-5">Iskoristite sve mogućnosti koje platforma nudi.</p>
</div>

<!-- Nadogradite paket i Smart Scan kartice -->
<div class="row justify-content-center mt-4">
    <!-- Kartica za nadogradnju paketa -->
    <div class="col-md-6">
        <div class="card card-animate border border-info rounded-4 shadow-lg h-100 d-flex flex-column">
            <div class="alert alert-info border-0 rounded-top-4 m-0 d-flex justify-content-center py-2">
                <span class="fw-semibold">Vaš trenutni paket je <b>Starter</b></span>
            </div>
            <div class="card-body text-center py-4">
                <img src="<?php echo e(URL::asset('build/images/analyze.png')); ?>" class="img-fluid mb-3" width="90" alt="Upgrade Image">
                <h5 class="fw-semibold mb-3">Nadogradite paket</h5>
                <a href="pages-pricing" class="btn btn-info w-100">
                    <i class="ri-arrow-up-circle-line fs-18 me-2"></i> Nadogradite paket
                </a>
            </div>
        </div>
    </div>

    <!-- Kartica za Smart Scan -->
    <div class="col-md-6">
        <div class="card card-animate border border-info rounded-4 shadow-lg h-100 d-flex flex-column">
            <div class="alert alert-info border-0 rounded-top-4 m-0 d-flex justify-content-center py-2">
                <span class="fw-semibold">Preostalo još <b>5</b> skeniranja</span>
            </div>
            <div class="card-body text-center py-4">
                <img src="<?php echo e(URL::asset('build/images/screww.png')); ?>" class="img-fluid mb-3" width="90" alt="Scan Image">
                <h5 class="fw-semibold mb-3">Započnite analizu fakture</h5>
                <a href="pages-scan" class="btn btn-info w-100">
                    <i class="ri-qr-scan-2-line fs-18 me-2"></i> Pokreni skeniranje
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistički podaci -->
<div class="row mt-4 g-3">
    <!-- Dostupna skeniranja -->
    <div class="col-md-3">
        <div class="card card-animate rounded-4 border border-info shadow-lg p-3 d-flex flex-column align-items-center text-center">
        <div class="alert alert-info border-0 rounded-top-4 text-center w-100 py-1">
                <span class="fw-semibold">Dostupna skeniranja</span>
            </div>
            <i class="ri-scan-2-line fs-1 text-info mt-2"></i>
            <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="28">0</span></h2>
            <p class="mb-1 text-muted">
                <span class="badge bg-light text-success">
                    <i class="ri-arrow-up-line align-middle"></i> 16.24 %
                </span> vs. prethodni mjesec
            </p>
            <canvas id="doughnut1" class="chartjs-chart" style="width: 50px; height: 50px;"></canvas>
        </div>
    </div>

    <!-- Broj skeniranih faktura -->
    <div class="col-md-3">
        <div class="card card-animate rounded-4 border border-info shadow-lg p-3 d-flex flex-column align-items-center text-center">
            <div class="alert alert-info border-0 rounded-top-4 text-center w-100 py-1">
                <span class="fw-semibold">Broj skeniranih faktura</span>
            </div>
            <i class="ri-file-list-3-line fs-1 text-info mt-2"></i>
            <h2 class="mt-2 ff-secondary fw-semibold">
                <span class="counter-value" data-target="27.66">0</span>k
            </h2>
            <p class="mb-1 text-muted">
                <span class="badge bg-light text-danger">
                    <i class="ri-arrow-down-line align-middle"></i> 3.96 %
                </span> vs. prethodni mjesec
            </p>
            <canvas id="doughnut2" class="chartjs-chart" style="width: 50px; height: 50px;"></canvas>
        </div>
    </div>

    <!-- Broj carinskih tarifa -->
    <div class="col-md-3">
        <div class="card card-animate rounded-4 border border-info shadow-lg p-3 d-flex flex-column align-items-center text-center">
            <div class="alert alert-info border-0 rounded-top-4 text-center w-100 py-1">
                <span class="fw-semibold">Broj carinskih tarifa</span>
            </div>
            <i class="ri-barcode-box-line fs-1 text-info mt-2"></i>
            <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="19">0</span></h2>
            <p class="mb-1 text-muted">
                <span class="badge bg-light text-success">
                    <i class="ri-arrow-up-line align-middle"></i> 9.32 %
                </span> vs. prethodni mjesec
            </p>
            <canvas id="doughnut3" class="chartjs-chart" style="width: 10px; height: 10px;"></canvas>
        </div>
    </div>

    <!-- Broj dobavljača -->
    <div class="col-md-3">
        <div class="card card-animate rounded-4 border border-info shadow-lg p-3 d-flex flex-column align-items-center text-center">
            <div class="alert alert-info border-0 rounded-top-4 text-center w-100 py-1">
                <span class="fw-semibold">Broj dobavljača</span>
            </div>
            <i class="ri-truck-line fs-1 text-info mt-2"></i>
            <h2 class="mt-2 ff-secondary fw-semibold"><span class="counter-value" data-target="12">0</span></h2>
            <p class="mb-1 text-muted">
                <span class="badge bg-light text-danger">
                    <i class="ri-arrow-down-line align-middle"></i> 4.21 %
                </span> vs. prethodni mjesec
            </p>
            <canvas id="doughnut4" class="chartjs-chart" style="width: 30px; height: 30px;"></canvas>
        </div>
    </div>
</div>



<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('build/libs/apexcharts/apexcharts.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/dashboard-analytics.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/chart.js/chart.umd.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/chartjs.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>


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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.layouts-horizontal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\edeklarant\app-service-laravel\resources\views/layouts-horizontal.blade.php ENDPATH**/ ?>