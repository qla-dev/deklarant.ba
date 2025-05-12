<!-- ========== App Menu ========== -->
<div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title w-100" id="scanModalLabel"><i class="fas fa-wand-magic-sparkles fs-6 me-1" style="font-size:10px;"></i>Skeniraj fakturu sa AI</h5>
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
                        <p class="mt-3">Prevucite dokument ovdje ili kliknite kako bi uploadali i skenirali vašu fakturu</p>
                    </div>

                    <div class="file-list" id="fileList" style="display: none;"></div>

                    <div class="progress mt-3 w-100" id="uploadProgressContainer" style="display: none;">
                        <div id="uploadProgressBar" class="progress-bar bg-info" role="progressbar" style="width: 0%">0%</div>
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
<div class="app-menu navbar-menu">
    <!-- LOGO -->


    <div id="scrollbar" style="height: auto!important;">
        <div class="container-fluid d-flex justify-content-center  ">


            <ul class="navbar-nav  " id="navbar-nav">

                <li class="nav-item me-3">
                    <a class="nav-link menu-link ps-0 d-flex align-items-center justify-content-between" href="{{ route('root') }}" id="homeLink">
                        <span>
                            <i class="ri-home-line text-info"></i>
                            <span>@lang('translation.home')</span>
                        </span>
                        <i class="ri-arrow-down-s-line text-muted collapse-toggler" data-bs-toggle="collapse" data-bs-target="#sidebarDashboards" role="button" aria-expanded="false" aria-controls="sidebarDashboards" style="cursor: pointer;"></i>
                    </a>

                    <div class="collapse menu-dropdown" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="analytics" class="nav-link">@lang('translation.analytics')</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item me-3">
                    <a class="nav-link menu-link" href="kalendar"  role="button"
                        aria-expanded="false" aria-controls="sidebarApps">
                        <i class="ri-calendar-line text-info"></i> <span>@lang('translation.statistic')
                        </span>
                    </a>
                   
                </li>


                <li class="nav-item me-3">
                    <a class="nav-link menu-link" href="moje-fakture"  role="button"
                        aria-expanded="false" aria-controls="sidebarLayouts">
                        <i class="ri-file-line text-info "></i> <span>@lang('translation.myorder')</span> 

                    </a>
                    
                </li>


                <!-- end Dashboard Menu -->

                <li class="nav-item me-3">
                    <a class="nav-link menu-link" href="moji-dobavljaci"  role="button"
                        aria-expanded="false" aria-controls="sidebarAuth">
                        <i class="ri-truck-line text-info fs-4"></i> <span>@lang('translation.clients')
                        </span>
                    </a>
                   
                </li>

                <li class="nav-item me-3">
                    <a class="nav-link menu-link" href="tarifne-oznake"  role="button"
                        aria-expanded="false" aria-controls="sidebarPages">
                        <i class="mdi mdi-sticker-text-outline text-info"></i> <span>@lang('translation.declarant')
                        </span>
                    </a>
                   
                </li>

                

                <li class="nav-item me-3">
                    <a class="nav-link menu-link" href="kursna-lista"  role="button"
                        aria-expanded="false" aria-controls="sidebarUI">
                        <i class="ri-exchange-dollar-line text-info"></i> <span>@lang('translation.exclist')
                        </span>
                    </a>
                    <div class="collapse menu-dropdown mega-dropdown-menu" id="sidebarUI">
                        <div class="row">
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="ui-alerts" class="nav-link">@lang('translation.alerts')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-badges" class="nav-link">@lang('translation.badges')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-buttons" class="nav-link">@lang('translation.buttons')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-colors" class="nav-link">@lang('translation.colors')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-cards" class="nav-link">@lang('translation.cards')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-carousel" class="nav-link">@lang('translation.carousel')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-dropdowns" class="nav-link">@lang('translation.dropdowns')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-grid" class="nav-link">@lang('translation.grid')
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="ui-images" class="nav-link">@lang('translation.images')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-tabs" class="nav-link">@lang('translation.tabs')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-accordions" class="nav-link">@lang('translation.accordion-collapse')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-modals" class="nav-link">@lang('translation.modals')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-offcanvas" class="nav-link">@lang('translation.offcanvas')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-placeholders" class="nav-link">@lang('translation.placeholders')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-progress" class="nav-link">@lang('translation.progress')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-notifications" class="nav-link">@lang('translation.notifications')
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-4">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="ui-media" class="nav-link">@lang('translation.media-object')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-embed-video" class="nav-link">@lang('translation.embed-video')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-typography" class="nav-link">@lang('translation.typography')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-lists" class="nav-link">@lang('translation.lists')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-links" class="nav-link"><span>@lang('translation.links')</span> <span
                                                class="badge badge-pill bg-success">@lang('translation.new')</span></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-general" class="nav-link">@lang('translation.general')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-ribbons" class="nav-link">@lang('translation.ribbons')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="ui-utilities" class="nav-link">@lang('translation.utilities')
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </li>






                <li class="nav-item">
                    <a href="javascript:void(0);"
                        data-bs-toggle="modal"
                        data-bs-target="#scanModal"
                        style="width: 190px; height:28px;"
                        class="btn btn-info btn-sm ms-5 text-white py-0 d-flex align-items-center justify-content-center">

                        <i class="fas fa-wand-magic-sparkles fs-6 me-1"></i>
                        <span class="fs-6"> Skeniraj fakturu sa AI</span>
                    </a>


                </li>






            </ul>







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