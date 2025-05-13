@extends('layouts.master')
@section('css')
<style>
    .card.pricing-box {
        box-shadow: 0 15px 35px rgba(0, 123, 255, 0.15), 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 0.75rem;
    }

    .card.pricing-box:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 45px rgba(0, 123, 255, 0.2), 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .card.pricing-box.ribbon-box {
        box-shadow: 0 25px 55px rgba(13, 110, 253, 0.35), 0 10px 30px rgba(0, 0, 0, 0.1);
        transform: scale(1.02);
        z-index: 1;
    }
    .card.pricing-box.ribbon-box:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 45px rgba(0, 123, 255, 0.2), 0 8px 20px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection
@section('title') @lang('translation.pricing') @endsection
@section('content')




<div class="row justify-content-center mt-5">
    <div class="col-lg-5">
        <div class="text-center mb-4 pb-2">
            <h4 class="fw-semibold fs-22">Odaberi paket koji odgovara tvojim potrebama</h4>
            <p class="text-muted mb-4 fs-15">Jednostavno plati, odaberi željenu opciju i odustani kad god želiš <br>(bez dodatnih naknada)</p>
        </div>
    </div><!--end col-->
</div><!--end row-->

<div class="container">

    <div class="row justify-content-center">
        <!-- StartUp Plan -->
        <div class="col-lg-4 mb-4">
            <div class="card pricing-box border-0 rounded-0 h-100">
                <div class="card-body p-4 m-2 d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-grow-1">
                            <h5 class="fw-semibold mb-1">StartUp</h5>
                            <p class="text-muted mb-0">Za manja preduzeća</p>
                        </div>
                        <div class="avatar-sm">
                            <div class="avatar-title bg-light rounded-circle text-ifno">
                                <i class="ri-star-s-fill text-info fs-5"></i>
                            </div>
                        </div>
                    </div>
                    <div class="pt-2 pb-3">
                        <h2>500 <small class="fs-5">KM</small><span class="fs-6 text-muted"></span></h2>
                    </div>
                    <hr class="my-3 text-muted">
                    <ul class="list-unstyled text-muted vstack gap-3 mb-3">
                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>100</b> AI Skeniranja Fakture</li>
                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>50 strana</b> po Fakturi</li>
                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>200</b> Faktura u historiji</li>
                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i>Prosječna brzina skeniranja: <b>20 s</b></li>
                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i>30 dana</li>
                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>24/7</b> Support</li>
                    </ul>
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#paymentChoiceModal"
                        class="btn btn-info w-100 mt-auto text-white">Započni</a>
                </div>
            </div>
        </div>

        <!-- GoBig Plan -->
        <div class="col-lg-4 mb-4">
            <div class="card pricing-box border-0 rounded-0 ribbon-box right h-100">
                <div class="card-body p-4 m-2 d-flex flex-column">
                    <div class="ribbon-two ribbon-two-info"><span>Popularno</span></div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-grow-1">
                            <h5 class="fw-semibold mb-1">GoBig</h5>
                            <p class="text-muted mb-0">Idealno za biznise u razvoju</p>
                        </div>
                        <div class="avatar-sm">
                            <div class="avatar-title bg-light rounded-circle text-primary">
                                <i class="ri-medal-line text-info fs-3"></i>
                            </div>
                        </div>
                    </div>
                    <div class="pt-2 pb-3">
                        <h2>850 <small class="fs-5">KM</small><span class="fs-6 text-muted"></span></h2>
                    </div>
                    <hr class="my-3 text-muted">
                    <ul class="list-unstyled text-muted vstack gap-3 mb-3">
                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>200</b> AI Skeniranja Fakture</li>
                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>150 strana</b> po Fakturi</li>
                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>500</b> Faktura u historiji</li>
                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i>Prosječna brzina skeniranja: <b>10 s</b></li>
                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i>120 dana</li>
                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>24/7</b> Support</li>
                    </ul>
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#paymentChoiceModal"
                        class="btn btn-info w-100 mt-auto text-white">Započni</a>
                </div>
            </div>
        </div>

        <!-- Business Plan -->
        <div class="col-lg-4 mb-4">
            <div class="card pricing-box border-0 rounded-0 h-100">
                <button type="button" class="btn btn-soft-info btn-sm shadow-none w-100" style="position: absolute; border-bottom-left-radius: 0; border-bottom-right-radius:0">
                                         <i class="ri-file-list-3-line align-middle"></i> Aktivan do: 25.08.2025. godine
                                    </button>
                <div class="card-body p-4 m-2 d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-grow-1">
                            <h5 class="fw-semibold mb-1">Business</h5>
                            <p class="text-muted mb-0">Skrojeno za velike biznise</p>
                        </div>
                        <div class="avatar-sm">
                            <div class="avatar-title bg-light rounded-circle text-primary">
                                <i class="ri-shield-star-line text-info fs-2"></i>
                            </div>
                        </div>
                    </div>
                    <div class="pt-2 pb-3">
                        <h2>2000 <small class="fs-5">KM</small><span class="fs-6 text-muted"></span></h2>
                    </div>
                    <hr class="my-3 text-muted">
                    <ul class="list-unstyled text-muted vstack gap-3 mb-3">
                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>500</b> AI Skeniranja Fakture</li>
                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>Neograničeno strana </b> po Fakturi</li>
                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>Neograničeno</b> faktura u historiji</li>
                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i>Prosječna brzina skeniranja: <b>4 s</b></li>
                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i>365 dana</li>
                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>24/7</b> Support</li>
                    </ul>
                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#paymentChoiceModal"
                        class="btn btn-info w-100 mt-auto text-white">Produži</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Method Modal -->
