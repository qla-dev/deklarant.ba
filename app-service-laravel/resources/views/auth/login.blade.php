@extends('layouts.login-master')
@section('css')
<link href="https://db.onlinewebfonts.com/c/b05a654a86637945b4997a378a5181fe?family=Facebook+Sans+Bold" rel="stylesheet">
<link href="{{ URL::asset('build/css/landing.min.css') }}"  rel="stylesheet" type="text/css" />
<script src="https://cdn.lordicon.com/lordicon.js"></script>

    <style>
        #loginForm {
  animation: fadeIn 0.5s ease;
}
    
    #registerForm, #appImage, #mobileFirstContent {
  animation: fadeIn 0.5s ease;

}

#mobileFirstContent  {
  margin-top: 3rem;
}

  body {
      background-color: #f5f5f5;
    }

    .form-section {
      width: 100%;
      max-width: 420px;
      padding: 2rem 1rem;
    }
    .form-control::placeholder {
      color: #b0b0b0;
    }
    .password-wrapper {
      position: relative;
    }
    .toggle-password {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      z-index: 10;
      color: #888;
    }
    .divider {
      display: flex;
      align-items: center;
      text-align: center;
      margin: 1.5rem 0;
    }
    .divider::before,
    .divider::after {
      content: '';
      flex: 1;
      border-bottom: 1px solid #ccc;
    }
    .divider::before {
      margin-right: .75em;
    }
    .divider::after {
      margin-left: .75em;
    }    
    
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
    
    .ai-span {
        
        color: #289cdb!important;
        padding: unset!Important;
        background: unset!Important;
            font-size: inherit!important;
    font-style: normal!important;
    font-weight: bolder!important;
        margin-bottom: unset!important;
        padding-bottom: !important;
         font-family: 'Facebook Sans Bold', sans-serif!important;
    }
    
    .logo-span {
        font-family: 'Facebook Sans Bold', sans-serif!important;
         font-weight: bolder!important;
    }
    
    .form-label {
        
        font-family: 'Facebook Sans', sans-serif; font-size: 0.8rem;
        margin-bottom: 0;
    }
    
    .back-link { color: #289cdb!important; }
    .btn-soft-info {
            background: #dff0fa;
    color: #299cdb;
         font-weight: 700!important;
    }
    
    .btn-soft-info:focus, .btn-soft-info:hover {
            background: #299cdb;
    color: white;
       
        
    }
    .header-elements {
        padding-top: 10px!important;
        padding-bottom: 10px!important;
    }
    
    .form-control {
        background: #f3f3f9!important;
    }
    .form-control:active,  .form-control:focus {
        box-shadow: unset!important;
        border-color: #299cdb!important;
    }
    
    .kartica {
        border: unset!important;
            margin-top:1rem;
         box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); /* lagana siva sjena */
        padding-bottom: 1rem!important;
    }
    
    .welcome2-section-area {padding: 0!important}
    
    .loader {
    width: 48px;
    height: 48px;
    border: 5px solid #299cdb;
    border-bottom-color: transparent;
    border-radius: 50%;
    display: inline-block;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
    position: absolute;
    top: 50%;
    left: 50%;
    margin-left: -25px;
    margin-top: -25px;
    }

    @keyframes rotation {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
    } 
    
    
    
    @media (max-width: 991.98px) {
  .content-height {
    height: unset;
    padding: 1rem;
  }
        
        .naslov {
            
            font-size: 2rem!important;
        }

        #loginForm, #registerForm {
height: 100vh;
overflow-y: hidden;  

        }
        .kartica{
          margin-top: 2rem!important;
        }

        .bottom-buttons {
          margin-bottom: 36px!important;
        }

        .header-images-area {

          margin-top: 0px!important;
        }

        .mobile-haeder2 {position: fixed!important;}
 
    
}

    
    
    
    @media (min-width: 992px) {
  .content-height {
    height: 90vh; /* ili neka specifična visina */
    padding: 3rem;
  }


}
    
    .mobile-sidebar2 {
        background: #262a2f!important;
        color: white!important;
        border: 2px solid #262a2f!important;
    
    }

    .header-btn2:disabled {
        opacity: 0.8;
        cursor: not-allowed;
        background-color: #4db8e3 !important; /* Brighter version of the info color */
    }

    </style>
  
