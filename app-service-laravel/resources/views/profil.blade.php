@extends('layouts.master')
@section('title')
@lang('translation.profile')
@endsection
@section('css')
<link rel="stylesheet" href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css">
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .avatar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);

        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.4s ease-in-out;
        cursor: pointer;
        z-index: 2;

    }

    .text-custom {
        color: #fff !important;
        background-color: transparent !important;
        border: none !important;
        box-shadow: none !important;
        opacity: 0.9;

    }

    .text-custom:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: white !important;
    }


    #avatar-wrapper {
        overflow: visible;
        width: 100px;
        /* or match your avatar size */
        height: 100px;
        overflow: hidden;
        border-radius: 50%;
    }

    #avatar-wrapper:hover .avatar-overlay {
        opacity: 1;
    }

    .btn-custom-blue {
        background-color: #135071 !important;
        color: #fff !important;
        border: none;
    }

    .btn-custom-blue:hover {
        background-color: #0f3e59 !important;
        /* slightly darker on hover */
    }

    .dataTables_filter label {
        color: white !important;

    }

    .dataTables_filter input {
        border-radius: 0 !important;
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
        0% {
            transform: scale(0);
            opacity: 0;
        }

        60% {
            transform: scale(1.2);
            opacity: 1;
        }

        80% {
            transform: scale(0.95);
        }

        100% {
            transform: scale(1);
        }
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

    .modal-dialog.modal-xl {
        max-width: 75vw;
        /* or set fixed px: 1200px, 1400px */
    }



    /* Wrapper for search input */
</style>
@endsection
@section('content')

<!-- Profile Foreground -->
<div class="profile-foreground position-relative mx-n4 mt-n4">
    <div class="profile-wid-bg">
        <div class="text-end p-4" style="position: absolute; right: 0;">
            <div class="p-0 ms-auto rounded-circle profile-photo-edit">
                <input id="background-img-input" type="file" class="profile-foreground-img-file-input d-none">
                <label for="background-img-input" class="profile-photo-edit btn btn-light w-100" style="padding-left: 10px; padding-right: 10px; padding-top: 6px; padding-bottom: 6px;">
                    <i class="ri-image-edit-line align-bottom me-1"></i> Promijeni naslovnu sliku
                </label>
            </div>
        </div>
        <img id="profile-background" src="/images/profile-bg.jpg" class="profile-wid-img" alt="Profile Background">
    </div>
</div>

<!-- Profile Header -->
<div class="pt-4 mb-4 profile-wrapper pb-lg-4">
    <div class="row g-4 align-items-center">
        <div class="col-auto">
            <div class="avatar-lg position-relative" id="avatar-wrapper">
                <!-- Avatar image -->
                <img id="user-avatar" class="img-thumbnail rounded-circle">

                <div id="avatar-fallback" class="rounded-circle d-flex justify-content-center align-items-center text-white fw-bold d-none"
                    style="width: 100%; height: 100%; font-size: 2.5rem;">
                    <!-- initial goes here -->
                </div>

                <!-- Hover overlay with edit icon -->
                <div class="avatar-overlay position-absolute top-0 start-0 w-100 h-100   text-white bg-dark bg-opacity-50 rounded-circle"
                    style="z-index: 1; position: relative;">
                    <i class="ri-edit-2-fill fs-4"></i>
                </div>

                <!-- Hidden file input for avatar upload -->
                <input type="file" id="avatar-input" accept="image/*" style="display: none;">
            </div>
        </div>

        <div class="col">
            <div class="p-2">
                <!-- Username filled dynamically -->
                <div class="d-flex align-items-center  flex-wrap">
                    <h3 class="text-white mb-1 me-2" id="profile-fullname">
                        {{ Auth::user()->first_name && Auth::user()->last_name 
                        ? Auth::user()->first_name . ' ' . Auth::user()->last_name 
                        : Auth::user()->username }}
                    </h3>
                    <!-- <p class="text-white-50">Owner & Founder</p> -->
                </div>

                <!-- Location filled dynamically -->
                <div class="hstack text-white-50 gap-2">
                    <span id="profile-location">
                        <i class="ri-map-pin-user-line align-middle"></i>
                        {{ Auth::user()->city && Auth::user()->country ? Auth::user()->city . ', ' . Auth::user()->country : (Auth::user()->city ?? (Auth::user()->country ?? 'Nepoznata lokacija')) }}
                    </span>

                </div>

                <div class="hstack text-white-50 gap-1">
                    Član od:
                    <span id="joining-date">
                        @if(Auth::user()->joining_date)
                        {{ \Carbon\Carbon::parse(Auth::user()->joining_date)->format('d.m.Y') }}
                        @else
                        Nepoznat datum
                        @endif
                    </span>

                </div>
            </div>
        </div>

        <div class="col-12 col-lg-auto order-last order-lg-0">
            <div class="row text-center text-white-50">
                <div class="col-6">

                </div>
                <div class="col-6">

                </div>
            </div>
        </div>
    </div>
</div>


<!-- Main Content -->
<div class="row">
    <div class="col-lg-12">

        <!-- Navigation Tabs -->
        <ul class="nav nav-pills animation-nav profile-nav py-3 flex-grow-1 gap-2 " role="tablist">

            <li class="nav-item">
                <a class="nav-link  active" data-bs-toggle="tab" href="#overview-tab">Osnovni podaci</a>
            </li>


            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#projects">Moja pretplata</a>
            </li>

        </ul>

        <!-- Tab Content -->
        <div class="tab-content">






            <!-- Overview Tab -->
            <div class="tab-pane fade show active mb-0" id="overview-tab">
                <div class="row h-100 align-items-stretch">
                    <!-- Left Side Cards -->
                    <div class="col-xxl-4 d-flex flex-column rounded-0">
                        <div class="card mb-3 d-flex flex-column align-items-center justify-content-center rounded-0">
                            <div class="card-body d-flex align-items-center w-100 justify-content-between" style="min-height: 60px;">
                                <!-- Loader (shown initially) -->
                                <div class="w-100 d-flex justify-content-center" id="user-package-loader">
                                    <div class="spinner-border text-info" role="status"></div>
                                </div>
                                <!-- Package text (hidden initially) -->
                                <p class="fw-semibold mb-0 d-none d-flex" id="user-package-text" style="justify-content: space-between;"></p>

                                <!-- Upgrade button (hidden initially) -->
                                <a href="cijene-paketa" id="upgrade-btn" class="btn btn-info text-white btn-sm d-none fs-6">
                                    <i class="ri-arrow-up-circle-line"></i> Nadogradi paket
                                </a>
                            </div>

                        </div>
                       <!-- MOJE DEKLARACIJE -->
<div class="card rounded-0 w-100 mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Moje deklaracije</h5>
        <a href="moje-deklaracije" class="text-info text-end fs-13">Pregledaj sve</a>
    </div>
    <div class="card-body d-flex justify-content-start align-items-center flex-column pb-0 pt-0 position-relative" style="min-height: 200px;">
        <!-- Centered loader -->
        <div class="position-absolute top-50 start-50 translate-middle">
            <div class="user-documents-loader spinner-border text-info" role="status"></div>
        </div>

        <!-- Content hidden initially -->
        <div class="row g-3 mt-3 user-documents d-none w-100 text-center" id="user-documents">
            <div class="col-12 text-muted"></div>
        </div>
    </div>
</div>

<!-- MOJI KLIJENTI -->
<div class="card rounded-0 w-100 mb-0">
     <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Moji klijenti</h5>
        <a href="moji-klijenti" class="text-info text-end fs-13">Pregledaj sve</a>
    </div>
    <div class="card-body d-flex justify-content-start align-items-center flex-column pb-0 pt-0 position-relative" style="min-height: 250px;">
        <!-- Centered loader -->
        <div class="position-absolute top-50 start-50 translate-middle">
            <div class="suppliers-loader spinner-border text-info" role="status"></div>
        </div>

        <!-- Content hidden initially -->
        <div class="suppliers-list d-none w-100 mt-3"></div>
    </div>
</div>

                        



                    </div>

                    <!-- Right Form with Tabs -->
                    <div class="col-xxl-8 d-flex flex-column">
                        <div class="card d-flex flex-column rounded-0">
                            <!-- Nav Tabs -->
                            <div class="card-header" style="margin-bottom: 0;">
                                <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active text-info" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                            <i class="fas fa-user"></i>
                                            Osobni podaci
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link text-info" data-bs-toggle="tab" href="#companyDetails" role="tab">
                                            <i class="fa-solid fa-building"></i>
                                            Podaci o kompaniji
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-info" data-bs-toggle="tab" href="#changePassword" role="tab">
                                            <i class="fas fa-key"></i>
                                            Promjena lozinke
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <!-- Tab Content -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <!-- Personal Details Tab -->
                                    <div class="tab-pane fade show active" id="personalDetails" role="tabpanel">
                                        <form action="javascript:void(0);">
                                            <div class="row">
                                                <div class="col-lg-6 mb-3">
                                                    <label for="firstnameInput" class="form-label text-info">Ime</label>
                                                    <input type="text" class="form-control rounded-0" id="firstnameInput" placeholder="Ime" />
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label for="lastnameInput" class="form-label text-info">Prezime</label>
                                                    <input type="text" class="form-control rounded-0" id="lastnameInput" placeholder="Prezime" />
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label for="phonenumberInput" class="form-label text-info">Broj mobitela</label>
                                                    <input type="text" class="form-control rounded-0" id="phonenumberInput" placeholder="Broj telefona" />
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label for="emailInput" class="form-label text-info">Email adresa</label>
                                                    <input type="email" class="form-control rounded-0" id="emailInput" placeholder="Email" />
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label for="designationInput" class="form-label text-info">Pozicija</label>
                                                    <input type="text" class="form-control rounded-0" id="designationInput" placeholder="Pozicija" />
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label for="websiteInput1" class="form-label text-info">Web stranica</label>
                                                    <input type="text" class="form-control rounded-0" id="websiteInput1" placeholder="Web stranica" />
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label for="zipcodeInput" class="form-label text-info">Poštanski broj</label>
                                                    <input type="text" class="form-control rounded-0" id="zipcodeInput" placeholder="Poštanski broj" />
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label for="cityInput" class="form-label text-info">Grad</label>
                                                    <input type="text" class="form-control rounded-0" id="cityInput" placeholder="Grad" />
                                                </div>
                                                <div class="col-lg-12 mb-3">
                                                    <label for="countryInput" class="form-label text-info">Država</label>
                                                    <input type="text" class="form-control rounded-0" id="countryInput" placeholder="Država" />
                                                </div>

                                                <div class="col-lg-12 mb-3">
                                                    <label for="exampleFormControlTextarea" class="form-label text-info">Opis</label>
                                                    <textarea class="form-control rounded-0" id="exampleFormControlTextarea" placeholder="Opis" rows="3"></textarea>
                                                </div>
                                                <div class="col-lg-12 mt-4 mb-2">
                                                    <div class="hstack gap-2 justify-content-end">
                                                        <button type="submit" id="update-user-btn" class="btn btn-info"><i class="fas fa-save fs-6 me-1"></i> Ažuriraj podatke</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="companyDetails" role="tabpanel">
                                        <form action="javascript:void(0);">
                                            <div class="row">
                                                <div class="col-lg-6 mb-3">
                                                    <label for="companyNameInput" class="form-label text-info">Ime kompanije</label>
                                                    <input type="text" class="form-control rounded-0" id="companyNameInput" placeholder="Naziv kompanije" />
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label for="addressInput" class="form-label text-info">Adresa</label>
                                                    <input type="text" class="form-control rounded-0" id="addressInput" placeholder="Adresa" />
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label for="documentIdInput" class="form-label text-info">ID broj</label>
                                                    <input type="text" class="form-control rounded-0" id="documentIdInput" placeholder="ID broj" />
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label for="vatInput" class="form-label text-info">PDV broj</label>
                                                    <input type="text" class="form-control rounded-0" id="vatInput" placeholder="PDV broj" />
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label for="ownerInput" class="form-label text-info">Vlasnik</label>
                                                    <input type="text" class="form-control rounded-0" id="ownerInput" placeholder="Vlasnik" />
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label for="contactPersonInput" class="form-label text-info">Kontakt osoba</label>
                                                    <input type="text" class="form-control rounded-0" id="contactPersonInput" placeholder="Kontakt osoba" />
                                                </div>

                                                <div class="col-lg-12 mb-3">
                                                    <label for="contactNumberInput" class="form-label text-info">Kontakt broj</label>
                                                    <input type="text" class="form-control rounded-0" id="contactNumberInput" placeholder="Kontakt broj" />
                                                </div>

                                                <div class="col-lg-12 mt-4 mb-2">
                                                    <div class="hstack gap-2 justify-content-end">
                                                        <button type="submit" id="update-company-btn" class="btn btn-info">
                                                            <i class="fas fa-save fs-6 me-1"></i> Ažuriraj podatke
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- Change Password Tab -->
                                    <div class="tab-pane fade" id="changePassword" role="tabpanel">
                                        <form action="javascript:void(0);">
                                            <div class="row">
                                                <div class="col-lg-12 mb-3">
                                                    <label for="oldPassword" class="form-label text-info">Stara lozinka</label>
                                                    <input type="password" class="form-control rounded-0" id="oldPassword" placeholder="Unesite staru lozinku" />
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label for="newPassword" class="form-label text-info">Nova lozinka</label>
                                                    <input type="password" class="form-control rounded-0" id="newPassword" placeholder="Unesite novu lozinku" />
                                                </div>
                                                <div class="col-lg-6 mb-3">
                                                    <label for="confirmPassword" class="form-label text-info">Potvrdite novu lozinku</label>
                                                    <input type="password" class="form-control rounded-0" id="confirmPassword" placeholder="Potvrdite novu lozinku" />
                                                </div>
                                                <div class="col-lg-12 mt-4 mb-2">
                                                    <div class="hstack gap-2 justify-content-end">
                                                        <button type="submit" id="change-password-btn" class="btn btn-info"><i class="fas fa-save fs-6 me-1"></i> Promijeni lozinku</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>





            <!-- Moje fakture tab (activities) -->


            <!-- Modal -->
            <div class="modal fade" id="invoiceDetailsModal" tabindex="-1" aria-labelledby="invoiceDetailsModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Pregled deklaracije</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Zatvori"></button>
                        </div>
                        <div class="modal-body p-0">
                            <div class="row justify-content-center">
                                <div class="col-xxl-9">
                                    <div class="card" id="demo">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div
                                                    class="card-header border-bottom-dashed p-4 d-flex justify-content-between">
                                                    <div>
                                                        <img src="{{ URL::asset('build/images/logo.svg') }}"
                                                            class="card-logo" alt="logo" height="30">
                                                        <div class="mt-4">
                                                            <h6 class="text-muted text-uppercase fw-semibold">Adresa
                                                            </h6>
                                                            <p class="text-muted mb-1" id="address-details">--</p>
                                                            <p class="text-muted mb-0" id="zip-code"><span>Poštanski
                                                                    broj:</span> --</p>
                                                        </div>
                                                    </div>
                                                    <div class="text-end">

                                                        <h6><span class="text-muted fw-normal">Email:</span> <span
                                                                id="email">--</span></h6>
                                                        <h6><span class="text-muted fw-normal">Web:</span> <a href="#"
                                                                class="link-primary" target="_blank" id="website">--</a>
                                                        </h6>
                                                        <h6 class="mb-0"><span
                                                                class="text-muted fw-normal">Telefon:</span> <span
                                                                id="contact-no">--</span></h6>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="card-body p-4">
                                                    <div class="row g-3">
                                                        <div class="col-lg-3 col-6">
                                                            <p class="text-muted mb-2 text-uppercase fw-semibold">
                                                                Faktura #</p>
                                                            <h5 class="fs-14 mb-0">#<span id="invoice-no">--</span></h5>
                                                        </div>
                                                        <div class="col-lg-3 col-6">
                                                            <p class="text-muted mb-2 text-uppercase fw-semibold">Datum
                                                            </p>
                                                            <h5 class="fs-14 mb-0"><span id="invoice-date">--</span>
                                                            </h5>
                                                        </div>
                                                        <div class="col-lg-3 col-6">
                                                            <p class="text-muted mb-2 text-uppercase fw-semibold">
                                                                Skenirana</p>
                                                            <span class="badge bg-light text-dark fs-11"
                                                                id="payment-status">--</span>
                                                        </div>
                                                        <div class="col-lg-3 col-6">
                                                            <p class="text-muted mb-2 text-uppercase fw-semibold">Ukupan
                                                                iznos</p>
                                                            <h5 class="fs-14 mb-0"><span id="total-amount">--</span>
                                                            </h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="card-body p-4 border-top border-top-dashed">
                                                    <div class="row g-3">
                                                        <div class="col-6">
                                                            <h6 class="text-muted text-uppercase fw-semibold mb-3">
                                                                Dobavljač</h6>
                                                            <p class="fw-medium mb-2" id="billing-name">--</p>
                                                            <p class="text-muted mb-1" id="billing-address-line-1">--
                                                            </p>
                                                            <p class="text-muted mb-1"><span>Telefon: </span><span
                                                                    id="billing-phone-no">--</span></p>
                                                            <p class="text-muted mb-0"><span>PIB: </span><span
                                                                    id="billing-tax-no">--</span></p>
                                                        </div>
                                                        <div class="col-6">
                                                            <h6 class="text-muted text-uppercase fw-semibold mb-3">
                                                                Zemlja porijekla</h6>
                                                            <p class="fw-medium mb-2" id="shipping-country">--</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Invoice Items -->
                                            <div class="col-lg-12">
                                                <div class="card-body p-4">
                                                    <div class="table-responsive">
                                                        <table
                                                            class="table table-borderless text-center table-nowrap align-middle mb-0">
                                                            <thead>
                                                                <tr class="table-active">
                                                                    <th>#</th>
                                                                    <th>Artikal</th>
                                                                    <th>Opis</th>
                                                                    <th>Cijena</th>
                                                                    <th>Količina</th>
                                                                    <th>Ukupno</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="products-list">
                                                                <!-- Dynamic rows will be inserted here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Totals -->
                                            <div class="col-lg-12">
                                                <div class="card-body pt-0">
                                                    <div class="border-top border-top-dashed mt-2">
                                                        <table
                                                            class="table table-borderless table-nowrap align-middle mb-0 ms-auto"
                                                            style="width:250px">
                                                            <tbody>
                                                                <tr class="border-top border-top-dashed fs-15">
                                                                    <th scope="row">Ukupno</th>
                                                                    <th class="text-end"><span id="modal-total-amount">
                                                                        </span> EUR </th>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="mt-4">
                                                        <h6 class="text-muted text-uppercase fw-semibold mb-3">Detalji
                                                            plaćanja:</h6>
                                                        <p class="text-muted mb-1">Način plaćanja: <span
                                                                class="fw-medium">Kartica</span></p>
                                                        <p class="text-muted mb-1">Ime vlasnika kartice: <span
                                                                class="fw-medium">Tin Tomić</span></p>
                                                        <p class="text-muted mb-1">Broj kartice: <span
                                                                class="fw-medium">xxxx xxxx xxxx 1234</span></p>
                                                        <p class="text-muted">Ukupno za platiti: <span
                                                                class="fw-medium"><span
                                                                    id="payment-method-amount">755.96</span> KM</span>
                                                        </p>
                                                    </div>

                                                    <div class="mt-4">
                                                        <div class="alert alert-info">
                                                            <p class="mb-0"><span class="fw-semibold">Napomena:</span>
                                                                <span id="note">Račun je informativnog karaktera.
                                                                    Provjerite detalje prije plaćanja.</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                                                        <a href="javascript:window.print()" class="btn btn-success">
                                                            <i class="ri-printer-line align-bottom me-1"></i> Print
                                                        </a>
                                                        <a href="javascript:void(0);" class="btn btn-primary">
                                                            <i class="ri-download-2-line align-bottom me-1"></i>
                                                            Download
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div> <!-- row -->
                                    </div> <!-- card -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Paketi(Pricing) -->
            <div class="tab-pane fade mt-0" id="projects">
                <div class="container">
                    <div class="row justify-content-center">
                        <!-- StartUp Plan -->
                        <div class="col-lg-4 mb-4">
                            <div class="card pricing-box border-0 rounded-0 h-100">
                                <button type="button" id="btn-StartUp" class=" d-none btn btn-soft-info btn-sm  rounded-0 shadow-none w-100" style="position: absolute; border-bottom-left-radius: 0; border-bottom-right-radius:0">
                                    Aktivan do: <span class="date-valid"></span>. godine
                                </button>
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
                                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>100</b> AI Skeniranih Deklaracije</li>
                                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>50 strana</b> po Deklaraciji</li>
                                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>200</b> Deklaracija u historiji</li>
                                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i>Prosječna brzina skeniranja: <b>20 s</b></li>
                                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i>30 dana</li>
                                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>24/7</b> Support</li>
                                    </ul>

                                    <a id="btnAction-StartUp" data-bs-toggle="modal" data-bs-target="#paymentChoiceModal" href="javascript:void(0);" class="btn btn-info w-100 mt-auto text-white">Započni</a>
                                </div>
                            </div>
                        </div>

                        <!-- GoBig Plan -->
                        <div class="col-lg-4 mb-4">
                            <div class="card pricing-box border-0 rounded-0 ribbon-box right h-100">

                                <button type="button" id="btn-GoBig" class=" d-none btn btn-soft-info btn-sm shadow-none w-100 rounded-0" style="position: absolute; border-bottom-left-radius: 0; border-bottom-right-radius:0">
                                    Aktivan do: <span class="date-valid"></span>. godine
                                </button>
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
                                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>200</b> AI Skeniranih Deklaracije</li>
                                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>150 strana</b> po Deklaraciji</li>
                                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>500</b> Deklaracija u historiji</li>
                                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i>Prosječna brzina skeniranja: <b>10 s</b></li>
                                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i>120 dana</li>
                                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>24/7</b> Support</li>
                                    </ul>
                                    <a id="btnAction-GoBig" href="javascript:void(0);" class="btn btn-info w-100 mt-auto text-white" data-bs-toggle="modal" data-bs-target="#paymentChoiceModal">Započni</a>
                                </div>
                            </div>
                        </div>

                        <!-- Business Plan -->
                        <div class="col-lg-4 mb-4">
                            <div class="card pricing-box border-0 rounded-0 h-100">
                                <button type="button" id="btn-Business" class=" d-none btn btn-soft-info btn-sm shadow-none w-100 rounded-0" style="position: absolute; border-bottom-left-radius: 0; border-bottom-right-radius:0">
                                    Aktivan do: <span class="date-valid"></span>.godine
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
                                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>500</b> AI Skeniranih Deklaracije</li>
                                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>Neograničeno strana </b> po Deklaraciji</li>
                                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>Neograničeno</b> Deklaracija u historiji</li>
                                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i>Prosječna brzina skeniranja: <b>4 s</b></li>
                                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i>365 dana</li>
                                        <li><i class="ri-checkbox-circle-fill text-info me-2"></i><b>24/7</b> Support</li>
                                    </ul>

                                    <a id="btnAction-Business" href="javascript:void(0);" class="btn btn-info w-100 mt-auto text-white" data-bs-toggle="modal" data-bs-target="#paymentChoiceModal">Započni</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
<!--end row-->

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










</div>


</div>
</div>





@endsection


@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/profile.init.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!--  DataTables Core -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


<!--  DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>




<!-- jQuery (MUST be before DataTables) -->











<!-- Card payment script test -->
<script>
    const cardNumberInput = document.getElementById("cardNumber");
    const cardLogo = document.getElementById("cardLogo");

    cardNumberInput.addEventListener("input", function(e) {
        // Remove all non-digit characters
        let raw = e.target.value.replace(/\D/g, "");

        // Limit to 16 digits max
        if (raw.length > 16) {
            raw = raw.slice(0, 16);
        }

        // Format as 4-digit groups
        let formatted = raw.match(/.{1,4}/g);
        e.target.value = formatted ? formatted.join(" ") : "";

        // Card type detection
        if (raw.startsWith("4")) {
            // Visa
            cardLogo.src = "https://img.icons8.com/color/32/000000/visa.png";
            cardLogo.alt = "Visa";
        } else if (/^5[1-5]/.test(raw)) {
            // Mastercard
            cardLogo.src = "https://img.icons8.com/color/32/000000/mastercard-logo.png";
            cardLogo.alt = "Mastercard";
        } else {
            // Default icon
            cardLogo.src = "https://img.icons8.com/color/32/000000/bank-card-back-side.png";
            cardLogo.alt = "Card";
        }
    });
</script>


<!-- User Avatar photo upload-->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const csrftoken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const avatarBasePath = "/storage/uploads/avatars/";

        const avatarImg = document.getElementById("user-avatar");
        const avatarFallback = document.getElementById("avatar-fallback");

        if (!token || !user) {
            alert("Niste prijavljeni.");
            return;
        }

        // 1. Fetch user and update UI
        fetch(`/api/users/${user.id}`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        })
        .then(res => res.json())
        .then(data => {
            const userData = data.user;
            const avatar = userData.avatar;

            // Use first letter of first_name, else username, else email
            let initial = "U";
            if (userData.username?.length > 0) {
                initial = userData.username[0].toUpperCase();
            }
            avatarFallback.textContent = initial;
            avatarFallback.classList.remove("d-none");
            avatarImg.classList.add("d-none");

            // Then attempt to load avatar
            if (avatar) {
                const testImage = new Image();
                testImage.onload = function () {
                    avatarImg.src = avatarBasePath + avatar;
                    avatarImg.classList.remove("d-none");
                    avatarFallback.classList.add("d-none");
                };
                testImage.onerror = function () {
                    // Avatar failed to load, keep initials visible
                };
                testImage.src = avatarBasePath + avatar;
            }
        });

        // 2. Avatar overlay → trigger file input
        document.querySelector(".avatar-overlay").addEventListener("click", function () {
            document.getElementById("avatar-input").click();
        });

        // 3. On avatar file select → upload → update user
        document.getElementById("avatar-input").addEventListener("change", function () {
            const file = this.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append("file", file);
            formData.append("folder", "avatars");

            fetch(`/api/storage/uploads`, {
                method: "POST",
                headers: {
                    Authorization: `Bearer ${token}`,
                    'X-CSRF-TOKEN': csrftoken
                },
                body: formData
            })
            .then(async res => {
                const text = await res.text();
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error("Invalid JSON: " + text);
                }
            })
            .then(upload => {
                const avatarFileName = upload.stored_as;
                if (!avatarFileName) throw new Error("No avatar returned");

                return fetch(`/api/users/${user.id}`, {
                    method: "PUT",
                    headers: {
                        Authorization: `Bearer ${token}`,
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrftoken
                    },
                    body: JSON.stringify({
                        avatar: avatarFileName
                    })
                });
            })
            .then(res => res.json())
            .then(update => {
                const updatedAvatar = update.user.avatar;
                const imgPath = `${avatarBasePath}${updatedAvatar}?t=${Date.now()}`;

                avatarFallback.classList.remove("d-none");
                avatarImg.classList.add("d-none");

                const img = new Image();
                img.onload = function () {
                    avatarImg.src = imgPath;
                    avatarImg.classList.remove("d-none");
                    avatarFallback.classList.add("d-none");
                };
                img.onerror = function () {
                    // Image failed to load
                };
                img.src = imgPath;
            })
            .catch(err => {
                console.error("Error uploading avatar:", err);
            });
        });
    });
