@extends('layouts.layouts-horizontal')

@section('title')
@lang('translation.horizontal')
@endsection

@section('css')
<link href="{{ URL::asset('build/libs/jsvectormap/jsvectormap.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<style>
    /* Optional: Remove the margin-right for the last slide to avoid overflow */
    .mySwiper .swiper-slide:last-child {
        margin-right: 0;
    }
</style>



@endsection

@section('content')


<!-- Top part -->

<div class="col-xl-12">
    <div class="card border-0 rounded-0 shadow-0 h-100 mt-1">
        <div class="row g-0">
            <!-- Left Columns -->
            <div class="col-md-2 col-6 border-end border-0 order-2 order-md-0 bg-white card-animate mt-lg-0 mt-md-0 mt-3">
                <div class="d-flex card  rounded-0 m-0 flex-column h-100">
                    <div class="bg-danger text-white text-center py-1 rounded-0">
                        <i class="ri-alert-line me-1"></i>
                        <span><b>3</b> dostupna. Nadopuni!</span>
                    </div>
                    <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center p-2">
                        <h6 class="text-muted text-uppercase fs-11 mb-1" style="margin-top: -10px;">Dostupna AI
                            skeniranja</h6>
                        <div class="d-flex align-items-center justify-content-center" style="margin-top: 20px;">
                            <i class="fas fa-wand-magic-sparkles text-info mb-1" style="font-size: 35px;"></i>
                            <h3 class="mb-0 ms-2"><span class="counter-value" id="remainScans">0</span></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2 col-6 border-0 order-3 order-md-0 bg-white card-animate mt-lg-0 mt-md-0 mt-3">
                <a href="moje-fakture">
                    <div class="d-flex card m-0  rounded-0 flex-column h-100">
                        <div class="bg-info text-white text-center py-1 rounded-0">
                            <i class=" ri-arrow-up-s-line me-1"></i>
                            <span>Pregledaj sve</span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center p-2">
                            <h6 class="text-muted text-uppercase fs-11 mb-1">Broj skeniranih faktura</h6>
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="ri-file-text-line  text-info mb-1" style="font-size: 45px"></i>
                                <h3 class="mb-0 ms-2"><span id="usedScans" class="counter-value">0</span></h3>
                            </div>
                        </div>

                    </div>
                </a>
            </div>

            <!-- Middle Avatar Section -->
            <div class="col-md-4 border-end order-1 order-md-0 d-flex align-items-center border-0 rounded-0 alert alert-light p-1 mb-2 m-lg-0">
                <div class="p-2 text-center d-flex flex-column h-100 w-100 justify-content-center align-items-center">
                    <div class="card-body text-center p-2">
                        <img id="user-avatar" src="{{ URL::asset('build/images/users/avatar-1.jpg') }}"
                            class="rounded-circle shadow-sm mb-2" width="60" height="60" alt="Korisnički avatar">
                        <h6 class="fw-bold mb-1 mt-1" id="welcome-user">Dobrodošli na deklarant.ba!</h6>
                        <p class="fw-semibold fs-7 mb-1 text-info" id="user-package-display">
                            Učitavanje paketa...
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 w-100 p-0 p-lg-1">
                        <div class="d-flex justify-content-center gap-2 w-100">
                            <a href="cijene-paketa"
                                class="btn btn-info text-white w-50 btn-sm d-flex align-items-center justify-content-center">
                                <i class="fas fa-circle-chevron-up fs-6 me-1"></i> <span class="fs-6">Nadogradi
                                    paket</span>
                            </a>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#scanModal"
                                class="btn btn-info w-50 animated-btn btn-sm d-flex align-items-center justify-content-center">
                                <i class="fas fa-wand-magic-sparkles fs-6 me-1" style="font-size:10px;"></i>
                                <span class="fs-6"> Skeniraj fakturu sa AI</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Columns remain unchanged -->

            <div class="col-md-2 col-6 border-end border-0 order-4 order-md-0 bg-white card-animate mt-lg-0 mt-md-0 mt-4">
                <a href="moji-dobavljaci">
                    <div class="d-flex card  rounded-0 m-0 flex-column h-100">
                        <div class="bg-info text-white text-center py-1 rounded-0">
                            <i class=" ri-arrow-up-s-line me-1"></i>
                            <span>Pregledaj sve</span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center p-2">
                            <h6 class="text-muted text-uppercase fs-11 mb-1">Moji dobavljači</h6>
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="ri-truck-line text-info" style="font-size: 45px"></i>
                                <h3 class="mb-0 ms-2"><span class="counter-value" id="totalSuppliers">0</span></h3>
                            </div>
                        </div>
                    </div>
                </a>
            </div>


            <div class="col-md-2 col-6 border-0 order-5 order-md-0 bg-white card-animate mt-lg-0 mt-md-0 mt-4">
                <a href="kursna-lista">
                    <div class="d-flex  card m-0 rounded-0 flex-column h-100">
                        <div class="bg-info text-white text-center py-1 rounded-0">
                            <i class="ri-arrow-up-s-line me-1"></i>
                            <span>Pregledaj sve</span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center p-2">
                            <h6 class="text-muted text-uppercase fs-11 mb-1">Broj carinskih tarifa</h6>
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="ri-barcode-box-line text-info" style="font-size: 45px"></i>
                                <h3 class="mb-0 ms-2"><span class="counter-value" data-target="128">0</span></h3>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</div>

