<!-- ========== App Menu ========== -->
<div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title w-100" id="scanModalLabel"><i class="fas fa-wand-magic-sparkles fs-6 me-1" style="font-size:10px;"></i>Skeniraj deklaraciju sa AI</h5>
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
                        <p class="mt-3">Prevucite dokument ovdje ili kliknite kako bi uploadali i skenirali vašu deklaraciju</p>
                    </div>

                    <div class="file-list" id="fileList" style="display: none;"></div>

                    <div class="progress mt-3 w-100" id="uploadProgressContainer" style="display: none;">
                        <div id="uploadProgressBar" class="progress-bar bg-info" role="progressbar" style="width: 0%">0%</div>
                    </div>

                    <div id="scanningLoader" class="mt-4 text-center d-none">
                        <div class="spinner-border text-info" role="status" style="width: 3rem; height: 3rem;"></div>
                        <p class="mt-3 fw-semibold" id="scanningText">Skeniranje deklracije...</p>
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
<div class="app-menu navbar-menu">
    <!-- LOGO -->


    <div id="scrollbar" style="height: auto!important;">
        <div class="container-fluid d-flex justify-content-center  ">


{{-- USER NAVIGATION --}}
@auth
<ul class="navbar-nav admin-nav" id="navbar-nav-user">
    <li class="nav-item me-1">
        <a class="nav-link menu-link ps-0 d-flex align-items-center justify-content-between" href="#" id="homeLink">
            <span>
                <i class="ri-home-line text-info"></i>
                <span>@lang('translation.home')</span>
            </span>
            <i class="ri-arrow-down-s-line text-muted collapse-toggler" data-bs-toggle="collapse" data-bs-target="#sidebarDashboards" role="button" aria-expanded="false" aria-controls="sidebarDashboards" style="cursor: pointer; min-width:unset!important"></i>
        </a>
        <div class="collapse menu-dropdown" id="sidebarDashboards">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="/" class="nav-link">@lang('translation.information_dashboard')</a>
                </li>
                <li class="nav-item">
                    <a href="analitika" class="nav-link">@lang('translation.analytics')</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item me-1">
        <a class="nav-link menu-link" href="kalendar">
            <i class="ri-calendar-line text-info"></i> <span>@lang('translation.statistic')</span>
        </a>
    </li>
    <li class="nav-item me-1">
        <a class="nav-link menu-link" href="moje-deklaracije">
            <i class="ri-file-line text-info"></i> <span>@lang('translation.myorder')</span>
        </a>
    </li>
    <li class="nav-item me-1">
        <a class="nav-link menu-link" href="moji-klijenti">
            <i class="ri-user-line text-info fs-4"></i> <span>@lang('translation.clients')</span>
        </a>
    </li>
    <li class="nav-item me-1">
        <a class="nav-link menu-link" href="moji-dobavljaci">
            <i class="ri-truck-line text-info fs-4"></i> <span>@lang('translation.importers')</span>
        </a>
    </li>
    <li class="nav-item me-1">
        <a class="nav-link menu-link" href="tarifne-oznake">
            <i class="mdi mdi-sticker-text-outline text-info"></i> <span>@lang('translation.declarant')</span>
        </a>
    </li>
    <li class="nav-item me-1">
        <a class="nav-link menu-link" href="cijene-paketa">
            <i class="ri-price-tag-3-line text-info"></i> <span>@lang('translation.pricing')</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#scanModal" class="btn btn-info btn-sm ms-5 text-white py-0 d-flex align-items-center justify-content-center" style="width: 190px; height:28px;">
            <i class="fas fa-wand-magic-sparkles fs-6 me-1"></i>
            <span class="fs-6"> Skeniraj deklaraciju sa AI</span>
        </a>
    </li>
</ul>
@endauth

{{-- SUPERADMIN NAVIGATION --}}
@if(Auth::user()->role == 'superadmin')
<ul class="navbar-nav user-nav" id="navbar-nav-admin" style="justify-content: unset!important; gap: 10px!important;">
    <li class="nav-item me-1">
        <a class="nav-link menu-link" href="moji-korisnici">
            <i class="ri-user-line text-info fs-4"></i> <span>Korisnici aplikacije</span>
        </a>
    </li>
    <li class="nav-item me-1">
        <a class="nav-link menu-link" href="moji-klijenti">
            <i class="ri-user-line text-info fs-4"></i> <span>Distribrateri</span>
        </a>
    </li>
    <li class="nav-item me-1">
        <a class="nav-link menu-link" href="moji-dobavljaci">
            <i class="ri-truck-line text-info fs-4"></i> <span>@lang('translation.importers')</span>
        </a>
    </li>
</ul>
@endif


         

        





        </div>

        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>

@section('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const homeLink = document.getElementById("homeLink");

        if (homeLink) {
            homeLink.addEventListener("click", function(e) {
                // If the submenu is already shown, trigger navigation
                const targetCollapse = document.querySelector(this.getAttribute("href"));
                const isExpanded = this.getAttribute("aria-expanded") === "true";

                // Let the collapse toggle as usual
                setTimeout(() => {
                    // If it was already expanded, or just became expanded — then redirect
                    if (isExpanded || targetCollapse.classList.contains('show')) {
                        window.location.href = this.getAttribute("data-href");
                    }
                }, 300); // wait for collapse to finish animation
            });
        }
    });
</script>
@endsection