</script>


<!-- View invoice logic -->







<!-- Fetch UserProfile and Business data -->

<script>
    document.addEventListener("DOMContentLoaded", async function() {


        if (!user || !token) {
            console.warn("User or token not found in localStorage");
            return;
        }

        try {
            const response = await fetch(`/api/users/${user.id}`, {
                headers: {
                    "Authorization": `Bearer ${token}`
                }
            });

            if (!response.ok) {
                console.error("Failed to fetch user data");
                return;
            }

            const data = await response.json();
            const u = data.user;
            const company = u?.company;

            window.userData = u;
            window.companyDataFilled = false;

            const updateInput = (id, value, fallbackPlaceholder) => {
                const el = document.getElementById(id);
                if (!el) return;
                if (value && value.trim()) {
                    el.value = value;
                } else {
                    el.value = "";
                    el.placeholder = fallbackPlaceholder;
                }
            };

            // === Fill PERSONAL DETAILS immediately ===
            updateInput("firstnameInput", u.first_name, "Unesite ime");
            updateInput("lastnameInput", u.last_name, "Unesite prezime");
            updateInput("phonenumberInput", u.phone_number, "Unesite broj mobitela");
            updateInput("emailInput", u.email, "Unesite email adresu");
            updateInput("JoiningdatInput", u.joining_date, "Unesite datum pridruženja");
            updateInput("designationInput", u.designation, "Unesite poziciju");
            updateInput("websiteInput1", u.website, "Unesite web stranicu");
            updateInput("cityInput", u.city, "Unesite grad");
            updateInput("countryInput", u.country, "Unesite državu");
            updateInput("zipcodeInput", u.zip_code, "Unesite poštanski broj");

            const desc = document.getElementById("exampleFormControlTextarea");
            if (desc) {
                if (u.description && u.description.trim()) {
                    desc.value = u.description;
                } else {
                    desc.value = "";
                    desc.placeholder = "Unesite opis";
                }
            }

            // === Fill COMPANY DETAILS when tab is shown ===
            const tabLink = document.querySelector('a[href="#companyDetails"]');
            if (tabLink) {
                tabLink.addEventListener("shown.bs.tab", function() {
                    if (window.companyDataFilled) return;

                    const c = window.userData?.company || {};

                    updateInput("companyNameInput", c.name, "Unesite ime kompanije");
                    updateInput("addressInput", c.address, "Unesite adresu");
                    updateInput("documentIdInput", c.document_id || c.id, "Unesite ID broj");
                    updateInput("vatInput", c.pdv, "Unesite PDV broj");
                    updateInput("ownerInput", c.owner, "Unesite vlasnika");
                    updateInput("contactPersonInput", c.contact_person, "Unesite kontakt osobu");
                    updateInput("contactNumberInput", c.contact_number, "Unesite kontakt broj");

                    window.companyDataFilled = true;
                });
            }

        } catch (err) {
            console.error("Error fetching user/company data:", err);
        }
    });