<!-- Mid part of layout -->

<div class="row g-1 mt-2">
    <!-- Card 1: Izvršena skeniranja -->
    <div class="col-xl-3 col-md-6">
        <div class="card rounded-0 card-animate overflow-hidden">
            <div class="position-absolute start-0" style="z-index: 0;">
                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120" width="200" height="120">
                    <style>
                        .s0 {
                            opacity: .05;
                            fill: var(--vz-info);
                        }
                    </style>
                    <path class="s0"
                        d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4z" />
                </svg>
            </div>
            <div class="card-body" style="z-index:1;">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Izvršena skeniranja</p>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            <span id="usedScansValue">0</span>/<span id="totalScansValue">0</span>
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
        <div class="card rounded-0 card-animate overflow-hidden">
            <div class="position-absolute start-0" style="z-index: 0;">
                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120" width="200" height="120">
                    <style>
                        .s0 {
                            opacity: .05;
                            fill: var(--vz-info);
                        }
                    </style>
                    <path class="s0"
                        d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4z" />
                </svg>
            </div>
            <div class="card-body" style="z-index:1;">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Broj spašenih faktura</p>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            <span class="counter-value" id="totalInvoices" data-target="45">0</span><span
                                class="counter-value">/500</span>
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
        <div class="card rounded-0 card-animate overflow-hidden">
            <div class="position-absolute start-0" style="z-index: 0;">
                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120" width="200" height="120">
                    <style>
                        .s0 {
                            opacity: .05;
                            fill: var(--vz-info);
                        }
                    </style>
                    <path class="s0"
                        d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4z" />
                </svg>
            </div>
            <div class="card-body" style="z-index:1;">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Zakonskih dokumenata</p>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            <span class="counter-value" data-target="19">19</span>
                        </h4>
                    </div>
                    <div style="width: 80px; height: 80px;" class="d-flex align-items-center justify-content-center">
                        <i class="ri-scales-line text-info" style="font-size: 45px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Vrijeme skeniranja -->
    <div class="col-xl-3 col-md-6">
        <div class="card rounded-0 card-animate overflow-hidden">
            <div class="position-absolute start-0" style="z-index: 0;">
                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120" width="200" height="120">
                    <style>
                        .s0 {
                            opacity: .05;
                            fill: var(--vz-info);
                        }
                    </style>
                    <path class="s0"
                        d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4z" />
                </svg>
            </div>
            <div class="card-body" style="z-index:1;">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Prosječna brzina skeniranja</p>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            <span class="counter-value" id="scanSpeedValue">0.00</span>
                        </h4>
                    </div>
                    <div style="width: 80px; height: 80px;" class="d-flex align-items-center justify-content-center">
                        <i class="ri-timer-flash-line text-info" style="font-size: 45px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Bottom  part (last 2 parts) of layout -->

<div class="swiper g-1 mySwiper mt-2">
    <div class="swiper-wrapper" id="supplierCardsContainer">
        <!-- Dynamic cards will be injected here as .swiper-slide -->
    </div>
</div>