<!-- Modal za plaćanje karticom -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-info  rounded-0 shadow">
            <div class=" d-flex align-items-center justify-content-between bg-info text-white py-1">
                <h5 class="modal-title d-flex align-items-center  ms-1 me-1 text-white"
                    id="paymentModalLabel">Unesite vaše podatke</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Zatvori"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="cardName" class="form-label">Ime i prezime vlasnika kartice</label>
                        <input type="text" class="form-control border-info rounded-0" id="cardName"
                            placeholder="Ime i Prezime">
                    </div>
                    <div class="mb-3">
                        <label for="cardNumber" class="form-label">Broj kartice</label>
                        <div class="input-group">
                            <input type="text" class="form-control border-info rounded-0" id="cardNumber"
                                placeholder="0000 0000 0000 0000">
                            <span class="input-group-text bg-white border-info rounded-0">
                                <img src="https://img.icons8.com/color/32/000000/mastercard-logo.png"
                                    id="cardLogo" alt="Mastercard" height="20">
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="expiry" class="form-label">Datum isteka</label>
                            <input type="text" class="form-control border-info rounded-0" id="expiry"
                                placeholder="MM/GG">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cvc" class="form-label">CVC kod</label>
                            <input type="text" class="form-control border-info rounded-0" id="cvc"
                                placeholder="123">
                        </div>
                    </div>


                    <div class="form-check mb-3">
                        <input class="form-check-input border-info rounded-0" type="checkbox" id="saveCard">
                        <label class="form-check-label" for="saveCard">
                            Sačuvaj podatke o kartici za naredna plaćanja
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input border-info rounded-0" type="checkbox"
                            id="termsCheck">
                        <label class="form-check-label" for="termsCheck">
                            Prihvatam <a href="#">uslove korištenja</a> i <a href="#">politiku
                                privatnosti</a>
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-center align-items center ">
                <button type="button" class="btn btn-outline-info w-50"
                    data-bs-dismiss="modal">Otkaži</button>
                <button type="submit" class="btn btn-info text-white w-50">Plati</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal za izbor načina plaćanja -->
<div class="modal fade" id="paymentChoiceModal" tabindex="-1" aria-labelledby="paymentChoiceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-info  shadow">
            <div class="d-flex justify-content-between bg-info py-1 px-1 text-white">

                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Zatvori"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-4">Molimo odaberite način na koji želite izvršiti uplatu:</p>
                <div class="d-grid gap-3">
                    <button class="btn btn-info text-white" data-bs-dismiss="modal" data-bs-toggle="modal"
                        data-bs-target="#paymentModal">
                        💳 Kartično plaćanje
                    </button>
                    <button class="btn btn-outline-info" data-bs-dismiss="modal" data-bs-toggle="modal"
                        data-bs-target="#virmanModal">
                        🧾 Plaćanje virmanom
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal za plaćanje virmanom -->
<div class="modal fade" id="virmanModal" tabindex="-1" aria-labelledby="virmanModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-info ">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="virmanModalLabel">Upute za plaćanje virmanom</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Zatvori"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Naziv primaoca:</label>
                    <p class="mb-0">Qla Dev d.o.o. Sarajevo</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Broj računa (IBAN):</label>
                    <p class="mb-0">BA39 1542 0512 3456 7890</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Svrha uplate:</label>
                    <p class="mb-0">Uplata paketa usluga – <span class="text-info fw-semibold">[StartUp /
                            GoBig / Business]</span></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Iznos:</label>
                    <p class="mb-0 text-info fs-5">[unesi iznos u KM]</p>
                </div>
                <hr>
                <p class="text-muted small">
                    Nakon izvršene uplate, molimo pošaljite dokaz o uplati na email:
                    <strong>uplate@qla.dev</strong> radi brže obrade.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Zatvori</button>
                <button type="button" class="btn btn-info text-white">Uredu</button>
            </div>
        </div>
    </div>
</div>




@endsection
@section('script')
<script src="{{ URL::asset('build/js/pages/pricing.init.js') }}"></script>

<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection