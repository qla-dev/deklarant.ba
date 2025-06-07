
<div class="mobile-footer-scan d-sm-none">
    <button type="button"
        class="btn btn-info btn-sm text-white w-100 d-flex align-items-center justify-content-center"
        data-bs-toggle="modal" data-bs-target="#scanModal">
        <i class="fas fa-wand-magic-sparkles fs-6 me-1"></i>
        <span class="fs-6">Skeniraj deklaraciju sa AI</span>
    </button>
</div>

<footer class="footer main-footer">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-5 col-sm-6" style="margin-top:-3px">
                <script>document.write(new Date().getFullYear())</script> © <strong class="logo-span logo-text">deklarant<span class="ai-span">.ai</span></strong>
            </div>
            <div class="col-7 col-sm-6">
                <div class="text-end d-flex d-md-block fs-6 justify-content-center logo-span">
                Razvijeno od strane  

                  <a href="/" class="logo-dark">
                        <span class="d-inline d-md-none">
                            <img src="{{ URL::asset('build/images/logo-qla.png') }}" alt="" style="width:55px; margin-left: 3px;">
                        </span>
                         <span class="d-none d-md-inline"> <img src="{{ URL::asset('build/images/logo-qla.png') }}" alt="" style="width:60px; margin-top: -2px; margin-left: 2px;"></span>
                 
                    </a>
                   

                    <a href="/" class="logo-light">
                        <span >
                            <img src="{{ URL::asset('build/images/logo-qla-dark.png') }}" alt=""  style="width:60px">
                        </span>
                       
                        
                    </a>


                </div>
            </div>
        </div> 
    </div>
</footer>