<div class="row mt-2">
    <div class="col-12 px-1">
        <div class="row mb-4 g-0 mx-1">
            <!-- LEFT COLUMN -->
            <div class="col-xl-6">
                <div class="row g-1 mx-0">
                    <!-- 4 cards in 2 rows -->
                    <div class="col-md-6">
                        <div class="card rounded-0 h-100 ">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0 fs-6">Zahtjevi za inspekcijski nadzor</h5>
                                <a class="text-muted fs-6">Pogledaj sve</a>
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center">

                                <div class="row d-flex text-center text-truncate">
                                    <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i>
                                        <div>Dokument 1 </div>
                                    </div>
                                    <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i>
                                        <div>Dokument 2 </div>
                                    </div>
                                    <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i>
                                        <div>Dokument 3</div>
                                    </div>
                                    <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i>
                                        <div>Dokument 4</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card rounded-0 h-100">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0 fs-6">Carinjenje po nepotpunoj izjavi</h5>
                                <a class="text-muted fs-6">Pogledaj sve</a>
                            </div>
                            <div class="card-body d-flex justify-content-center align-items-center">

                                <div class="row d-flex text-center text-truncate">
                                    <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i>
                                        <div>Dokument 1 </div>
                                    </div>
                                    <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i>
                                        <div>Dokument 2 </div>
                                    </div>
                                    <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i>
                                        <div>Dokument 3</div>
                                    </div>
                                    <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i>
                                        <div>Dokument 4</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card rounded-0 h-100">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0 fs-6">Izjave za carinjenje pošiljki</h5>
                                <a class="text-muted fs-6">Pogledaj sve</a>
                            </div>
                            <div class="card-body d-flex justify-content-center align-items-center">

                                <div class="row d-flex text-center text-truncate">
                                    <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i>
                                        <div>Dokument 1 </div>
                                    </div>
                                    <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i>
                                        <div>Dokument 2 </div>
                                    </div>
                                    <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i>
                                        <div>Dokument 3</div>
                                    </div>
                                    <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i>
                                        <div>Dokument 4</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card rounded-0 h-100">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0 fs-6">Obrasci</h5>
                                <a class="text-muted fs-6">Pogledaj sve</a>
                            </div>
                            <div class="card-body d-flex justify-content-center align-items-center">

                                <div class="row d-flex text-center text-truncate">
                                    <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i>
                                        <div>Dokument 1 </div>
                                    </div>
                                    <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i>
                                        <div>Dokument 2 </div>
                                    </div>
                                    <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i>
                                        <div>Dokument 3</div>
                                    </div>
                                    <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i>
                                        <div>Dokument 4</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="col-xl-6 d-flex flex-column ">
                <div class="row g-1 flex-fill mx-0">
                    <div class="col-md-6 ">
                        <div class="card rounded-0 w-100 h-100 card-animate ">
                            <div class="card-header">
                                <h5 class="mb-0">Zadnje korištene tarife</h5>
                            </div>
                            <div class="card-body align-items-center text-truncate">
                                <div class="tariff-list">
                                    <!-- Dynamically populated supplier data goes here -->
                                </div>

                                <div class="card-footer mt-1 pt-0 pb-0 d-flex justify-content-center">

                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex card-animate">
                        <div class="card rounded-0 w-100 h-100">
                            <div class="card-header">
                                <h5 class="mb-0">Zadnje korišteni dobavljači</h5>
                            </div>
                            <div class="card-body">
                                <div class="suppliers-list">
                                    <!-- Dynamically populated supplier data goes here -->
                                </div>
                                <div class="card-footer mt-1 pt-0 pb-0 d-flex justify-content-center">

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
<div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title w-100" id="scanModalLabel"><i class="fas fa-wand-magic-sparkles fs-6 me-1"
                        style="font-size:10px;"></i>Skeniraj fakturu sa AI</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvori"></button>
            </div>
            <div class="modal-body d-flex justify-content-center">
                <div class="dropzone" id="dropzone">
                    <input type="file" id="fileInput" multiple>
                    <div class="corner corner-top-left"></div>
                    <div class="corner corner-top-right"></div>
                    <div class="corner corner-bottom-left"></div>
                    <div class="corner corner-bottom-right"></div>

                    <div class="text-center" id="dropzone-content">
                        <i class="ri-file-2-line text-info fs-1"></i>
                        <p class="mt-3">Prevucite dokument ovdje ili kliknite kako bi uploadali i skenirali vašu fakturu
                        </p>
                    </div>

                    <div class="file-list" id="fileList" style="display: none;"></div>

                    <div class="progress mt-3 w-100" id="uploadProgressContainer" style="display: none;">
                        <div id="uploadProgressBar" class="progress-bar bg-info" role="progressbar" style="width: 0%">0%
                        </div>
                    </div>

                    <div id="scanningLoader" class="mt-4 text-center d-none">
                        <div class="spinner-border text-info" role="status" style="width: 3rem; height: 3rem;"></div>
                        <p class="mt-3 fw-semibold" id="scanningText">Skeniranje fakture...</p>
                        <div id="successCheck" class="d-none mt-3">
                            <i class="ri-checkbox-circle-fill text-success fs-1 animate__animated animate__zoomIn"></i>
                            <p class="text-success fw-semibold mt-2">Uspješno skenirano!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>











@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/dashboard-analytics.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="{{ URL::asset('build/libs/chart.js/chart.umd.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/chartjs.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<!-- Swiper CSS -->


<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", async function() {
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
            console.log("Fetched stats:", stats);

            // Get the suppliers data, ensure no demo supplier is added
            const suppliers = stats.supplier_profit_changes || [];

            // Limit to 5 suppliers
            const limitedSuppliers = suppliers.slice(-5);

            const suppliersContainer = document.querySelector(".suppliers-list");

            if (suppliersContainer) {
                suppliersContainer.innerHTML = ''; // Clear existing content

                limitedSuppliers.forEach(supplier => {
                    console.log("Rendering supplier:", supplier);

                    // Convert percentage_change to a number
                    const percentageChange = parseFloat(supplier.percentage_change);

                    // Ensure we handle both positive and negative values
                    const isPositive = percentageChange >= 0;
                    const growthClass = isPositive ? "text-success" : "text-danger";
                    const arrowIcon = isPositive ? "ri-arrow-up-line" : "ri-arrow-down-line";

                    const supplierElement = document.createElement("div");
                    supplierElement.classList.add("d-flex", "justify-content-between", "align-items-center", "mb-2");

                    supplierElement.innerHTML = `
                                <div>
                                    <div class="fw-semibold">${supplier.name}</div>
                                    <div class="text-muted fs-12">${supplier.owner ?? 'Nepoznat vlasnik'}</div>
                                </div>
                                <div class="${growthClass} fs-13">
                                    ${isNaN(percentageChange) ? 'N/A' : percentageChange.toFixed(1)}% <i class="${arrowIcon} ms-1"></i>
                                </div>
                            `;

                    suppliersContainer.appendChild(supplierElement);
                });
            }
        } catch (error) {
            console.error("Error fetching supplier data:", error);
        }
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", async function() {
        const token = localStorage.getItem("auth_token");
        const user = JSON.parse(localStorage.getItem("user"));

        if (!token || !user?.id) {
            console.warn("User or token missing.");
            return;
        }

        try {
            const res = await axios.get(`/api/invoices/users/${user.id}`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            const invoices = res.data || [];

            // Filter invoices with non-empty items and take up to 5
            const validItems = invoices
                .filter(inv => Array.isArray(inv.items) && inv.items.length > 0)
                .flatMap(inv => inv.items.map(item => ({
                    code: item.best_customs_code_matches?.[0] || "Nepoznat kod",
                    name: item.item_description_original || item.item_description || "Nepoznat naziv",
                    vat: "",
                })))
                .slice(0, 5);

            const container = document.querySelector(".tariff-list");
            container.innerHTML = "";

            validItems.forEach(item => {
                container.innerHTML += `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <div class="fw-semibold">${item.code}</div>
                            <div class="text-muted fs-12">${item.name}</div>
                        </div>
                        <div class="text-success fs-13">
                             
                        </div>
                    </div>
                `;
            });

        } catch (err) {
            console.error("Greška pri dohvaćanju faktura:", err);
        }
    });
</script>




<!-- new doughnut logic -->