@endsection
@section('content')

  <!--===== PRELOADER STARTS =======-->
  <div id="preloader">
    <div class="loader"></div>
  </div>
 <!--===== PRELOADER ENDS =======-->
 


  <!--=====HEADER START=======-->
  <header>
    <div class="header-area homepage2 header header-sticky d-none d-lg-block " id="header" style="position: relative!important">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="header-elements">
              <div class="site-logo">
                <a href="index.html"><img src="{{ URL::asset('build/images/homepage/logo/logo-dek.png') }}" alt="" width="200"></a>
              </div>
              <div class="main-menu">
                <ul class="mb-0">
                     
      <li><a href="cjenovnik.html">Cjenovnik</a></li>
      <li><a href="kontakt.html">Kontakt</a></li>
                </ul>
                <a href="#" class="header-btn2 rounded-1 loginFormShow">Prijava</a>
                <a href="#" class="header-btn2 rounded-1 ms-0 openRegister">Isprobaj besplatno</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!--=====HEADER END =======-->

  <!--===== MOBILE HEADER STARTS =======-->
 <div class="mobile-header mobile-haeder2 d-block d-lg-none">
  <div class="container-fluid">
    <div class="col-12">
      <div class="mobile-header-elements">
        <div class="mobile-logo">
          <a href="index.html"><img src="{{ URL::asset('build/images/homepage/logo/logo-dek.png') }}" alt="" height="17px"></a>
        </div>
          <div class="main-menu d-flex">
                <a href="#" class="header-btn2 rounded-1 loginFormShow me-3">Prijava</a>
                 <div class="mobile-nav-icon dots-menu">
          <i class="fa-solid fa-bars"></i>
        </div>
              </div>
       
      </div>
    </div>
  </div>
</div>

<div class="mobile-sidebar mobile-sidebar2">
  <div class="logosicon-area">
    <div class="logos">
      <img src="{{ URL::asset('build/images/homepage/logo/logo1.png') }}" alt="" style="width: 50%">
    </div>
    <div class="menu-close">
      <i class="fa-solid fa-xmark"></i>
    </div>
   </div>
  <div class="mobile-nav mobile-nav2">
    <ul class="mobile-nav-list nav-list2 ps-0">
      
      <li><a href="cjenovnik.html">Cjenovnik</a></li>
      <li><a href="kontakt.html">Kontakt</a></li>
      </ul>
  </div>
    <footer class="footer" style="position: fixed; bottom: 0; left: 0; width: 100%;background: #262a2f!important;">
    <div class="container-fluid">
        <div class="row ">
          
                <div class="text-center d-flex w-100 fs-6 justify-content-center align-items-center mb-3">
                <span class="me-2">Razvijeno od strane  </span>

                  <a hrf="http://qla.dev/"><img src="{{ URL::asset('build/images/homepage/logo/logo-qla-dark.png') }}" alt="" style="width: 100px"></a>


                </div>
            
        </div> 
    </div>
</footer>
</div>
  <!--===== MOBILE HEADER STARTS =======-->

<!--===== WELCOME STARTS =======-->
<div class="welcome2-section-area content-height" style="background-image: url({{ URL::asset('build/images/homepage/background/header-bg.png') }}); background-position: center; background-repeat: no-repeat; background-size: cover; display: flex;align-items: center;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 ps-lg-0 ps-lg-0 d-none d-lg-block">
                <div class="welcome2-header heading3">
                    <span data-aos="fade-up" data-aos-duration="600">Oslobodite potencijal AI tehnologije</span>
                    <h2 data-aos="fade-up" data-aos-duration="800" style="font-family: 'Facebook Sans', sans-serif; font-size: 3.5rem; color:black" class="naslov">
  Obrađuj <b style="color: #289cdb;  font-family: 'Facebook Sans Bold', sans-serif;">deklaracije</b> do <b style="color: #289cdb;  font-family: 'Facebook Sans Bold', sans-serif;">11X brže</b> i uštedi
