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

     .logo-span {
        font-family: 'Facebook Sans Bold', sans-serif!important;
    }

    .ai-span {
        
        color: #289cdb!important;
        padding: unset!Important;
        background: unset!Important;
            font-size: inherit!important;
    font-style: normal!important;
        margin-bottom: unset!important;
        padding-bottom: !important;
         font-family: 'Facebook Sans Bold', sans-serif!important;
    }

    @media (max-width: 768px) {

  

    .tariff-list, .suppliers-list {
        max-height: 150px!important;
        overflow-y: auto!important;
    }
    }


    /* Optional: Remove the margin-right for the last slide to avoid overflow */
    .mySwiper .swiper-slide:last-child {
        margin-right: 0;
    }

   @media (max-width: 1453.98px) {
  .deklaraciju {
    display: none !important;
  }
}

</style>



@endsection

@section('content')


<!-- Top part -->

<div class="col-xl-12">
    <div class="card border-0 rounded-0 shadow-0 h-100 mt-1 mobile-shadow-remove">
        <div class="row g-0">
            <!-- Left Columns -->
            <div class="col-md-2 col-6 border-end border-0 order-2 order-md-0 card-animate mt-lg-0 mt-md-0 mt-3">
             <a href="cijene-paketa">  
            <div class="d-flex card  rounded-0 m-0 flex-column h-100">
                     @include('components.package-card-statusbar')
                    <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center text-center p-2">
                        <h6 class="text-muted text-uppercase fs-11 mb-1" style="margin-top: -10px;">Dostupna AI
                            skeniranja</h6>
                        <div class="d-flex align-items-center justify-content-center" style="margin-top: 20px;">
                            <i class="fas fa-wand-magic-sparkles text-info mb-1" style="font-size: 35px;"></i>
                            <h3 class="mb-0 ms-2"><span class="counter-value">{{ Auth::user()->getRemainingScans() ?? '0' }}</span></h3>
                        </div>
                    </div>
                </div>
                </a>  
            </div>

            <div class="col-md-2 col-6 border-0 order-3 order-md-0 card-animate mt-lg-0 mt-md-0 mt-3">
                <a href="moje-deklaracije">
                    <div class="d-flex card m-0  rounded-0 flex-column h-100">
                        <div class="bg-info text-white text-center py-1 rounded-0">
                            <i class=" ri-arrow-up-s-line me-1"></i>
                            <span>Pregledaj sve</span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center p-2">
                            <h6 class="text-muted text-uppercase fs-11 mb-1">Spašene deklaracije</h6>
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="ri-file-text-line  text-info mb-1" style="font-size: 45px"></i>
                                <h3 class="mb-0 ms-2"><span class="counter-value counter-value-invoice">0</span></h3>
                            </div>
                        </div>

                    </div>
                </a>
            </div>

            <!-- Middle Avatar Section -->
            <div class="col-md-4 border-start border-end order-1 order-md-0 d-flex align-items-center border-0 rounded-0 alert alert-light p-1 mb-2 m-lg-0 main-card-dashboard">
                <div class="p-2 text-center d-flex flex-column h-100 w-100 justify-content-center align-items-center">
                    <div class="card-body text-center p-2">
                        <div class="row d-flex text-center mb-3 fs-4" style="justify-content: center!important; height: 50px;">
                            <img class="user-avatar img-thumbnail rounded-circle d-none avatar-class">
                            <div class="avatar-fallback rounded-circle bg-info d-flex justify-content-center align-items-center text-white" style="width: 50px; height: 50px;"></div>
                        </div>
                        <h6 class="mb-1 mt-1 logo-span welcome">Dobrodošli na <strong class="logo-span logo-text">deklarant<span class="ai-span">.ai</span></strong> {{ Auth::user()->username ?? '' }}</h6>
                        <p class="fs-7 mb-1 text-info" id="user-package-display"></p>
    @include('components.package-dashboard-statusbar')
                        
                    </div>
                    <div class="card-footer bg-transparent border-0 w-100 p-0 p-lg-1">
                        <div class="d-flex justify-content-center gap-2 w-100">
                              <div class="w-50">
                              @include('components.upgrade-button')
                              </div>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#scanModal"
                                class="btn btn-info w-50 animated-btn btn-sm d-flex align-items-center justify-content-center">
                                <i class="fas fa-wand-magic-sparkles fs-6 me-1" style="font-size:10px;"></i>
                                <p class="fs-6 mb-0"> Skeniraj <span class="deklaraciju">deklaraciju</span> sa AI</p>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Columns remain unchanged -->

            <div class="col-md-2 col-6 border-end border-0 order-4 order-md-0 card-animate mt-lg-0 mt-md-0 mt-4">
                <a href="moji-klijenti">
                    <div class="d-flex card  rounded-0 m-0 flex-column h-100">
                        <div class="bg-info text-white text-center py-1 rounded-0">
                            <i class=" ri-arrow-up-s-line me-1"></i>
                            <span>Pregledaj sve</span>
                        </div>
                        <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center p-2">
                            <h6 class="text-muted text-uppercase fs-11 mb-1">Moji klijenti</h6>
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="ri-user-line text-info" style="font-size: 38px"></i>
                                <h3 class="mb-0 ms-2"><span class="counter-value" id="totalImporters">0</span></h3>
                            </div>
                        </div>
                    </div>
                </a>
            </div>


            <div class="col-md-2 col-6 border-0 order-5 order-md-0 card-animate mt-lg-0 mt-md-0 mt-4">
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
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">aktivan {{ Auth::user()->getActivePackageName()}} paket</p>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
         
                          <span id="remainingScans">{{ Auth::user()->getRemainingScans() ?? '0' }}</span>/<span id="totalScans">{{ Auth::user()->getActivePackageStats()->available_scans ?? '0' }}</span>

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
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Spašene deklaracije</p>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            <span class="counter-value" id="totalInvoices" data-target="45">0</span><span
                                class="counter-value">/{{ Auth::user()->getActivePackageStats()->document_history ?? '0' }}</span>
                               
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
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Carinskih tarifa</p>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            <span class="counter-value" data-target="14.1082">14.108</span>
                        </h4>
                    </div>
                    <div style="width: 80px; height: 80px;" class="d-flex align-items-center justify-content-center">
                        <i class="ri-barcode-box-line text-info" style="font-size: 45px;"></i>
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
                            {{ Auth::user()->getActivePackageStats()->speed_limit ?? 'Nije definisano' }}
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
                    <div class="col-md-6  mb-3 mb-md-0">
                        <div class="card rounded-0 h-100 ">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0 fs-6">Zahtjevi za inspekcijski nadzor</h5>
                                <a class="text-info fs-6">Pregledaj sve</a>
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

                    <div class="col-md-6  mb-3 mb-md-0">
                        <div class="card rounded-0 h-100">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0 fs-6">Carinjenje po nepotpunoj izjavi</h5>
                                <a class="text-info fs-6">Pregledaj sve</a>
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
                    <div class="col-md-6  mb-3 mb-md-0">
                        <div class="card rounded-0 h-100">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0 fs-6">Izjave za carinjenje pošiljki</h5>
                                <a class="text-info fs-6">Pregledaj sve</a>
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
                    <div class="col-md-6  mb-3 mb-md-0">
                        <div class="card rounded-0 h-100">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0 fs-6">Obrasci</h5>
                                <a class="text-info fs-6">Pregledaj sve</a>
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
            <div class="col-xl-6 d-flex flex-column">
                <div class="row g-1 flex-fill mx-0">
                    <!-- First column: Zadnje korištene tarife -->
                    <div class="col-md-6  mb-3 mb-md-0">
                        <div class="card rounded-0 w-100 h-100 card-animate mb-0">
                            <div class="card-header">
                                <h5 class="mb-0">Nedavne tarife</h5>
                            </div>
                                                      <div class="card-body d-flex justify-content-start align-items-center flex-column pb-0 pt-0 position-relative" style="min-height: 200px;">
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <div class="tariff-loader spinner-border text-info" role="status"></div>
                                </div>
                                <div class="tariff-list d-none w-100 mt-3"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Second column: Zadnje korišteni dobavljači -->
                                       <div class="col-md-6 d-flex ">
                        <div class="card rounded-0 w-100 h-100 mb-0">
                            <div class="card-header">
                                <h5 class="mb-0">Nedavni klijenti</h5>
                            </div>
                                                       <div class="card-body d-flex justify-content-start align-items-center flex-column pb-0 pt-0 position-relative" style="min-height: 200px;">
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <div class="suppliers-loader spinner-border text-info" role="status"></div>
                                </div>
                                <div class="suppliers-list d-none w-100 mt-3"></div>
                            </div>
                        </div>
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
document.addEventListener("DOMContentLoaded", async function () {
    if (!token || !user?.id) {
        console.warn("Missing auth or user.");
        return;
    }

    const supplierContainer = document.querySelector(".suppliers-list");
    const tariffContainer = document.querySelector(".tariff-list");
    const supplierLoader = document.querySelector(".suppliers-loader");
    const tariffLoader = document.querySelector(".tariff-loader");

    try {
        const res = await axios.get(`/api/statistics/users/${user.id}`, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });

        const stats = res.data || {};
        console.log("Stats response:", stats);

        // --- SUPPLIERS SECTION ---
        const suppliers = stats.importer_stats?.latest_importers || [];
        const lastSuppliers = suppliers.slice(-5);

        if (supplierLoader) supplierLoader.classList.add("d-none");
        if (supplierContainer) {
            supplierContainer.classList.remove("d-none");
            supplierContainer.innerHTML = "";

            if (lastSuppliers.length === 0) {
                supplierContainer.innerHTML = `
                    <div class="text-muted text-center position-absolute translate-middle top-50 start-50">
                        Nema podataka o dobavljačima
                    </div>
                `;
            } else {
                lastSuppliers.forEach((supplier) => {
                    const percentage = parseFloat(supplier.percentage_change);
                    const isPositive = percentage >= 0;
                    const growthClass = isPositive ? "text-success" : "text-danger";
                    const arrow = isPositive ? "ri-arrow-up-line" : "ri-arrow-down-line";

                    supplierContainer.innerHTML += `
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <div class="fw-semibold">
                                    ${supplier.name.length > 17 ? supplier.name.substring(0, 17) + "…" : supplier.name}
                                </div>
                                <div class="text-muted fs-12">${supplier.owner ?? "Nepoznat vlasnik"}</div>
                            </div>
                            <div class="text-info fs-12" style="white-space: nowrap;">
                                ${!supplier.address 
                                    ? "Nije definisano" 
                                    : (supplier.address.length > 17 
                                        ? supplier.address.substring(0, 17) + "…" 
                                        : supplier.address)}
                                <i class="ri-map-pin-line text-info ms-1"></i>
                            </div>
                        </div>
                    `;
                });

                supplierContainer.innerHTML += `
                    <div class="card-footer p-0 pb-0 pt-0 d-flex justify-content-end pregledaj-vise-bottom-right">
                        <a href="moji-klijenti" class="text-info fs-13 mb-2" style="margin:.5rem!important;">
                            Pregledaj sve
                        </a>
                    </div>
                `;
            }
        }

        // --- TARIFF SECTION ---
        const latestTariffs = stats.latest_tariffs || [];
        const topTariffs = latestTariffs.slice(0, 5);

        if (tariffLoader) tariffLoader.classList.add("d-none");
        if (tariffContainer) {
            tariffContainer.classList.remove("d-none");
            tariffContainer.innerHTML = "";

            if (topTariffs.length === 0) {
                tariffContainer.innerHTML = `
                    <div class="text-muted text-center position-absolute translate-middle top-50 start-50">
                        Nema nedavnih tarifa
                    </div>
                `;
            } else {
                topTariffs.forEach((item) => {
                    tariffContainer.innerHTML += `
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <div class="fw-semibold">${item.item_code}</div>
                                <div class="text-muted fs-12" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px;">
    ${item.name}
</div>

                            </div>
                            <div class="fs-12 text-info">${item.tariff_rate ?? '-'}%</div>
                        </div>
                    `;
                });

                tariffContainer.innerHTML += `
                    <div class="card-footer p-0 pb-0 pt-0 d-flex justify-content-end pregledaj-vise-bottom-right">
                        <a href="moje-deklaracije" class="text-info fs-13 mb-2" style="margin:.5rem!important">
                            Pregledaj sve
                        </a>
                    </div>
                `;
            }
        }

    } catch (err) {
        console.error("Greška pri dohvaćanju statistike:", err);

        if (supplierLoader) supplierLoader.classList.add("d-none");
        if (tariffLoader) tariffLoader.classList.add("d-none");

        if (supplierContainer) {
            supplierContainer.classList.remove("d-none");
            supplierContainer.innerHTML = `<div class="text-danger">Greška pri dohvaćanju dobavljača.</div>`;
        }

        if (tariffContainer) {
            tariffContainer.classList.remove("d-none");
            tariffContainer.innerHTML = `<div class="text-danger">Greška pri dohvaćanju tarifa.</div>`;
        }
    }
});
</script>