<script>
    document.addEventListener("DOMContentLoaded", async function() {
        const centerTextPlugin = {
            id: "centerText",
            beforeDraw: function(chart) {
                const width = chart.width,
                    height = chart.height,
                    ctx = chart.ctx;

                ctx.restore();
                const fontSize = ((height / 8) * 2).toFixed(2);
                ctx.font = fontSize + "px sans-serif";
                ctx.textBaseline = "middle";
                ctx.textAlign = "center";

                const dataset = chart.data.datasets[0];
                const total = dataset.data.reduce((acc, val) => acc + val, 0);
                const percentage = Math.round((dataset.data[0] / total) * 100);

                const text = percentage + "%";
                const textX = Math.round(width / 2);
                const textY = Math.round(height / 2);

                ctx.fillStyle = "#299cdb";
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
                    labels: ["Iskorišteno", "Preostalo"],
                    datasets: [{
                        data: [usedPercentage, remaining],
                        backgroundColor: ["#299cdb", "#d6f0fa"],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: "70%",
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: false
                        }
                    }
                },
                plugins: [centerTextPlugin]
            });
        }

        const user = JSON.parse(localStorage.getItem("user"));
        const token = localStorage.getItem("auth_token");

        if (!user || !token) {
            console.warn("Token ili korisnik nije pronađen.");
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
            const usedScans = stats.used_scans ?? 0;
            const remainingScans = stats.remaining_scans ?? 0;
            const totalScans = usedScans + remainingScans;

            document.getElementById("usedScansValue").innerText = usedScans;
            document.getElementById("totalScansValue").innerText = totalScans;

            const usedPercentage = totalScans > 0 ? (usedScans / totalScans) * 100 : 0;

            createDoughnutChart("doughnut1", usedPercentage);

        } catch (err) {
            console.error("Greška prilikom dohvaćanja statistike:", err);
        }
    });
</script>


<!-- doughnut2 -->

    <script>
        document.addEventListener("DOMContentLoaded", async function() {
            const user = JSON.parse(localStorage.getItem("user"));
            const token = localStorage.getItem("auth_token");
        
            if (!user || !token) {
                console.warn("User or token missing in localStorage.");
                return;
            }
        
            const invoicesUrl = `/api/invoices/users/${user.id}`;
            const packagesUrl = `/api/user-packages`;
        
            try {
                const packageRes = await axios.get(packagesUrl, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
            
                const userPackages = packageRes.data?.data || [];
                const userPackage = userPackages.find(p => p.active);
                let invoiceLimit = 0;
            
                if (userPackage?.package?.name?.toLowerCase() === 'gobig') invoiceLimit = 500;
                else if (userPackage?.package?.name?.toLowerCase() === 'startup') invoiceLimit = 200;
                else if (userPackage?.package?.name?.toLowerCase() === 'business') invoiceLimit = Infinity;
            
                const invoiceRes = await axios.get(invoicesUrl, {
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                });
            
                const invoices = invoiceRes.data || [];
                const invoiceCount = invoices.length;
            
                const invoiceEl = document.getElementById("totalInvoices");
                if (invoiceEl) invoiceEl.innerText = invoiceCount;
            
                const denominatorEl = invoiceEl.nextElementSibling;
                if (denominatorEl && invoiceLimit !== Infinity) {
                    denominatorEl.innerText = `/${invoiceLimit}`;
                } else if (denominatorEl) {
                    denominatorEl.innerText = "/∞";
                }
            
                const usedPercentage = invoiceLimit === Infinity ? 100 : Math.min((invoiceCount / invoiceLimit) * 100, 100);
                createDoughnutChart("doughnut2", usedPercentage);
            
            } catch (err) {
                console.error("Greška prilikom dohvaćanja faktura ili paketa:", err);
            }
        
            function createDoughnutChart(canvasId, usedPercentage) {
                const ctx = document.getElementById(canvasId).getContext("2d");
                const remaining = 100 - usedPercentage;
            
                new Chart(ctx, {
                    type: "doughnut",
                    data: {
                        labels: ["Iskorišteno", "Preostalo"],
                        datasets: [{
                            data: [usedPercentage, remaining],
                            backgroundColor: ["#299cdb", "#d6f0fa"],
                        }, ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: "70%",
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: false
                            },
                        },
                    },
                    plugins: [{
                        id: "centerText",
                        beforeDraw: function(chart) {
                            const width = chart.width,
                                height = chart.height,
                                ctx = chart.ctx;
                        
                            ctx.restore();
                            const fontSize = ((height / 8) * 2).toFixed(2);
                            ctx.font = fontSize + "px sans-serif";
                            ctx.textBaseline = "middle";
                            ctx.textAlign = "center";
                        
                            const dataset = chart.data.datasets[0];
                            const total = dataset.data.reduce((acc, val) => acc + val, 0);
                            const percentage = Math.round((dataset.data[0] / total) * 100);
                        
                            const text = percentage + "%";
                            const textX = Math.round(width / 2);
                            const textY = Math.round(height / 2);
                        
                            ctx.fillStyle = "#299cdb";
                            ctx.fillText(text, textX, textY);
                            ctx.save();
                        },
                    }, ],
                });
            }
        });
    </script>