</h2>
                     

                    <p data-aos="fade-up" data-aos-duration="1000">Automatsko skeniranje carinskih deklaracija uz pomoć AI – bez čekanja. <br>Uz <strong class="logo-span">deklarant<span class="ai-span" style="color:#289cdb!important">.ai</span></strong>, svaki unos postaje jednostavan, precizan i odmah dostupan u nekoliko sekundi – oslobodite se ručne obrade i fokusirajte se na ono što je važno.</p>
                    <div data-aos="fade-up" data-aos-duration="1200">
                      <a href="#" class="header-btn2 rounded-1 openRegister">Započni </a>
                    <a href="#functions" class="header-btn2 ms-2 rounded-1">Provjeri funkcionalnosti</a>
                    </div>
                </div>
            </div>
             <div class="col-lg-6 ps-lg-0 d-block d-lg-none" id="mobileFirstContent">
                <div class="welcome2-header heading3">
                    <span data-aos="fade-up" data-aos-duration="600" style="margin-top: 3.7rem;
    margin-bottom: 2rem;">Oslobodite potencijal AI tehnologije</span>
                    <h2 data-aos="fade-up" data-aos-duration="800" style="font-family: 'Facebook Sans', sans-serif; font-size: 3.5rem" class="naslov">
  Obrađuj <b style="color: #289cdb;  font-family: 'Facebook Sans Bold', sans-serif;">deklaracije</b> do <b style="color: #289cdb;  font-family: 'Facebook Sans Bold', sans-serif;">11X brže</b> i uštedi
</h2>
                        <div class="header-images-area">
                    <div class="header-elements1 reveal h-100 w-100">
                        <img src="{{ URL::asset('build/images/homepage/img/home2.png') }}" alt="">
                    </div>
                    <div class="header-elements2" data-aos="zoom-out" data-aos-duration="1000">
                        <img src="{{ URL::asset('build/images/homepage/elements/scan.png') }}" alt="" class="aniamtion-key-3">
                    </div>
                </div>

                    <p class="mb-0" data-aos="fade-up" data-aos-duration="1000">Automatsko skeniranje carinskih deklaracija uz pomoć AI – bez čekanja. <br>Uz <strong class="logo-span">deklarant<span class="ai-span" style="color:#289cdb!important;">.ai</span></strong>, svaki unos postaje jednostavan, precizan i odmah dostupan u nekoliko sekundi – oslobodite se ručne obrade i fokusirajte se na ono što je važno.</p>
                    <div data-aos="fade-up" data-aos-duration="1200">
                      <a href="#" class="header-btn2 rounded-1 openRegister  bottom-buttons">Započni </a>
                    <a href="#" class="header-btn2 ms-2 rounded-1  bottom-buttons">Provjeri funkcionalnosti</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6  ps-lg-0 d-none d-lg-block" id="appImage">
                <div class="header-images-area">
                    <div class="header-elements1 reveal h-100 w-100">
                        <img src="{{ URL::asset('build/images/homepage/img/home2.png') }}" alt="">
                    </div>
                    <div class="header-elements2" data-aos="zoom-out" data-aos-duration="1000">
                        <img src="{{ URL::asset('build/images/homepage/elements/scan.png') }}" alt="" class="aniamtion-key-3">
                    </div>
                </div>
            </div>
            <!-- Desna forma -->
      <!-- LOGIN FORMA -->
