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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    

    <style>
        .mySwiper .swiper-slide {
             margin-right: 3px; /* This is equivalent to Bootstrap's g-1 gap */
        }

    /* Optional: Remove the margin-right for the last slide to avoid overflow */
        .mySwiper .swiper-slide:last-child {
             margin-right: 0;
        }

        .my-alert {
            
            transition: background-color 0.6s ease-in-out, color 0.6s ease-in-out;
        }
        .my-card:hover .my-alert {
            background-color: #299cdb !important; /* bg-info */
            
            color: white !important;
        }
        .dropzone {
        width: 450px;
        height: 450px;
        border: dashed rgb(59, 171, 171);
        /* Fixed typo */

        background-color: #f8f9fa;
        text-align: center;
        padding: 50px;
        cursor: pointer;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: background-color 0.3s ease-in-out;
    }
    
    @keyframes bounce-in {
    0% { transform: scale(0); opacity: 0; }
    60% { transform: scale(1.2); opacity: 1; }
    80% { transform: scale(0.95); }
    100% { transform: scale(1); }
    }


    .dropzone:hover {
        background-color: #e3f2fd;
    }

    .dropzone input {
        display: none;
    }

    .corner {
        position: absolute;
        width: 50px;
        height: 50px;
        border: 7px solid #299cdb;
    }

    .corner-top-left {
        top: -4px;
        left: -4px;
        border-right: none;
        border-bottom: none;
    }

    .corner-top-right {
        top: -4px;
        right: -4px;
        border-left: none;
        border-bottom: none;
    }

    .corner-bottom-left {
        bottom: -4px;
        left: -4px;
        border-right: none;
        border-top: none;
    }

    .corner-bottom-right {
        bottom: -4px;
        right: -4px;
        border-left: none;
        border-top: none;
    }

    .file-list {
        margin-top: 15px;
        width: 100%;
        max-height: 150px;
        overflow-y: auto;
        text-align: left;
        padding: 10px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.1);
    }

    .file-item {
        font-size: 14px;
        padding: 5px;
        border-bottom: 1px solid #e3e3e3;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .file-item:last-child {
        border-bottom: none;
    }

    .remove-file {
        cursor: pointer;
        color: red;
        font-size: 16px;
        font-weight: bold;
    }

    .scan-icon {
        font-size: 150px;
        color: #299cdb;
    }

    .checkmark-animation {
        font-size: 3rem;
        color: #28a745;
        animation: pop-in 0.6s ease-out forwards;
        opacity: 0;
    }

    @keyframes pop-in {
        0% {
            transform: scale(0.3);
            opacity: 0;
        }
        80% {
            transform: scale(1.2);
            opacity: 1;
        }
        100% {
            transform: scale(1);
        }
    }

       
     
        /* Ensure the swiper-container behaves as expected */
        

        
        
        


        


    </style>
@endsection

@section('content')


<!-- Top part -->
 