<!-- carinske tarife -->
<script>
    document.addEventListener("DOMContentLoaded", async function() {
        try {
            const response = await fetch("/storage/data/tariff.json");
            const tariffData = await response.json();

            // Broji samo one koji imaju ključ "Tarifna oznaka"
            const totalTariffs = tariffData.filter(item => item["Tarifna oznaka"]).length;

            const tariffCounterEl = document.querySelector(".counter-value[data-target]");
            if (tariffCounterEl) {
                tariffCounterEl.setAttribute("data-target", totalTariffs);
                tariffCounterEl.innerText = totalTariffs;
            }


        } catch (error) {
            console.error("❌ Greška pri učitavanju tarifa:", error);
        }
    });
</script>

<!-- brzina skeniranja -->

<script>
    document.addEventListener("DOMContentLoaded", async function() {
        const token = localStorage.getItem("auth_token");
        if (!token) return console.warn("Missing auth token");

        try {
            const packageRes = await axios.get("/api/user-packages", {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            const userPackages = packageRes.data?.data || [];
            const userPackage = userPackages.find(p => p.active);
            const packageName = userPackage?.package?.name?.toLowerCase();

            const scanSpeedEl = document.getElementById("scanSpeedValue");

            if (scanSpeedEl) {
                let speed = "N/A";
                switch (packageName) {
                    case "startup":
                        speed = "20 sekundi";
                        break;
                    case "gobig":
                        speed = "10 sekundi";
                        break;
                    case "business":
                        speed = "4 sekunde";
                        break;
                }
                scanSpeedEl.innerText = speed;
            }

        } catch (err) {
            console.error("Greška prilikom dohvaćanja paketa:", err);
        }
    });
</script>










<!-- Used scans | remainining scans -->
<script>
    document.addEventListener("DOMContentLoaded", async function() {
        const user = JSON.parse(localStorage.getItem("user"));
        const token = localStorage.getItem("auth_token");

        console.log("[INIT] Provjera lokalne pohrane...");
        console.log(" Korisnik:", user);
        console.log(" Token:", token?.substring(0, 25) + "..."); // da ne ispiše cijeli token

        if (!user || !token) {
            console.warn(" User ili token nedostaje u localStorage.");
            return;
        }

        const API_URL = `/api/statistics/users/${user.id}`;
        console.log(` Pozivam API: ${API_URL}`);

        try {
            const response = await axios.get(API_URL, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            console.log(" API response:", response);
            const stats = response.data || {};

            console.log(" Parsed statistike:", stats);

            const fields = {
                totalSuppliers: stats.total_suppliers ?? 0,
                totalInvoices: stats.total_invoices ?? 0,
                usedScans: stats.used_scans ?? 0,
                remainScans: stats.remaining_scans ?? 0
            };

            console.log("📌 Vrijednosti za prikaz u DOM-u:", fields);

            Object.entries(fields).forEach(([id, value]) => {
                const el = document.getElementById(id);
                if (el) {
                    console.log(`➡️ Ažuriram #${id} na:`, value);
                    el.innerText = value;
                } else {
                    console.warn(`⚠️ Element s ID '${id}' nije pronađen u DOM-u.`);
                }
            });

        } catch (error) {
            console.error("❌ Greška pri dohvaćanju statistike:", error);
        }
    });
</script>


<!-- statistics -->
<script>
    document.addEventListener("DOMContentLoaded", async function() {


        const API_URL = `/api/statistics`;
        const token = localStorage.getItem("auth_token");

        if (!token) {
            console.warn("No token found in localStorage.");
            return;
        }

        try {
            const response = await axios.get(API_URL, {
                headers: {
                    Authorization: `Bearer ${token}`
                }

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



<!-- avatar upload -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const user = JSON.parse(localStorage.getItem("user"));

        if (user) {
            const welcome = document.getElementById("welcome-user");
            if (welcome) {
                welcome.innerText = `Dobrodošao/la na deklarant.ba, ${user.username}!`;
            }

            const avatar = document.getElementById("user-avatar");
            if (avatar) {
                const avatarUrl = `/storage/uploads/avatars/${user.avatar}`;
                // Check if the image loads correctly, fallback if not
                const testImg = new Image();
                testImg.onload = function() {
                    avatar.src = avatarUrl;
                };
                testImg.onerror = function() {
                    avatar.src = "/build/images/users/avatar-1.jpg";
                };
                testImg.src = avatarUrl;
            }
        }
    });
</script>


<!-- suppliers -->
<script>
    function renderScanCharts() {
        const canvases = document.querySelectorAll(".scan-chart");

        const staticLabels = ["Jan", "Feb", "Mar", "Apr", "Maj", "Jun", "Jul", "Avg", "Sep", "Okt", "Nov", "Dec"];
        const staticData = [1200, 1300, 1250, 1450, 1600, 1400, 1700, 1800, 1750, 1900, 2000, 2100];

        canvases.forEach((canvas) => {
            const ctx = canvas.getContext("2d");

            new Chart(ctx, {
                type: "line",
                data: {
                    labels: staticLabels,
                    datasets: [{
                        data: staticData,
                        backgroundColor: "rgba(41, 156, 219, 0.15)",
                        borderColor: "#299cdb",
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            display: false
                        },
                        y: {
                            display: false
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: false
                        }
                    }
                }
            });
        });
    }

    document.addEventListener("DOMContentLoaded", async function() {
        const token = localStorage.getItem("auth_token");

        if (!token) {
            console.warn("Missing auth token.");
            return;
        }

        try {
            const response = await axios.get(`/api/suppliers`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            let suppliers = Array.isArray(response.data) ? response.data : response.data.data || [];

            const fallbackSuppliers = [{
                    name: "Generic Co.",
                    owner: "N/A",
                    address: "Sarajevo"
                },
                {
                    name: "Example Inc.",
                    owner: "N/A",
                    address: "Mostar"
                },
                {
                    name: "Placeholder Ltd.",
                    owner: "N/A",
                    address: "Tuzla"
                },
                {
                    name: "Test Supplier",
                    owner: "N/A",
                    address: "Zenica"
                },
                {
                    name: "Demo Group",
                    owner: "N/A",
                    address: "Bihać"
                },
                {
                    name: "ACME Corp",
                    owner: "N/A",
                    address: "Travnik"
                }
            ];

            suppliers = suppliers.length < 10 ?
                suppliers.concat(fallbackSuppliers.slice(0, 10 - suppliers.length)) :
                suppliers.slice(-10);

            const container = document.getElementById("supplierCardsContainer");
            container.innerHTML = "";

            suppliers.forEach((supplier) => {
                const firstLetter = supplier.name?.[0]?.toUpperCase() || "?";

                const avatarHTML = `
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm"
                         style="width: 40px; height: 40px; background-color: #299cdb; font-size: 16px;">
                         ${firstLetter}
                    </div>`;

                const slide = document.createElement("div");
                slide.className = "swiper-slide";

                slide.innerHTML = `
                    <div class="card rounded-0 card-animate overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-3 d-flex align-items-center">
                                        ${supplier.name}
                                        <span class="ms-2">${avatarHTML}</span>
                                    </p>
                                    <h4 class="fs-10 fw-semibold ff-secondary mb-0 text-truncate">
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

            renderScanCharts();

            new Swiper(".mySwiper", {
                slidesPerView: 6,
                spaceBetween: 4,
                autoplay: {
                    delay: 2000,
                    disableOnInteraction: false
                },
                loop: true,
                navigation: false,
                breakpoints: {
                    768: {
                        slidesPerView: 2
                    },
                    992: {
                        slidesPerView: 4
                    },
                    1200: {
                        slidesPerView: 6
                    }
                }
            });

        } catch (error) {
            console.error("Error fetching suppliers:", error);
        }
    });
</script>



<!-- Upload data -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const token = localStorage.getItem("auth_token");
        if (!token) {
            Swal.fire({
                icon: 'warning',
                title: 'Niste prijavljeni',
                text: 'Molimo ulogujte se.',
                showConfirmButton: true,
            }).then(() => {
                window.location.href = "/login";
            });
            return;
        }

        const dropzone = document.getElementById("dropzone");
        const fileInput = document.getElementById("fileInput");
        const fileList = document.getElementById("fileList");
        const dropzoneContent = document.getElementById("dropzone-content");
        const progressContainer = document.getElementById("uploadProgressContainer");
        const progressBar = document.getElementById("uploadProgressBar");
        const scanButton = document.getElementById("startScanBtn");

        if (scanButton) {
            scanButton.addEventListener("click", function() {
                fileInput.click();
            });
        }

        function updateFileList(files) {
            fileList.innerHTML = "";
            if (files.length > 0) {
                fileList.style.display = "block";
                dropzoneContent.style.display = "none";
            } else {
                fileList.style.display = "none";
                dropzoneContent.style.display = "block";
            }

            Array.from(files).forEach((file, index) => {
                const fileItem = document.createElement("div");
                fileItem.classList.add("file-item");

                const fileName = document.createElement("span");
                fileName.textContent = file.name;

                const removeBtn = document.createElement("span");
                removeBtn.textContent = "×";
                removeBtn.classList.add("remove-file");
                removeBtn.dataset.index = index;

                removeBtn.addEventListener("click", function() {
                    let dt = new DataTransfer();
                    let fileArray = Array.from(fileInput.files);
                    fileArray.splice(index, 1);
                    fileArray.forEach(f => dt.items.add(f));
                    fileInput.files = dt.files;
                    updateFileList(fileInput.files);
                });

                fileItem.appendChild(fileName);
                fileItem.appendChild(removeBtn);
                fileList.appendChild(fileItem);
            });
        }

        function uploadFiles(files) {
            const formData = new FormData();
            Array.from(files).forEach(file => formData.append('file', file));

            progressContainer.style.display = "block";
            progressBar.style.width = "0%";
            progressBar.innerText = "0%";

            let fakeProgress = 0;
            const fakeInterval = setInterval(() => {
                fakeProgress += 5;
                if (fakeProgress > 100) fakeProgress = 100;

                progressBar.style.width = fakeProgress + "%";
                progressBar.innerText = fakeProgress + "%";

                if (fakeProgress === 100) {
                    clearInterval(fakeInterval);
                }
            }, 150);

            fetch('/api/storage/invoice-uploads', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        Authorization: `Bearer ${token}`
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error("Upload failed");
                    return response.json();
                })
                .then(data => {
                    console.log('Upload successful:', data);
                    Swal.fire({
                        icon: "success",
                        title: "Dokument uspješno uploadan!",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        if (data.invoice_id) {
                            localStorage.setItem("scan_invoice_id", data.invoice_id);
                        }
                        window.location.href = "/apps-invoices-create";
                    });
                })
                .catch(error => {
                    console.error('Upload error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Greška',
                        text: 'Došlo je do greške prilikom uploada dokumenta.'
                    });
                    progressContainer.style.display = "none";
                });
        }

        dropzone.addEventListener("dragover", e => {
            e.preventDefault();
            dropzone.classList.add("bg-light");
        });

        dropzone.addEventListener("dragleave", () => {
            dropzone.classList.remove("bg-light");
        });

        dropzone.addEventListener("drop", e => {
            e.preventDefault();
            dropzone.classList.remove("bg-light");
            let dt = new DataTransfer();
            Array.from(fileInput.files).forEach(f => dt.items.add(f));
            Array.from(e.dataTransfer.files).forEach(f => dt.items.add(f));
            fileInput.files = dt.files;
            updateFileList(fileInput.files);
            uploadFiles(fileInput.files);
        });

        fileInput.addEventListener("change", () => {
            updateFileList(fileInput.files);
            uploadFiles(fileInput.files);
        });
    });
</script>


<!-- user package -->
<script>
    document.addEventListener("DOMContentLoaded", async function() {
        const token = localStorage.getItem("auth_token");
        if (!token) return console.warn("Nedostaje auth token");

        try {
            const res = await axios.get("/api/user-packages", {
                headers: {
                    Authorization: `Bearer ${token}`,
                }
            });

            const userPackages = res.data?.data || [];
            const displayElement = document.getElementById("user-package-display");

            if (!displayElement) return;

            if (userPackages.length > 0) {
                const userPackage = userPackages.find(p => p.active) || userPackages[0];
                const packageName = userPackage?.package?.name || "Nepoznat";

                displayElement.innerHTML = `Tvoj trenutni paket je <b>${packageName}</b>`;
            } else {
                displayElement.innerHTML = `Još uvijek nisi odabrao paket? <a href="/cijene-paketa" class="fw-bold text-decoration-underline text-info">Odaberi ovdje</a>`;
            }

        } catch (err) {
            console.error("Greška pri dohvaćanju paketa korisnika:", err);
            const displayElement = document.getElementById("user-package-display");
            if (displayElement) {
                displayElement.innerHTML = `Greška prilikom dohvaćanja paketa.`;
            }
        }
    });
</script>








@endsection