<div class="col-lg-6 d-flex align-items-center justify-content-center d-none" id="loginForm">
  <div class="form-section card bg-white kartica">
       <div class="text-center mb-2">
      <h5 class="fw-bold pb-2" style="font-family: 'Facebook Sans', sans-serif; font-size: 16px">
       Prijavi se na <span class="logo-span">deklarant<span class="ai-span">.ai</span></span> platformu
      </h5>
      <p class="text-muted form-label" style="margin-top: -10px;">Koristi svoje postojeće podatke</p>
    </div>
     <form id="login-form">
      @csrf
      <div class="mb-3">
        <label for="email" class="form-label">Korisničko ime</label>
        <input type="email" class="form-control" id="username"  placeholder="Unesite korisničko ime">
      </div>
      <div class="mb-3">
        <label for="password-input" class="form-label">Lozinka</label>
        <div class="password-wrapper">
          <input type="password" class="form-control" id="password-input" placeholder="Unesite lozinku">
          <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password-input', this)"></i>
        </div>
        <div class="text-end mt-1">
          <a href="#" class="small password back-link form-label">Zaboravljena lozinka?</a>
        </div>
      </div>

      <button type="submit" class="header-btn2 rounded-1 w-100 text-white" style="border:unset" id="login-btn">
        <span class="button-text">Prijavi se</span>
        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
      </button>

      <div class="divider">ili</div>

      <a href="#" class="header-btn2 btn-soft-info rounded-1 w-100 mb-3 openRegister">Registruj se</a>

      <div class="text-center">
        <small class="text-muted back-link fs-17">
          <a href="#" class="back-link form-label back-home-mobile  d-block d-lg-none"><i class="fa fa-chevron-left me-1 mt-2"></i> Povratak na početnu stranicu</a>
          <a href="#" class="back-link form-label back-home  d-none d-lg-block"><i class="fa fa-chevron-left me-1 mt-2"></i> Povratak na početnu stranicu</a>
        </small>
      </div>
    </form>
  </div>
</div>

<!-- REGISTRACIJA FORMA -->
<div class="col-lg-6 d-flex align-items-center justify-content-center d-none" id="registerForm">
  <div class="form-section card bg-white kartica">
    <div class="text-center mb-2">
      <h5 class="fw-bold pb-2" style="font-family: 'Facebook Sans', sans-serif; font-size: 16px">
        Kreiraj novi <span class="logo-span">deklarant<span class="ai-span">.ai</span></span> račun
      </h5>
      <p class="text-muted form-label" style="margin-top: -10px;">Napravite svoj besplatni nalog</p>
    </div>
    <form id="registerionForm" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="email" class="form-label">Email adresa <span class="text-danger">*</span></label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Unesite email adresu" required>
      </div>
      <div class="mb-3">
        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Unesite korisničko ime" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Lozinka <span class="text-danger">*</span></label>
        <div class="password-wrapper">
          <input type="password" class="form-control" id="password" name="password" placeholder="Unesite lozinku" required>
          <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
        </div>
      </div>
      <div class="mb-3">
        <label for="confirm_password" class="form-label">Potvrdite lozinku <span class="text-danger">*</span></label>
        <div class="password-wrapper">
          <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Potvrdi lozinku">
          <i class="fa-solid fa-eye toggle-password"  onclick="togglePassword('confirm_password', this)"></i>
        </div>
      </div>

      <button type="submit" class="header-btn2 rounded-1 w-100 mt-3 text-white" style="border:unset" id="register-btn">
        <span class="button-text">Napravi račun</span>
        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
      </button>

      <div class="text-center mb-3 mt-2">
        <small class="text-muted form-label">Imate svoj račun?
          <a href="#" class="back-link" id="backToLogin">Prijavite se</a>
        </small>
      </div>

      <div class="text-center">
        <small class="text-muted back-link fs-17">
             <a href="#" class="back-link form-label back-home-mobile  d-block d-lg-none"><i class="fa fa-chevron-left me-1 mt-2"></i> Povratak na početnu stranicu</a>
          <a href="#" class="back-link form-label back-home  d-none d-lg-block"><i class="fa fa-chevron-left me-1 mt-2"></i> Povratak na početnu stranicu</a>
        </small>
      </div>
    </form>
  </div>
</div>


        </div>
    </div>
</div>
<!--===== WELCOME ENDS =======-->