<div class="col-xl-12">
    <div class="card border-0 rounded-0 shadow-0 h-100 mt-1">
        <div class="row g-0">
            <!-- Left Columns -->
            <div class="col-md-2 border-end border-0 card-animate">
                <div class="d-flex flex-column h-100">
                    <div class="bg-info text-white text-center py-1 rounded-0">
                        <i class="ri-alert-line me-1"></i>
                        <span>Testna poruka <b>123</b> test.</span>
                    </div>
                    <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center p-2">
                        <h6 class="text-muted text-uppercase fs-11 mb-1">Broj skeniranih faktura</h6>
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="ri-file-text-line  text-info mb-1" style="font-size: 45px"></i>
                            <h3 class="mb-0 ms-2"><span id="usedScans" class="counter-value">0</span></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2 border-end border-0 card-animate">
                <div class="d-flex flex-column h-100">
                    <div class="bg-info text-white text-center py-1 rounded-0">
                        <i class="ri-alert-line me-1"></i>
                        <span>Testna poruka <b>123</b> test.</span>
                    </div>
                    <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center p-2">
                        <h6 class="text-muted text-uppercase fs-11 mb-1">Dostupna skeniranja</h6>
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="ri-scan-2-line text-info mb-1" style="font-size: 45px"></i>
                            <h3 class="mb-0 ms-2"><span class="counter-value" id="remainScans">0</span></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Middle Avatar Section -->
            <div class="col-md-4 border-end d-flex align-items-center border-0 rounded-0 alert alert-light p-1 m-0 card-animate">
                <div class="p-2 text-center d-flex flex-column h-100 w-100 justify-content-center align-items-center">
                    <div class="card-body text-center">
                        <img id="user-avatar" src="{{ URL::asset('build/images/users/avatar-1.jpg') }}" class="rounded-circle shadow-sm mb-1" width="60" height="60" alt="Korisnički avatar">
                        <h6 class="fw-bold text-dark mb-1" id="welcome-user">Dobrodošli na eDeklarant!</h6>
                        <p class="fw-semibold fs-7 mb-1 text-dark">Vaš trenutni paket je <b>Starter</b></p>
                    </div>
                    <div class="card-footer bg-transparent border-0 w-100">
                        <div class="d-flex justify-content-center gap-2 w-100">
                            <a href="pages-pricing" class="btn btn-info text-white w-50 btn-sm d-flex align-items-center justify-content-center">
                                <i class="fas fa-circle-chevron-up fs-6 me-1"></i> <span class="fs-6">Nadogradi paket</span>
                            </a>
                            <a href="pages-scan" class="btn btn-info w-50 animated-btn btn-sm d-flex align-items-center justify-content-center">
                                <i class="fas fa-wand-magic-sparkles fs-6 me-1" style="font-size:10px;"></i><span class="fs-6"> Skeniraj fakturu</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Columns remain unchanged -->
            <div class="col-md-2 border-end card-animate">
                <div class="d-flex flex-column h-100">
                    <div class="bg-info text-white text-center py-1 rounded-0">
                        <i class="ri-alert-line me-1"></i>
                        <span>Testna poruka <b>123</b> test.</span>
                    </div>
                    <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center p-2">
                        <h6 class="text-muted text-uppercase fs-11 mb-1">Moji dobavljači</h6>
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="ri-truck-line text-info" style="font-size: 45px"></i>
                            <h3 class="mb-0 ms-2"><span class="counter-value" id="totalSuppliers">0</span></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2 card-animate">
                <div class="d-flex flex-column h-100">
                    <div class="bg-info text-white text-center py-1 rounded-0">
                        <i class="ri-alert-line me-1"></i>
                        <span>Testna poruka <b>123</b> test.</span>
                    </div>
                    <div class="d-flex flex-column flex-grow-1 justify-content-center align-items-center p-2">
                        <h6 class="text-muted text-uppercase fs-11 mb-1">Broj carinskih tarifa</h6>
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="ri-barcode-box-line text-info" style="font-size: 45px"></i>
                            <h3 class="mb-0 ms-2"><span class="counter-value" data-target="128">0</span></h3>
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
        <div class="card rounded-0 card-animate overflow-hidden">
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
        <div class="card rounded-0 card-animate overflow-hidden">
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
        <div class="card rounded-0 card-animate overflow-hidden">
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
                        <i class="ri-truck-line text-info" style="font-size: 45px;"></i>
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
                <h6 class="card-title mb-0">Moji dokumenti</h6>
                <a class="text-muted fs-6">Pogledaj sve</a>
               </div>
               <div class="card-body d-flex align-items-center justify-content-center">
                 
                       <div class="row d-flex text-center text-truncate" >  
                         <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i><div>Dokument 1 </div></div>
                         <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i><div>Dokument 2 </div></div>
                         <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i><div>Dokument 3</div></div>
                         <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i><div>Dokument 4</div></div>
                       </div>
                 
               </div>
             </div>
          </div>

          <div class="col-md-6">
             <div class="card rounded-0 h-100">
               <div class="card-header d-flex justify-content-between">
                <h6 class="card-title mb-0">Moji dokumenti</h6>
                <a class="text-muted fs-6">Pogledaj sve</a>
               </div>
               <div class="card-body d-flex justify-content-center align-items-center">
                 
                      <div class="row d-flex text-center text-truncate" >  
                         <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i><div>Dokument 1 </div></div>
                         <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i><div>Dokument 2 </div></div>
                         <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i><div>Dokument 3</div></div>
                         <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i><div>Dokument 4</div></div>
                       </div>
                 
               </div>
             </div>
          </div>
          <div class="col-md-6">
             <div class="card rounded-0 h-100">
               <div class="card-header d-flex justify-content-between">
                <h6 class="card-title mb-0">Moji dokumenti</h6>
                <a class="text-muted fs-6">Pogledaj sve</a>
               </div>
               <div class="card-body d-flex justify-content-center align-items-center">
                 
                <div class="row d-flex text-center text-truncate" >  
                         <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i><div>Dokument 1 </div></div>
                         <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i><div>Dokument 2 </div></div>
                         <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i><div>Dokument 3</div></div>
                         <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i><div>Dokument 4</div></div>
                       </div>
                 
               </div>
             </div>
          </div>
          <div class="col-md-6">
             <div class="card rounded-0 h-100">
               <div class="card-header d-flex justify-content-between">
                <h6 class="card-title mb-0">Moji dokumenti</h6>
                <a class="text-muted fs-6">Pogledaj sve</a>
               </div>
               <div class="card-body d-flex justify-content-center align-items-center">
                 
                <div class="row d-flex text-center text-truncate" >  
                         <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i><div>Dokument 1 </div></div>
                         <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i><div>Dokument 2 </div></div>
                         <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i><div>Dokument 3</div></div>
                         <div class="col-3 card-animate"><i class="ri-file-line fs-2 text-info"></i><div>Dokument 4</div></div>
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
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <div class="fw-semibold">8471.30</div>
                        <div class="text-muted fs-12">Laptop</div>
                  </div>
                    <div class="text-success fs-13">
                        18% <i class="ri-arrow-up-line ms-1"></i>
                    </div>
                </div>
              
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <div class="fw-semibold">8471.30</div>
                        <div class="text-muted fs-12">Laptop</div>
                  </div>
                    <div class="text-success fs-13">
                        18% <i class="ri-arrow-up-line ms-1"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <div class="fw-semibold">8471.30</div>
                        <div class="text-muted fs-12">Laptop</div>
                  </div>
                    <div class="text-success fs-13">
                        18% <i class="ri-arrow-up-line ms-1"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <div class="fw-semibold">8471.30</div>
                        <div class="text-muted fs-12">Laptop</div>                       
                  </div>
                    <div class="text-success fs-13">
                        18% <i class="ri-arrow-up-line ms-1"></i>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <div class="fw-semibold">8471.30</div>
                        <div class="text-muted fs-12">Laptop</div>                      
                  </div>
                    <div class="text-success fs-13">
                        18% <i class="ri-arrow-up-line ms-1"></i>
                    </div> 
                </div>
                <div class="card-footer mt-0 pt-0 pb-0 text-truncate">         
                    
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
      <div class="modal-header">
        <h5 class="modal-title" id="scanModalLabel">Skeniraj fakturu</h5>
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


  

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Swiper CSS -->


    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

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
        document.addEventListener("DOMContentLoaded", function () {

            // Custom plugin to render text inside the chart
            const centerTextPlugin = {
                id: "centerText",
                beforeDraw: function (chart) {
                    const width = chart.width,
                          height = chart.height,
                          ctx = chart.ctx;

                    ctx.restore();
                    const fontSize = ((height / 8) * 2).toFixed(2);
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

                    ctx.fillStyle = "#299cdb"; // Info color
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
                    <div class="card rounded-0 card-animate overflow-hidden">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">

                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-3">${supplier.name}
                                    <div class="flex-shrink-0 d-flex align-items-center ml-2">
                                         <img id="user-avatar" src="{{ URL::asset('build/images/users/orbico.png') }}" class="rounded-circle shadow-sm mb-1" width="40" height="40" alt="Korisnički avatar">
                                     </div></p>

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

<!-- Upload data -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const token = localStorage.getItem("auth_token");
    if (!token) {
        alert("Niste prijavljeni. Molimo ulogujte se.");
        window.location.href = "/auth-login-basic";
        return;
    }

    const dropzone = document.getElementById("dropzone");
    const fileInput = document.getElementById("fileInput");
    const fileList = document.getElementById("fileList");
    const dropzoneContent = document.getElementById("dropzone-content");
    const progressContainer = document.getElementById("uploadProgressContainer");
    const progressBar = document.getElementById("uploadProgressBar");

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

            removeBtn.addEventListener("click", function () {
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

        fetch('http://localhost:8080/api/upload', {
            method: 'POST',
            body: formData 
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Upload failed");
            }
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
                // Save returned task_id to localStorage (important for next steps!)
                if (data.task_id) {
                    localStorage.setItem("scan_task_id", data.task_id);
                }
                window.location.href = "/apps-invoices-create"; 
            });
        })
        .catch(error => {
            console.error('Upload error:', error);
            alert('Greška prilikom uploada.');
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

    dropzone.addEventListener("click", () => fileInput.click());

    fileInput.addEventListener("change", () => {
        updateFileList(fileInput.files);
        uploadFiles(fileInput.files);
    });
});
</script>









@endsection