</script>






<!-- Update User data -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get CSRF token from meta tag
        const csrftoken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if (!user || !token) {
            console.error("User or token not found in localStorage.");
            return;
        }

        const showSuccess = (text) => {
            Swal.fire({
                icon: "success",
                title: "Uspješno!",
                text,
                confirmButtonText: "U redu",
                customClass: {
                    confirmButton: 'btn btn-info w-xs mt-2',
                },
            });
        };

        const showError = (err) => {
            Swal.fire("Greška", err.message || "Nešto je pošlo po zlu.", "error");
        };

        const getPayload = () => ({
            first_name: document.getElementById("firstnameInput").value.trim(),
            last_name: document.getElementById("lastnameInput").value.trim(),
            phone_number: document.getElementById("phonenumberInput").value.trim(),
            email: document.getElementById("emailInput").value.trim(),
            joining_date: document.getElementById("JoiningdatInput")?.value.trim() || null,
            designation: document.getElementById("designationInput").value.trim(),
            website: document.getElementById("websiteInput1").value.trim(),
            city: document.getElementById("cityInput").value.trim(),
            country: document.getElementById("countryInput").value.trim(),
            zip_code: document.getElementById("zipcodeInput").value.trim(),
            description: document.getElementById("exampleFormControlTextarea").value.trim(),
            company: {
                name: document.getElementById("companyNameInput").value.trim(),
                address: document.getElementById("addressInput").value.trim(),
                id: document.getElementById("documentIdInput").value.trim(),
                pdv: document.getElementById("vatInput").value.trim(),
                owner: document.getElementById("ownerInput").value.trim(),
                contact_person: document.getElementById("contactPersonInput").value.trim(),
                contact_number: document.getElementById("contactNumberInput").value.trim()
            }
        });

        const handleSubmit = async () => {
            try {
                const response = await fetch(`/api/users/${user.id}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",

                        'X-CSRF-TOKEN': csrftoken
                    },
                    body: JSON.stringify(getPayload())
                });

                if (!response.ok) throw new Error(await response.text());

                const {
                    user: updatedUser
                } = await response.json();

                // Update UI

                document.getElementById("profile-location").innerHTML =
                    `<i class="ri-map-pin-user-line align-middle"></i> ${updatedUser.city || 'Nepoznat grad'}, ${updatedUser.country || 'Nepoznata država'}`;

                // Save to localStorage
                localStorage.setItem("user", JSON.stringify(updatedUser));

                // Update the name in the DOM
                const nameEl = document.getElementById("profile-fullname");
                if (nameEl) {
                    if (updatedUser.first_name && updatedUser.last_name) {
                        nameEl.textContent = updatedUser.first_name + " " + updatedUser.last_name;
                    } else {
                        nameEl.textContent = updatedUser.username;
                    }
                }

                showSuccess("Podaci su uspješno ažurirani.");
            } catch (err) {
                console.error("Greška:", err);
                showError(err);
            }
        };

        document.getElementById("update-user-btn").addEventListener("click", function(e) {
            e.preventDefault();
            handleSubmit();
        });
        document.getElementById("update-company-btn").addEventListener("click", function(e) {
            e.preventDefault();
            handleSubmit();
        });
    });
</script>



<!-- Moji dobavljači dynamic fetch -->
<script>
document.addEventListener("DOMContentLoaded", async function () {
    if (!token || !user?.id) {
        console.warn("Missing auth or user.");
        return;
    }

    const supplierContainer = document.querySelector(".suppliers-list");
    const supplierLoader = document.querySelector(".suppliers-loader");

    try {
        const res = await axios.get(`/api/statistics/users/${user.id}`, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });

        const stats = res.data || {};
        console.log("Stats response:", stats);

        // --- Suppliers Section ---
        const suppliers = stats.supplier_stats?.latest_suppliers || [];
        const lastSuppliers = suppliers.slice(-5);

        if (supplierLoader) supplierLoader.classList.add("d-none");
        if (supplierContainer) {
            supplierContainer.classList.remove("d-none");
            supplierContainer.innerHTML = "";

            if (lastSuppliers.length === 0) {
                supplierContainer.innerHTML = `
                    <div class="text-muted text-center position-absolute translate-middle top-50 start-50">Nema podataka o dobavljačima</div>
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
                                <div class="fw-semibold">${supplier.name}</div>
                                <div class="text-muted fs-12">${supplier.owner ?? "Nepoznat vlasnik"}</div>
                            </div>
                            <div class="text-info fs-12">
                                ${!supplier.address ? "Nije definisano" : supplier.address}
                            </div>
                        </div>
                    `;
                });

               
            }
        }
    } catch (err) {
        console.error("Greška pri dohvaćanju statistike:", err);

        if (supplierLoader) supplierLoader.classList.add("d-none");

        if (supplierContainer) {
            supplierContainer.classList.remove("d-none");
            supplierContainer.innerHTML = `<div class="text-danger">Greška pri dohvaćanju dobavljača.</div>`;
        }
    }
});
</script>

<!-- Invoice fetch dynamic -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const docsContainer = document.getElementById("user-documents");
        const loader = document.querySelector(".user-documents-loader");

        if (!token || !user || !docsContainer || !loader) return;

        fetch(`/api/invoices/users/${user.id}`, {
                headers: {
                    Authorization: `Bearer ${token}`
                }
            })
            .then(res => res.json())
            .then(data => {
                const invoices = Array.isArray(data) ? data : [];

                const lastFour = invoices
                    .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
                    .slice(0, 8);

                loader.classList.add("d-none");
                docsContainer.classList.remove("d-none");

                if (lastFour.length === 0) {
                    docsContainer.innerHTML = `
                    <div class="text-muted text-center position-absolute translate-middle top-50 start-50">Nema dostupnih dokumenata</div>
                `;
                    return;
                }

                const iconMap = {
                    pdf: "ri-file-pdf-2-line",
                    xls: "ri-file-excel-2-line",
                    xlsx: "ri-file-excel-2-line",
                    jpg: "ri-file-image-line",
                    jpeg: "ri-file-image-line",
                    png: "ri-file-image-line",
                    txt: "ri-file-text-line",
                    doc: "ri-file-word-2-line",
                    docx: "ri-file-word-2-line",
                    default: "ri-file-line"
                };

                docsContainer.innerHTML = lastFour.map(inv => {
                    const ext = inv.file_name?.split(".").pop()?.toLowerCase() || '';
                    const icon = iconMap[ext] || iconMap.default;
                    let file = inv.file_name || '';
                    if (file.length > 10) {
                        file = file.substring(0, 10) + '..';
                    }

                    return `
                    <div class="col-6 col-sm-3 text-center">
                        <a href="#" class="text-decoration-none view-invoice" data-id="${inv.id}" title="Pregled Deklaracije">
                            <i class="${icon} fs-24 text-info"></i>
                            <p class="fs-13 text-muted mt-1 mb-0" style="white-space:nowrap!important">${file}</p>
                        </a>
                    </div>
                `;
                }).join("");
            })
            .catch(err => {
                console.error("Failed to fetch user invoices:", err);
                loader.classList.add("d-none");
                docsContainer.classList.remove("d-none");
                docsContainer.innerHTML = `
                <div class="col-12 text-center text-danger">Greška pri učitavanju dokumenata.</div>
            `;
            });
    });
</script>





<script>
    document.addEventListener("DOMContentLoaded", function() {
        const hash = window.location.hash;
        if (hash) {
            const tabLink = document.querySelector(`a[href="${hash}"]`);
            if (tabLink) {
                const tab = new bootstrap.Tab(tabLink);
                tab.show();
            }
        }
    });
</script>



<!-- Dynamical Package update -->
<script>
    document.addEventListener("DOMContentLoaded", async function() {


        const loader = document.getElementById("user-package-loader");
        const packageText = document.getElementById("user-package-text");
        const upgradeBtn = document.getElementById("upgrade-btn");

        // Declare packageName here so it is visible after try/catch
        let packageName = null;

        try {
            const res = await axios.get("/api/user-packages", {
                headers: {
                    Authorization: `Bearer ${token}`,
                }
            });

            const userPackages = res.data?.data || [];


            if (userPackages.length > 0) {
                const userPackage = userPackages.find(p => p.active) || userPackages[0];
                packageName = userPackage?.package?.name || null;
                packageValid = userPackage?.expiration_date || null;


                // Format packageValid date from yyyy-mm-dd to dd.mm.yyyy
                let formattedDate = packageValid;
                if (packageValid) {
                    const parts = packageValid.split("-");
                    formattedDate = `${parts[2]}.${parts[1]}.${parts[0]}`;
                }


                if (packageText && packageName) {
                    let iconHTML = "";

                    switch (packageName.toLowerCase()) {
                        case "gobig":
                            iconHTML = `<i class="ri-medal-line text-info fs-3 me-2 position-absolute" style="margin-top:-6px!important;margin-left: 45px;"></i>`;
                            break;
                        case "startup":
                            iconHTML = `<i class="ri-star-s-fill text-info fs-3 me-2 position-absolute" style="margin-top:-2px!important;margin-left: 60px;"></i>`;
                            break;
                        case "business":
                            iconHTML = `<i class="ri-shield-star-line text-info fs-3 me-2 position-absolute" style="margin-top:-8px!important;margin-left: 68px;"></i>`;
                            break;
                        default:
                            iconHTML = "";
                    }

                    packageText.innerHTML = `
            <span class="d-inline-flex align-items-center gap-1 fs-15">
                <h5 class="card-title mb-0">Tvoj aktivni paket je</h5>
                <b class="d-inline-flex gap-1 text-info mb-0 me-4" style="margin-bottom:-1px!important">
                    ${packageName}
                    ${iconHTML}
                </b>
            </span>`;

                    // Replace all .date-valid elements with the formatted date
                    document.querySelectorAll('.date-valid').forEach(el => {
                        el.textContent = formattedDate;
                    });
                }
            } else {
                packageText.textContent = "Nema aktivnog paketa.";
            }



        } catch (err) {
            console.error("Greška pri dohvaćanju paketa korisnika:", err);
            packageText.textContent = "Greška pri učitavanju paketa.";
        }

        // Hide loader, show text and button
        loader.classList.add("d-none");
        packageText.classList.remove("d-none");
        upgradeBtn.classList.remove("d-none");

        // Normalize packageName to lowercase for comparison
        const normalizedPackage = packageName?.toLowerCase();

        // Show the "Aktivan do" button for the active package only
        normalizedPackage === 'gobig' && document.getElementById('btn-GoBig')?.classList.remove('d-none');
        normalizedPackage === 'startup' && document.getElementById('btn-StartUp')?.classList.remove('d-none');
        normalizedPackage === 'business' && document.getElementById('btn-Business')?.classList.remove('d-none');

        // Update action buttons text individually without loop
        const btnActionGoBig = document.getElementById('btnAction-GoBig');
        const btnActionStartUp = document.getElementById('btnAction-StartUp');
        const btnActionBusiness = document.getElementById('btnAction-Business');

        if (btnActionGoBig) {
            btnActionGoBig.textContent = (normalizedPackage === 'gobig') ? 'Produži' : 'Započni';
        }
        if (btnActionStartUp) {
            btnActionStartUp.textContent = (normalizedPackage === 'startup') ? 'Produži' : 'Započni';
        }
        if (btnActionBusiness) {
            btnActionBusiness.textContent = (normalizedPackage === 'business') ? 'Produži' : 'Započni';
        }

        // Debug logs (optional)
        console.log('Active package:', packageName);
        console.log('btn-GoBig element:', document.getElementById('btn-GoBig'));
    });
</script>



<!-- Profile bg-img static upload logic temporary -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const backgroundImage = document.getElementById("profile-background");
        const input = document.getElementById("background-img-input");
        const csrftoken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const backgroundUploadPath = "/storage/uploads/profile_backgrounds/";
        const defaultImagePath = "/images/profile-bg.jpg";

        if (!token || !user) {
            console.warn("User not authenticated.");
            return;
        }

        // Load background from localStorage or fallback
        const savedBackground = localStorage.getItem(`user-bg-${user.id}`);
        if (savedBackground) {
            backgroundImage.src = `${backgroundUploadPath}${savedBackground}?t=${Date.now()}`;
        } else {
            backgroundImage.src = defaultImagePath;
        }

        // Upload logic
        input.addEventListener("change", function() {
            const file = this.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append("file", file);
            formData.append("folder", "profile_backgrounds");

            fetch(`/api/storage/uploads`, {
                    method: "POST",
                    headers: {
                        Authorization: `Bearer ${token}`,
                        'X-CSRF-TOKEN': csrftoken
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(upload => {
                    const filename = upload.stored_as;
                    if (!filename) throw new Error("No file name returned.");

                    // Save + display
                    localStorage.setItem(`user-bg-${user.id}`, filename);
                    backgroundImage.src = `${backgroundUploadPath}${filename}?t=${Date.now()}`;
                })
                .catch(err => {
                    console.error("Upload failed:", err);
                });
        });
    });
</script>



<!-- view last 4 invoices modal logic -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Bind click event to all current and future .view-invoice elements
        $(document).on('click', '.view-invoice', function(e) {
            e.preventDefault();
            const invoiceId = $(this).data('id');
            if (invoiceId) openInvoiceModal(invoiceId);
        });

        function openInvoiceModal(invoiceId) {

            // Fetch invoice details
            fetch(`/api/invoices/${invoiceId}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(res => res.json())
                .then(invoice => {
                    // Fetch supplier details after invoice
                    return fetch(`/api/suppliers/${invoice.supplier_id}`, {
                            headers: {
                                'Authorization': `Bearer ${token}`
                            }
                        })
                        .then(res => res.json())
                        .then(supplier => ({
                            invoice,
                            supplier
                        }));
                })
                .then(({
                    invoice,
                    supplier
                }) => {
                    // Fill static invoice fields
                    $('#invoice-no').text(invoice.id);
                    $('#invoice-date').text(new Date(invoice.date_of_issue).toLocaleDateString('hr'));
                    $('#total-amount').text(`${parseFloat(invoice.total_price).toFixed(2)} KM`);
                    $('#modal-total-amount').text(`${parseFloat(invoice.total_price).toFixed(2)} KM`);
                    $('#payment-status').text(invoice.scanned ? 'Skenirana' : 'Nije skenirana');
                    $('#shipping-country').text(invoice.country_of_origin || '--');

                    // Supplier info
                    $('#billing-name').text(supplier.name || '--');
                    $('#billing-address-line-1').text(supplier.address || '--');
                    $('#billing-phone-no').text(supplier.contact_phone || '--');
                    $('#billing-tax-no').text(supplier.tax_id || '--');
                    $('#email').text(supplier.contact_email || '--');

                    // Invoice items
                    let itemsHTML = '';
                    let totalSum = 0;

                    (invoice.items || []).forEach((item, index) => {
                        const price = parseFloat(item.total_price || 0);
                        totalSum += price;

                        itemsHTML += `
                    <tr>
                        <th scope="row">${index + 1}</th>
                        <td class="text-start fw-medium">${item.item_description_original}</td>
                        <td class="text-muted text-wrap" style="white-space: normal; word-break: break-word; max-width: 500px;">${item.item_description}</td>
                        <td>${item.base_price} ${item.currency}</td>
                        <td>${item.quantity}</td>
                        <td class="text-end">${item.total_price} ${item.currency}</td>
                    </tr>`;
                    });

                    $('#products-list').html(itemsHTML);
                    $('#modal-total-amount').text(totalSum.toFixed(2));

                    // Reset any old backdrop and show modal
                    $('.modal-backdrop').remove();
                    $('body').removeClass('modal-open').css('padding-right', '');
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('invoiceDetailsModal')).show();
                })
                .catch(err => {
                    console.error('Greška pri učitavanju Deklaracije:', err);
                    alert('Greška pri učitavanju Deklaracije.');
                });
        }
    });
</script>