<!--===== GENERATION AREA with Local Lottie Icons =======-->
<div class="genaration2-section-area sp5" id="functions">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 m-auto">
        <div class="genaration2-header text-center heading4">
          <span data-aos="fade-up" data-aos-duration="800" class="mb-4">Pametna obrada deklaracija</span>
          <h2 data-aos="fade-up" data-aos-duration="800" class="naslov main-naslov">
            Šta nudi <b style="font-family: 'Facebook Sans Bold', sans-serif; color: black;">deklarant</b><b style="color: #289cdb;  font-family: 'Facebook Sans Bold', sans-serif;">.ai</b>?
          </h2>
        </div>
      </div>
    </div>
    <div class="space60"></div>

    <div class="row text-center">
      <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-duration="800">
        <div class="mb-3">
          <lord-icon
              src="/build/images/lottie/wired-outline-2844-magic-wand-hover-pinch.json"
              trigger="hover"
              stroke="regular"
              colors="primary:#121331,secondary:#289cdb"
              style="width:70px;height:70px">
          </lord-icon>
        </div>
        <h5 class="fw-bold text-black">AI prepoznavanje i klasifikacija</h5>
        <p>Na osnovu opisa artikala, sistem automatski predlaže najtačniju tarifnu oznaku iz baze – brzo i precizno.</p>
      </div>

      <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-duration="1000">
        <div class="mb-3">
          <lord-icon
              src="/build/images/lottie/wired-outline-245-edit-document-hover-pinch.json"
              trigger="hover"
              stroke="regular"
              style="width:65px;height:65px">
          </lord-icon>
        </div>
        <h5 class="fw-bold  text-black">Revizija i uređivanje</h5>
        <p>Korisnik ima punu kontrolu – svi AI rezultati su otvoreni za izmjene prije eksportovanja.</p>
      </div>

      <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-duration="1200">
        <div class="mb-3">
          <lord-icon
              src="/build/images/lottie/wired-outline-56-document-hover-swipe.json"
              trigger="hover"
              stroke="regular"
              style="width:60px;height:60px">
          </lord-icon>
        </div>
        <h5 class="fw-bold  text-black">Eksport u više formata</h5>
        <p>Generiši dokumente u PDF, CSV (za tarifne softvere) i XML formatima – spremno za carinu i štampu.</p>
      </div>
    </div>

    <div class="row text-center mt-4">
      <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-duration="1400">
        <div class="mb-3">
          <lord-icon
              src="/build/images/lottie/wired-outline-12-layers-hover-slide.json"
              trigger="hover"
              stroke="regular"
              style="width:65px;height:65px">
          </lord-icon>
        </div>
        <h5 class="fw-bold  text-black">Baza uvoznika i partnera</h5>
        <p>Pristupi svojoj internoj bazi, ali i globalnoj koju obezbjeđuje deklarant.ai – sve centralizirano.</p>
      </div>

      <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-duration="1600">
        <div class="mb-3">
          <lord-icon
              src="/build/images/lottie/wired-outline-212-arrow-1-rounded-hover-pinch.json"
              trigger="hover"
              stroke="regular"
              style="width:60px;height:60px">
          </lord-icon>
        </div>
        <h5 class="fw-bold  text-black">Do 11x brža obrada</h5>
        <p>Zahvaljujući AI automatizaciji, unos deklaracija postaje munjevito brz – bez ručnog unosa.</p>
      </div>

      <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-duration="1800">
        <div class="mb-3">
          <lord-icon
              src="/build/images/lottie/wired-outline-1140-error-hover-enlarge.json"
              trigger="hover"
              stroke="regular"
              style="width:65px;height:65px">
          </lord-icon>
        </div>
        <h5 class="fw-bold  text-black">Sigurnost i pouzdanost</h5>
        <p>Podaci su zaštićeni, a tarifna baza ažurirana u skladu sa službenim pravilnicima.</p>
      </div>
    </div>
  </div>
</div>





