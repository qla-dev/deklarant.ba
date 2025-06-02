@extends('layouts.master')
@section('title') Česta pitanja - deklarant.ba @endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="row justify-content-evenly mb-4">
                <div class="col-lg-4">
                    <div class="">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 me-1">
                                <i class="ri-information-line fs-24 align-middle text-info me-1"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-16 mb-0 fw-semibold">O platformi</h5>
                            </div>
                        </div>
                        <div class="accordion accordion-border-box" id="genques-accordion">
                            <div class="accordion-item shadow">
                                <h2 class="accordion-header" id="genques-headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#genques-collapseOne" aria-expanded="true" aria-controls="genques-collapseOne">
                                        Šta je deklarant.ba?
                                    </button>
                                </h2>
                                <div id="genques-collapseOne" class="accordion-collapse collapse show" aria-labelledby="genques-headingOne" data-bs-parent="#genques-accordion">
                                    <div class="accordion-body">
                                        deklarant.ba je digitalna platforma za izradu, upravljanje i praćenje carinskih deklaracija i prateće dokumentacije. Omogućava automatsko prepoznavanje podataka iz faktura i drugih dokumenata, te značajno ubrzava i pojednostavljuje carinske procedure za firme i fizička lica.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item shadow">
                                <h2 class="accordion-header" id="genques-headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#genques-collapseTwo" aria-expanded="false" aria-controls="genques-collapseTwo">
                                        Koje probleme rješava deklarant.ba?
                                    </button>
                                </h2>
                                <div id="genques-collapseTwo" class="accordion-collapse collapse" aria-labelledby="genques-headingTwo" data-bs-parent="#genques-accordion">
                                    <div class="accordion-body">
                                        Platforma uklanja potrebu za ručnim unosom podataka, smanjuje greške i omogućava automatsko popunjavanje deklaracija na osnovu učitanih dokumenata. Sve deklaracije i statusi su dostupni na jednom mjestu, što olakšava praćenje i arhiviranje.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item shadow">
                                <h2 class="accordion-header" id="genques-headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#genques-collapseThree" aria-expanded="false" aria-controls="genques-collapseThree">
                                        Kako funkcioniše automatsko prepoznavanje podataka?
                                    </button>
                                </h2>
                                <div id="genques-collapseThree" class="accordion-collapse collapse" aria-labelledby="genques-headingThree" data-bs-parent="#genques-accordion">
                                    <div class="accordion-body">
                                        Nakon što učitate fakturu ili drugi dokument, sistem koristi napredne algoritme za prepoznavanje teksta i automatski popunava polja deklaracije. Sve podatke možete dodatno provjeriti i korigovati prije slanja.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item shadow">
                                <h2 class="accordion-header" id="genques-headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#genques-collapseFour" aria-expanded="false" aria-controls="genques-collapseFour">
                                        Ko može koristiti deklarant.ba?
                                    </button>
                                </h2>
                                <div id="genques-collapseFour" class="accordion-collapse collapse" aria-labelledby="genques-headingFour" data-bs-parent="#genques-accordion">
                                    <div class="accordion-body">
                                        Platforma je namijenjena špediterima, uvoznicima, izvoznicima, logističkim kompanijama i fizičkim licima koja povremeno uvoze ili izvoze robu.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item shadow">
                                <h2 class="accordion-header" id="genques-headingFive">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#genques-collapseFive" aria-expanded="false" aria-controls="genques-collapseFive">
                                        Da li je korištenje platforme usklađeno sa zakonima?
                                    </button>
                                </h2>
                                <div id="genques-collapseFive" class="accordion-collapse collapse" aria-labelledby="genques-headingFive" data-bs-parent="#genques-accordion">
                                    <div class="accordion-body">
                                        Da, deklarant.ba je usklađen sa zakonima o zaštiti podataka i carinskim propisima Bosne i Hercegovine.
                                    </div>
                                </div>
                            </div>
                        </div><!--end accordion-->
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 me-1">
                                <i class="ri-tools-line fs-24 align-middle text-info me-1"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-16 mb-0 fw-semibold">Korištenje i funkcionalnosti</h5>
                            </div>
                        </div>
                        <div class="accordion accordion-border-box" id="manageaccount-accordion">
                            <div class="accordion-item shadow">
                                <h2 class="accordion-header" id="manageaccount-headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#manageaccount-collapseOne" aria-expanded="true" aria-controls="manageaccount-collapseOne">
                                        Kako započeti korištenje deklarant.ba?
                                    </button>
                                </h2>
                                <div id="manageaccount-collapseOne" class="accordion-collapse collapse show" aria-labelledby="manageaccount-headingOne" data-bs-parent="#manageaccount-accordion">
                                    <div class="accordion-body">
                                        Registrujte se na platformi, potvrdite e-mail i odmah možete unositi ili učitavati dokumente za izradu deklaracija.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item shadow">
                                <h2 class="accordion-header" id="manageaccount-headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#manageaccount-collapseTwo" aria-expanded="false" aria-controls="manageaccount-collapseTwo">
                                        Kako se unose podaci za deklaraciju?
                                    </button>
                                </h2>
                                <div id="manageaccount-collapseTwo" class="accordion-collapse collapse" aria-labelledby="manageaccount-headingTwo" data-bs-parent="#manageaccount-accordion">
                                    <div class="accordion-body">
                                        Podaci se mogu unijeti ručno ili automatski učitavanjem dokumenata (faktura, otpremnica). Sistem prepoznaje podatke i popunjava polja, a vi ih možete provjeriti i izmijeniti.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item shadow">
                                <h2 class="accordion-header" id="manageaccount-headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#manageaccount-collapseThree" aria-expanded="false" aria-controls="manageaccount-collapseThree">
                                        Kako mogu pratiti status svojih deklaracija?
                                    </button>
                                </h2>
                                <div id="manageaccount-collapseThree" class="accordion-collapse collapse" aria-labelledby="manageaccount-headingThree" data-bs-parent="#manageaccount-accordion">
                                    <div class="accordion-body">
                                        U profilu imate pregled svih deklaracija sa statusima (u pripremi, poslano, obrađeno, odbijeno). Svaka promjena statusa je vidljiva u realnom vremenu.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item shadow">
                                <h2 class="accordion-header" id="manageaccount-headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#manageaccount-collapseFour" aria-expanded="false" aria-controls="manageaccount-collapseFour">
                                        Da li mogu koristiti platformu za više firmi ili korisnika?
                                    </button>
                                </h2>
                                <div id="manageaccount-collapseFour" class="accordion-collapse collapse" aria-labelledby="manageaccount-headingFour" data-bs-parent="#manageaccount-accordion">
                                    <div class="accordion-body">
                                        Da, možete kreirati više profila i povezati ih sa različitim firmama ili odjelima, te upravljati pristupima i ovlaštenjima.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item shadow">
                                <h2 class="accordion-header" id="manageaccount-headingFive">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#manageaccount-collapseFive" aria-expanded="false" aria-controls="manageaccount-collapseFive">
                                        Šta ako naiđem na problem ili imam dodatna pitanja?
                                    </button>
                                </h2>
                                <div id="manageaccount-collapseFive" class="accordion-collapse collapse" aria-labelledby="manageaccount-headingFive" data-bs-parent="#manageaccount-accordion">
                                    <div class="accordion-body">
                                        Na raspolaganju je podrška putem e-maila i kontakt forme. Također, dostupna je baza znanja i video uputstva.
                                    </div>
                                </div>
                            </div>
                        </div><!--end accordion-->
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 me-1">
                                <i class="ri-lock-line fs-24 align-middle text-info me-1"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-16 mb-0 fw-semibold">Privatnost i sigurnost</h5>
                            </div>
                        </div>
                        <div class="accordion accordion-border-box" id="privacy-accordion">
                            <div class="accordion-item shadow">
                                <h2 class="accordion-header" id="privacy-headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#privacy-collapseOne" aria-expanded="true" aria-controls="privacy-collapseOne">
                                        Kako deklarant.ba štiti moje podatke?
                                    </button>
                                </h2>
                                <div id="privacy-collapseOne" class="accordion-collapse collapse show" aria-labelledby="privacy-headingOne" data-bs-parent="#privacy-accordion">
                                    <div class="accordion-body">
                                        Svi podaci su pohranjeni na sigurnim serverima u skladu sa GDPR regulativom i zakonima BiH. Pristup podacima je zaštićen višeslojnom autentifikacijom i enkripcijom.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item shadow">
                                <h2 class="accordion-header" id="privacy-headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#privacy-collapseTwo" aria-expanded="false" aria-controls="privacy-collapseTwo">
                                        Ko ima pristup mojim informacijama?
                                    </button>
                                </h2>
                                <div id="privacy-collapseTwo" class="accordion-collapse collapse" aria-labelledby="privacy-headingTwo" data-bs-parent="#privacy-accordion">
                                    <div class="accordion-body">
                                        Pristup vašim podacima imaju samo ovlašteni korisnici i administratori, u skladu sa politikom privatnosti i ugovorom o korištenju platforme.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item shadow">
                                <h2 class="accordion-header" id="privacy-headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#privacy-collapseThree" aria-expanded="false" aria-controls="privacy-collapseThree">
                                        Kako mogu izbrisati svoj nalog ili podatke?
                                    </button>
                                </h2>
                                <div id="privacy-collapseThree" class="accordion-collapse collapse" aria-labelledby="privacy-headingThree" data-bs-parent="#privacy-accordion">
                                    <div class="accordion-body">
                                        Brisanje naloga ili podataka možete zahtijevati putem profila ili kontaktiranjem podrške. Svi podaci se trajno uklanjaju iz sistema nakon potvrde zahtjeva.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item shadow">
                                <h2 class="accordion-header" id="privacy-headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#privacy-collapseFour" aria-expanded="false" aria-controls="privacy-collapseFour">
                                        Da li su moji dokumenti dostupni trećim licima?
                                    </button>
                                </h2>
                                <div id="privacy-collapseFour" class="accordion-collapse collapse" aria-labelledby="privacy-headingFour" data-bs-parent="#privacy-accordion">
                                    <div class="accordion-body">
                                        Ne, vaši dokumenti nisu dostupni trećim licima bez vaše izričite saglasnosti, osim u slučajevima propisanim zakonom.
                                    </div>
                                </div>
                            </div>
                        </div><!--end accordion-->
                    </div>
                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->
@endsection
@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