<!-- new doughnut logic -->

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const centerTextPlugin = {
            id: "centerText",
            beforeDraw: function (chart) {
                const width = chart.width;
                const height = chart.height;
                const ctx = chart.ctx;

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

        let chartInstance = null;

        function createDoughnutChart(canvasId, usedPercentage) {
            const ctx = document.getElementById(canvasId).getContext("2d");
            const remaining = 100 - usedPercentage;

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: "doughnut",
                data: {
                    labels: ["Iskorišteno %", "Preostalo %"],
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
                        legend: { display: false },
                        tooltip: { enabled: true }
                    }
                },
                plugins: [centerTextPlugin]
            });
        }

        // 👇 Fetch values directly from DOM
        const remaining = parseInt(document.getElementById("remainingScans")?.textContent || "0");
        const total = parseInt(document.getElementById("totalScans")?.textContent || "0");

        let usedPercentage = 0;
        if (total > 0) {
            usedPercentage = Math.min((total - remaining) / total * 100, 100);
        }

        createDoughnutChart("doughnut1", Math.round(usedPercentage));
    });
</script>





<!-- doughnut2 -->


<script>
    document.addEventListener("DOMContentLoaded", async function() {
         ;
          

        if (!user || !token) {
            console.warn("User or token missing in localStorage.");
            return;
        }

        let chartInstance = null;

        function createDoughnutChart(canvasId, usedPercentage) {
            const ctx = document.getElementById(canvasId).getContext("2d");
            const remaining = 100 - usedPercentage;

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: "doughnut",
                data: {
                    labels: ["Iskorišteno %", "Preostalo %"],
                    datasets: [{
                        data: [usedPercentage, remaining],
                        backgroundColor: ["#299cdb", "#d6f0fa"],
                    }],
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
                            enabled: true
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
                }],
            });
        }


        createDoughnutChart("doughnut2", 0);

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

            const invoiceLimit = {{ Auth::user()->getActivePackageStats()->document_history ?? '0' }};

            const invoiceRes = await axios.get(invoicesUrl, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            });

            const invoices = invoiceRes.data || [];
            const invoiceCount = invoices.length;

            const invoiceEl = document.getElementById("totalInvoices");
            if (invoiceEl) invoiceEl.innerText = invoiceCount;

            const denominatorEl = invoiceEl?.nextElementSibling;
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
    });
</script>



<!-- brzina skeniranja -->

<script>
    document.addEventListener("DOMContentLoaded", async function() {
          
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
    let speed = "Nije definisano";
    const invoiceLimit = @json(Auth::user()?->getActivePackageStats()?->speed_limit ?? 0);
    scanSpeedEl.innerText = speed;
}


        } catch (err) {
            console.error("Greška prilikom dohvaćanja paketa:", err);
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
                    name: "Prima Co.",
                    owner: "Asim Delić",
                    address: "Sarajevo"
                },
                {
                    name: "Mostar Export Inc.",
                    owner: "Alma Mujić",
                    address: "Mostar"
                },
                {
                    name: "Trade Centar d.o.o.",
                    owner: "Nasim Ljubić",
                    address: "Tuzla"
                },
                {
                    name: "Quick Rent d.o.o.",
                    owner: "Azra Kovačević",
                    address: "Zenica"
                },
                {
                    name: "Demo Group",
                    owner: "Malik Hadžić",
                    address: "Bihać"
                },
                {
                    name: "ACME Corp",
                    owner: "Amir Begić",
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
                slidesPerView: 2,
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



<!-- Upload data 
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
</script> -->


<!-- user package -->









@endsection

@section('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (user) {
            // Populate middle avatar
            populateUserAvatar("user-avatar-middle", "avatar-middle-fallback", user);
        }
    });
</script>
@endsection