<!--===== PROCESS AREA STARTS =======-->
<div class="process-section-area sp5">
  <div class="container">
    <div class="row pt-2 pb-3">
      <div class="col-lg-6 m-auto">
        <div class="process-header heading4 text-center">
          <span data-aos="fade-up" data-aos-duration="800">Kako funkcioniše</span>
          <h2 data-aos="fade-up" data-aos-duration="1000" class="main-naslov">Od dokumenta do <strong style=" color: #289cdb!important;">gotove deklaracije</strong> – jednostavno u 5 koraka</h2>
        </div>
      </div>
    </div>
    <div class="space60 d-none d-md-block"></div>

    <!-- Korak 1 -->
    <div class="row align-items-center mb-4 mb-mb-0">
      <div class="col-lg-7 order-2 order-md-1">
        <div class="process-images reveal">
          <img src="build/images/homepage/img/process-img.png" alt="Upload dokumenta" class="aniamtion-key-3">
        </div>
      </div>
      <div class="col-lg-5 order-1 order-md-2">
        <div class="process-images-content heading4">
          <span data-aos="fade-left" data-aos-duration="800">1. Učitaj deklaraciju</span>
          <h2 data-aos="fade-left" data-aos-duration="1000">Upload carinske fakture</h2>
          <p data-aos="fade-left" data-aos-duration="1200">Uvezi PDF, Excel ili uslikaj dokument fakture. Naš sistem automatski prepoznaje sadržaj i priprema ga za obradu.</p>
        </div>
      </div>
    </div>

    <!-- Korak 2 -->
    <div class="process-section2 ps-0 pe-0  mb-4 mb-mb-0">
      <img src="assets/images/elements/process-elements1.png" alt="" class="process-elements1 d-lg-block d-none">
      <img src="assets/images/elements/process-elements2.png" alt="" class="process-elements2 d-lg-block d-none">
      <div class="row align-items-center">
        <div class="col-lg-5">
          <div class="process-images-content heading4">
            <span data-aos="fade-right" data-aos-duration="800">2. Prepoznavanje klijenata i dobavljača</span>
            <h2 data-aos="fade-right" data-aos-duration="1000">Automatsko pronalaženje</h2>
            <p data-aos="fade-right" data-aos-duration="1200">Sistem automatski traži klijenta i dobavljača po nazivu, ID-u ili PIB-u. Ako postoji u tvojoj bazi ili globalnoj bazi deklarant.ai, podaci se odmah popunjavaju.</p>
          </div>
        </div>
        <div class="col-lg-7">
          <div class="process-images reveal">
            <img src="build/images/homepage/img/process-img-3.png" alt="Prepoznavanje firmi" class="aniamtion-key-1">
          </div>
        </div>
      </div>
    </div>

    <!-- Korak 3 -->
    <div class="row align-items-center sekcija" >
      <div class="col-lg-7 order-2 order-md-1">
        <div class="process-images reveal">
          <img src="build/images/homepage/img/process-img-4.png" alt="AI klasifikacija"  class="aniamtion-key-3">
        </div>
      </div>
      <div class="col-lg-5 order-1 order-md-2">
        <div class="process-images-content heading4" >
          <span data-aos="fade-left" data-aos-duration="800">3. AI analiza i prijedlozi</span>
          <h2 data-aos="fade-left" data-aos-duration="1000">Automatska klasifikacija artikala</h2>
          <p data-aos="fade-left" data-aos-duration="1200">AI prepoznaje sve stavke i automatski predlaže najtačniju tarifnu oznaku za svaki artikal. Sve prijedloge možete pregledati i ručno prilagoditi.</p>
        </div>
      </div>
    </div>

    <!-- Korak 4 -->
    <div class="row align-items-center sekcija" >
      <div class="col-lg-5">
        <div class="process-images-content heading4" style="padding:0 50px 0 0!important">
          <span data-aos="fade-right" data-aos-duration="800">4. Spasi i uređuj deklaraciju</span>
          <h2 data-aos="fade-right" data-aos-duration="1000">Deklaracija ostaje u tvojoj arhivi</h2>
          <p data-aos="fade-right" data-aos-duration="1200">Nisi siguran u sve podatke odmah? Nema problema. Svaku deklaraciju možeš spremiti, kasnije uređivati i dovršiti kad god poželiš.</p>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="process-images reveal">
           <img src="build/images/homepage/img/process-img-5.png" alt="Upload dokumenta" class="aniamtion-key-1">
        </div>
      </div>
    </div>

    <!-- Korak 5 -->
    <div class="row align-items-center">
      <div class="col-lg-6">
        <div class="process-images reveal">
          <img src="assets/images/all-images/process-img3.png" alt="Eksport fajla">
        </div>
      </div>
      <div class="col-lg-6">
        <div class="process-images-content heading4">
          <span data-aos="fade-left" data-aos-duration="800">5. Eksport i dostava</span>
          <h2 data-aos="fade-left" data-aos-duration="1000">Preuzmi gotovu deklaraciju</h2>
          <p data-aos="fade-left" data-aos-duration="1200">Kada ste zadovoljni, jednim klikom eksportujte podatke u PDF, CSV ili XML format – spremno za carinu, internu arhivu ili tarifne softvere.</p>
        </div>
      </div>
    </div>
  </div>
</div>
<!--===== PROCESS AREA ENDS =======-->


@endsection

@section('script')
    <!--=====JS=======-->
 

 <script src="{{ URL::asset('build/js/login-js/plugins/jquery-3-6-0.min.js') }}"></script>

    <script>
  $(document).ready(function () {
    // Otvori login formu
    $(".loginFormShow").on("click", function (e) {
      e.preventDefault();
      $("#appImage").removeClass("d-lg-block");
      $("#registerForm").addClass("d-none");
      $("#mobileFirstContent").addClass("d-none");
      $("#loginForm").removeClass("d-none").hide().fadeIn(400);
      document.getElementById("username")?.focus();

    });

    // Otvori registraciju
    $(".openRegister").on("click", function (e) {
      e.preventDefault();
      $("#loginForm").addClass("d-none");
      $("#appImage").removeClass("d-lg-block");
      $("#mobileFirstContent").addClass("d-none");
      $("#registerForm").removeClass("d-none").hide().fadeIn(400);
    });

    // Povratak na login iz registracije
    $("#backToLogin").on("click", function (e) {
      e.preventDefault();
      $("#registerForm").addClass("d-none");
      $("#loginForm").removeClass("d-none").hide().fadeIn(400);
    });

    // Povratak na početnu
    $(".back-home").on("click", function (e) {
      e.preventDefault();
      $("#loginForm").addClass("d-none");
      $("#registerForm").addClass("d-none");
      $("#appImage").addClass("d-lg-block");
      $("#mobileFirstContent").removeClass("d-none");
        
    });
      
      // Povratak na početnu mobile
    $(".back-home-mobile").on("click", function (e) {
      e.preventDefault();
      $("#loginForm").addClass("d-none");
      $("#registerForm").addClass("d-none");
      $("#mobileFirstContent").removeClass("d-none");
        
    });

    // Globalna funkcija za prikaz/skrivanje lozinki
    window.togglePassword = function (id, el) {
      const input = document.getElementById(id);
      if (!input) return;
      const icon = el;
      if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
      } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
      }
    }
  });
</script>
<script>
    $(window).on('load', function () {
        $('#preloader').fadeOut('slow', function () {
            $(this).remove();
        });
    });
</script>



<script src="{{ URL::asset('build/js/login-js/plugins/aos.js') }}"></script>
<script src="{{ URL::asset('build/js/login-js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('build/js/login-js/plugins/counter.js') }}"></script>
<script src="{{ URL::asset('build/js/login-js/plugins/gsap.min.js') }}"></script>
<script src="{{ URL::asset('build/js/login-js/plugins/magnific-popup.js') }}"></script>
<script src="{{ URL::asset('build/js/login-js/plugins/mobilemenu.js') }}"></script>
<script src="{{ URL::asset('build/js/login-js/plugins/owlcarousel.min.js') }}"></script>
<script src="{{ URL::asset('build/js/login-js/plugins/ScrollTrigger.min.js') }}"></script>
<script src="{{ URL::asset('build/js/login-js/plugins/slick-slider.js') }}"></script>
<script src="{{ URL::asset('build/js/login-js/plugins/swiper.bundle.js') }}"></script>
<script src="{{ URL::asset('build/js/login-js/plugins/waypoints.js') }}"></script>

<script src="{{ URL::asset('build/js/login-js/main.js') }}"></script>






<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!--=====Login logic=======-->
<script>
    // Dummy MAC generator (for local dev)
    function getMACAddress() {
        return '00:11:22:33:44:55';
    }

    document.addEventListener("DOMContentLoaded", function() {
        const loginBtn = document.getElementById("login-btn");
        const buttonText = loginBtn.querySelector(".button-text");
        const spinner = loginBtn.querySelector(".spinner-border");

        loginBtn.addEventListener("click", async function(event) {
            event.preventDefault();
            
            // Disable button and show loader
            loginBtn.disabled = true;
            buttonText.classList.add("d-none");
            spinner.classList.remove("d-none");

            try {
                const username = document.getElementById("username").value;
                const password = document.getElementById("password-input").value;

                if (!username || !password) {
                    // Re-enable button and hide loader
                    loginBtn.disabled = false;
                    buttonText.classList.remove("d-none");
                    spinner.classList.add("d-none");

                    Swal.fire({
                        icon: 'warning',
                        title: 'Nedostaju podaci',
                        text: 'Molimo unesite korisničko ime i lozinku.'
                    });
                    return;
                }

                const mac = getMACAddress();

                const response = await axios.post("/api/auth/login", {
                    username,
                    password,
                }, {
                    headers: {
                        'MAC-Address': mac,
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,         
                    }
                });

                const { redirect } = response.data;

                Swal.fire({
                    icon: 'success',
                    title: 'Prijava uspješna!',
                    text: 'Uspješno ste prijavljeni',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = redirect || "/";
                });
            } catch (error) {
                // Re-enable button and hide loader
                loginBtn.disabled = false;
                buttonText.classList.remove("d-none");
                spinner.classList.add("d-none");
                
                console.error("Login error:", error);
                
                let errorMessage = error.response?.data?.message || "Došlo je do greške prilikom prijave. Molimo pokušajte ponovo.";
                
                // Translate specific error messages
                if (errorMessage === "Invalid credentials") {
                    errorMessage = "Pogrešno korisničko ime ili lozinka";
                }

                Swal.fire({
                    icon: "error",
                    title: "Greška",
                    text: errorMessage,
                    confirmButtonText: "U redu",
                    customClass: {
                        confirmButton: 'btn btn-info w-xs mt-2',
                    },
                });
            }
        });
    });
</script>

 <!--=====Register logic=======-->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const registerBtn = document.getElementById("register-btn");
        const registerButtonText = registerBtn.querySelector(".button-text");
        const registerSpinner = registerBtn.querySelector(".spinner-border");

        document.getElementById("registerionForm").addEventListener("submit", async function(event) {
            event.preventDefault();
            
            // Disable button and show loader
            registerBtn.disabled = true;
            registerButtonText.classList.add("d-none");
            registerSpinner.classList.remove("d-none");

            try {
                const form = document.getElementById("registerionForm");
                const formData = new FormData(form);

                // Check password confirmation before submitting
                const password = formData.get('password');
                const confirmPassword = formData.get('confirm_password');

                if (password !== confirmPassword) {
                    // Re-enable button and hide loader
                    registerBtn.disabled = false;
                    registerButtonText.classList.remove("d-none");
                    registerSpinner.classList.add("d-none");

                    Swal.fire({
                        icon: "error",
                        title: "Greška",
                        text: "Potvrda lozinke se ne podudara",
                        confirmButtonText: "U redu",
                        customClass: {
                            confirmButton: 'btn btn-info w-xs mt-2',
                        },
                    });
                    return;
                }

                const response = await axios.post("/api/auth/register", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                });

                const { token, user, message } = response.data;

                // Save token & user info
                localStorage.setItem("auth_token", token);
                localStorage.setItem("user", JSON.stringify(user));
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

                Swal.fire({
                    icon: 'success',
                    title: 'Registracija uspješna!',
                    text: message || 'Uspješno ste registrovani',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = "/";
                });

            } catch (error) {
                // Re-enable button and hide loader on error
                registerBtn.disabled = false;
                registerButtonText.classList.remove("d-none");
                registerSpinner.classList.add("d-none");
                
                console.error("Registration error:", error);
                
                let errorMessage = error.response?.data?.message || "Došlo je do greške prilikom registracije. Molimo pokušajte ponovo.";
                
                // Get the first validation error if it exists
                if (error.response?.data?.errors) {
                    const firstError = Object.values(error.response.data.errors)[0];
                    if (Array.isArray(firstError) && firstError.length > 0) {
                        errorMessage = firstError[0];
                    }
                }

                Swal.fire({
                    icon: "error",
                    title: "Greška",
                    text: errorMessage,
                    confirmButtonText: "U redu",
                    customClass: {
                        confirmButton: 'btn btn-info w-xs mt-2',
                    },
                });
            }
        });
    });
</script>

@endsection
