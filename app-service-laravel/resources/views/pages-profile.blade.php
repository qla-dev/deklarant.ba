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



    /* Wrapper for search input */
</style>
@endsection
@section('content')

<!-- Profile Foreground -->
<div class="profile-foreground position-relative mx-n4 mt-n4">
    <div class="profile-wid-bg">
        <img src="{{ URL::asset('build/images/profile-bg.jpg') }}" class="profile-wid-img" alt="Profile Background">
    </div>
</div>

<!-- Profile Header -->
<div class="pt-4 mb-4 profile-wrapper pb-lg-4">
    <div class="row g-4 align-items-center">
        <div class="col-auto">
            <div class="avatar-lg position-relative" id="avatar-wrapper">
                <!-- Avatar image -->
                <img id="user-avatar" src="/build/images/users/avatar-1.jpg" class="img-thumbnail rounded-circle"
                    alt="User Avatar">

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
                    <h3 class="text-white mb-1 me-2" id="profile-username">Učitavanje imena i prezimena...</h3>
                    <h3 class="text-white mb-1" id="profile-lastname"></h3>
                    <!-- <p class="text-white-50">Owner & Founder</p> -->
                </div>

                <!-- Location filled dynamically -->
                <div class="hstack text-white-50 gap-2">
                    <span id="profile-location"><i class="ri-map-pin-user-line align-middle"></i> Učitavanje
                        lokacije...</span>
                    <span><i class="ri-building-line align-middle"></i> Themesbrand</span>
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
                <a class="nav-link" data-bs-toggle="tab" href="#activities">Moje fakture</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#projects">Paketi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#documents">Test</a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">



            <div class="tab-pane fade" id="documents">
                <div class="table-responsive">
                    <select id="tariffSelect" class="form-control" style="width: 100%"></select>

                    <table id="tariffTable" class="table table-striped table-bordered w-100">
                        <thead class="table-light">
                            <tr>
                                <th>Tarifna oznaka</th>
                                <th>Naziv</th>
                                <th>Jedinica</th>
                                <th>Stopa (%)</th>
                                <th>EU</th>
                                <th>CEFTA</th>
                                <th>IRN</th>
                                <th>TUR</th>
                                <th>CHE, LIE</th>
                                <th>ISL</th>
                                <th>NOR</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>


            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview-tab">
                <div class="row">

                    <!-- Left Side Cards -->
                    <div class="col-xxl-3">
                        <!-- Current Plan Card -->
                        <div class="card">
                            <div class="card-body text-center">
                                <p class="fw-semibold">Vaš trenutni paket je <b>Starter</b></p>
                                <a href="pages-pricing" class="btn btn-info text-white btn-sm">
                                    <i class="ri-arrow-up-circle-line"></i> Nadogradite paket
                                </a>
                            </div>
                        </div>

                        <!-- Info Card -->

                        <!-- Suppliers Card -->
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Moji dobavljači</h5>
                                <div class="d-flex align-items-center py-2">
                                    <img src="{{ URL::asset('build/images/users/orbico.png') }}"
                                        class="avatar-xs rounded-circle me-3">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">ORBICO</h6>
                                        <small class="text-muted">Bosna i Hercegovina</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center py-2">
                                    <img src="{{ URL::asset('build/images/users/hifa.png') }}"
                                        class="avatar-xs rounded-circle me-3">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">HIFA</h6>
                                        <small class="text-muted">Bosna i Hercegovina</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center py-2">
                                    <img src="{{ URL::asset('build/images/users/samsung.png') }}"
                                        class="avatar-xs rounded-circle me-3">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">Samsung</h6>
                                        <small class="text-muted">Južna Koreja</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">Moji dokumenti</h5>
                                    <a data-bs-toggle="tab" href="#activities" id="viewAllDocuments"
                                        class="text-info fs-13">View all</a>
                                </div>

                                <div class="row g-3">
                                    <div class="col-6 col-sm-3">
                                        <div class="text-center">
                                            <i class="ri-file-pdf-2-line fs-24 text-info"></i>
                                            <p class="fs-13 text-muted mt-1 mb-0">Dokument.pdf</p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-3">
                                        <div class="text-center">
                                            <i class="ri-file-image-line fs-24 text-info"></i>
                                            <p class="fs-13 text-muted mt-1 mb-0">Slika.jpg</p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-3">
                                        <div class="text-center">
                                            <i class="ri-file-excel-2-line fs-24 text-info"></i>
                                            <p class="fs-13 text-muted mt-1 mb-0">Tabela.xlsx</p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-3">
                                        <div class="text-center">
                                            <i class="ri-file-image-line fs-24 text-info"></i>
                                            <p class="fs-13 text-muted mt-1 mb-0">Grafika.png</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                    <!-- Personal Details Form -->
                    <div class="col-xxl-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-pane" role="tabpanel">
                                    <form action="javascript:void(0);">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="firstnameInput" class="form-label text-info">
                                                        Ime</label>
                                                    <input type="text" class="form-control" id="firstnameInput"
                                                        placeholder="Unesite vaše ime">
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="lastnameInput"
                                                        class="form-label text-info">Prezime</label>
                                                    <input type="text" class="form-control" id="lastnameInput"
                                                        placeholder="Enter your lastname" value="Surname">
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="phonenumberInput" class="form-label text-info">Broj
                                                        mobitela</label>
                                                    <input type="text" class="form-control" id="phonenumberInput"
                                                        placeholder="Unesite Vaš broj mobitela">
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="emailInput" class="form-label text-info">Email
                                                        adresa</label>
                                                    <input type="email" class="form-control" id="emailInput"
                                                        placeholder="Enter your email">
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="JoiningdatInput" class="form-label text-info">Joining
                                                        Date</label>
                                                    <input type="text" class="form-control" data-provider="flatpickr"
                                                        id="JoiningdatInput" data-date-format="d M, Y"
                                                        data-deafult-date="24 Nov, 2021" placeholder="Select date" />
                                                </div>
                                            </div>
                                            <!--end col-->

                                            <!--end col-->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="designationInput"
                                                        class="form-label text-info">Designation</label>
                                                    <input type="text" class="form-control" id="designationInput"
                                                        placeholder="Designation" value="Lead Designer / Developer">
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="websiteInput1"
                                                        class="form-label text-info">Website</label>
                                                    <input type="text" class="form-control" id="websiteInput1"
                                                        placeholder="www.example.com" value="www.velzon.com" />
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="cityInput" class="form-label text-info">City</label>
                                                    <input type="text" class="form-control" id="cityInput"
                                                        placeholder="City" value="California" />
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="countryInput"
                                                        class="form-label text-info">Country</label>
                                                    <input type="text" class="form-control" id="countryInput"
                                                        placeholder="Country" value="United States" />
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label for="zipcodeInput" class="form-label text-info">Zip
                                                        Code</label>
                                                    <input type="text" class="form-control" minlength="5" maxlength="6"
                                                        id="zipcodeInput" placeholder="Enter zipcode" value="90011">
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-12">
                                                <div class="mb-3 pb-2">
                                                    <label for="exampleFormControlTextarea"
                                                        class="form-label text-info">Description</label>
                                                    <textarea class="form-control" id="exampleFormControlTextarea"
                                                        placeholder="Enter your description"
                                                        rows="3">Hi I'm Anna Adame,It will be as simple as Occidental; in fact, it will be Occidental. To an English person, it will seem like simplified English, as a skeptical Cambridge friend of mine told me what Occidental is European languages are members of the same family.</textarea>
                                                </div>
                                            </div>
                                            <!--end col-->
                                            <div class="col-lg-12">
                                                <div class="hstack gap-2 justify-content-center">
                                                    <button type="submit" class="btn btn-info">Ažuriraj podatke</button>

                                                </div>
                                            </div>
                                            <!--end col-->
                                        </div>
                                        <!--end row-->
                                    </form>

                                </div>


                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Moje fakture tab (activities) -->
            <div class="tab-pane fade" id="activities">
                <div class="table-responsive">
                    <table id="invoicesTable" class="table table-striped table-bordered align-middle mb-0 w-100">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Moje fakture</th>
                                <th>Zemlja porijekla</th>
                                <th>Tip datoteke</th>
                                <th>Cijena</th>
                                <th>Datum</th>
                                <th>Skenirana</th>
                            </tr>
                        </thead>
                        <tbody class="table-light">
                            <!-- AJAX content goes here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="invoiceDetailsModal" tabindex="-1" aria-labelledby="invoiceDetailsModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Pregled fakture</h5>
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
                                                            <h5 class="fs-14 mb-0"><span id="total-amount">--</span> KM
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
                                                                        </span> USD</th>
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
            <div class=" tab-pane fade d-flex justify-content-center mt-0 " id="projects">
                <div class="col-xl-9">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card pricing-box border-0 rounded-0">
                                <div class="card-body p-4 m-2">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h5 class="mb-1 fw-semibold">StartUp</h5>
                                            <p class="text-muted mb-0">Za manja preduzeća</p>
                                        </div>
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-light rounded-circle text-primary">
                                                <i class="ri-star-s-fill text-info fs-5"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pt-4">
                                        <h2>500 <small class="fs-5">KM</small><span
                                                class="fs-6 text-muted">/Mjesec</span></h2>
                                    </div>
                                    <hr class="my-4 text-muted">
                                    <div>
                                        <ul class="list-unstyled text-muted vstack gap-3">
                                            <li>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 text-info me-1">
                                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <b>100</b> Skeniranja
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 text-info me-1">
                                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        Prosječna brzina skeniranja: <b> 20 s </b>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 text-info me-1">
                                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        30 dana
                                                    </div>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 text-info me-1">
                                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <b>24/7</b> Support
                                                    </div>
                                                </div>
                                                <hr class="my-4 text-muted">
                                            </li>



                                        </ul>
                                        <div class="mt-4">
                                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                                data-bs-target="#paymentChoiceModal"
                                                class="btn btn-soft-info w-100 mt-2 text-white">Započni </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-4">
                            <div class="card pricing-box border-0 rounded-0 ribbon-box right border-0 rounded-0 ">
                                <div class="card-body p-4 m-2">
                                    <div class="ribbon-two ribbon-two-info"><span>Popularno</span></div>
                                    <div>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h5 class="mb-1 fw-semibold">GoBig</h5>
                                                <p class="text-muted mb-0">Idealno za biznise u razvoju</p>
                                            </div>
                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-light rounded-circle text-primary">
                                                    <i class="ri-medal-line text-info fs-3 bu"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pt-4">
                                            <h2>850 <small class="fs-5">KM</small><span
                                                    class="fs-6 text-muted">/Mjesec</span></h2>
                                        </div>
                                    </div>
                                    <hr class="my-4 text-muted">
                                    <div>
                                        <ul class="list-unstyled vstack gap-3 text-muted">
                                            <li>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 text-info me-1">
                                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <b>200</b> Skeniranja
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 text-info me-1">
                                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        Prosječna brzina skeniranja: <b> 10 s </b>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 text-info me-1">
                                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        120 dana
                                                    </div>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 text-info me-1">
                                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <b>24/7</b> Support
                                                    </div>
                                                </div>
                                                <hr class="my-4 text-muted">
                                            </li>


                                        </ul>
                                        <div class="mt-4">
                                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                                data-bs-target="#paymentChoiceModal"
                                                class="btn btn-soft-info w-100 mt-2 text-white">Započni </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!--end col-->
                        <div class="col-lg-4">
                            <div class="card pricing-box border-0 rounded-0">
                                <div class="card-body p-4 m-2">
                                    <div>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h5 class="mb-1 fw-semibold">Business</h5>
                                                <p class="text-muted mb-0">Dodatne mogućnosti za velike biznise</p>
                                            </div>
                                            <div class="avatar-sm">
                                                <div class="avatar-title bg-light rounded-circle text-primary">
                                                    <i class="ri-shield-star-line text-info fs-2 "></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pt-4">
                                            <h2 class="text-dark">2000 <small class="fs-5">KM</small><span
                                                    class="fs-6 text-muted">/Mjesec</span></h2>
                                        </div>
                                    </div>
                                    <hr class="my-4 text-muted">
                                    <div>
                                        <ul class="list-unstyled vstack gap-3 text-muted">
                                            <li>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 text-info me-1">
                                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <b>500</b> Skeniranja
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 text-info me-1">
                                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        Prosječna brzina skeniranja: <b> 4 s </b>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 text-info me-1">
                                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        365 dana
                                                    </div>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="d-flex  align-items-center">
                                                    <div class="flex-shrink-0 text-info me-1">
                                                        <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <b>24/7</b> Support
                                                    </div>
                                                </div>
                                                <hr class="my-4 text-muted">
                                            </li>


                                        </ul>
                                        <div class="mt-4">
                                            <a href="javascript:void(0);" data-bs-toggle="modal"
                                                data-bs-target="#paymentChoiceModal"
                                                class="btn btn-soft-info w-100 mt-2 text-white">Započni </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end col-->
            </div><!--end row-->

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

<!-- Scan Modal samo za ovaj screen -->
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



<script>
    document.getElementById('viewAllDocuments').addEventListener('click', function(event) {
        event.preventDefault();

        // Manually trigger the Bootstrap tab
        const triggerTab = new bootstrap.Tab(document.querySelector('a[href="#activities"]'));
        triggerTab.show();
    });
</script>
<!-- jQuery (MUST be before DataTables) -->


<!-- DataTable Initialization -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const token = localStorage.getItem("auth_token");
        const user = JSON.parse(localStorage.getItem("user"));

        if (!token || !user) {
            alert("Niste prijavljeni.");
            return;
        }

        const table = $('#invoicesTable').DataTable({
            ajax: {
                url: `/api/invoices/users/${user.id}`,
                dataSrc: "",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("Authorization", `Bearer ${token}`);
                }
            },
            scrollX: true,
            autoWidth: true,
            lengthChange: false,
            fixedColumns: {
                leftColumns: 1,
                rightColumns: 0
            },
            columns: [{
                    data: null,
                    title: 'ID',
                    render: function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'file_name',
                    title: 'Moje fakture',
                    render: function(data, type, row) {
                        return `<a href="#" class="text-info view-invoice" data-id="${row.id}">${data}</a>`;

                    }
                },
                {
                    data: 'country_of_origin',
                    title: 'Zemlja porijekla'
                },
                {
                    data: 'file_name',
                    title: 'Tip datoteke',
                    render: function(data) {
                        if (!data) return '<span class="badge bg-secondary">N/A</span>';
                        const ext = data.split('.').pop().toLowerCase();

                        let badgeClass = 'bg-secondary';
                        if (ext === 'pdf') badgeClass = 'bg-danger';
                        else if (['xls', 'xlsx'].includes(ext)) badgeClass = 'bg-success';
                        else if (['jpg', 'jpeg', 'png'].includes(ext)) badgeClass = 'bg-warning';
                        else if (ext === 'txt') badgeClass = 'bg-dark';

                        return `<span class="badge ${badgeClass} text-uppercase">${ext}</span>`;
                    }
                },
                {
                    data: 'total_price',
                    title: 'Cijena fakture',
                    render: function(data) {
                        return `${parseFloat(data).toFixed(2)} KM`;
                    }
                },
                {
                    data: 'date_of_issue',
                    title: 'Datum',
                    render: function(data) {
                        return new Date(data).toLocaleDateString('hr');
                    }
                },
                {
                    data: 'scanned',
                    title: 'Skenirana',
                    render: function(data) {
                        return data === 1 ? 'Da' : 'Ne';
                    }
                }
            ],
            dom: 'Bfrtip',
            buttons: [{
                    text: '<i class="ri-ai-generate-2 fs-5 me-1"></i> Skeniraj s AI',
                    className: 'nav-link px-3 py-2 text-custom',
                    action: function() {
                        window.location = '/pages-scan';
                    }
                },
                {
                    extend: 'csv',
                    text: 'Export u CSV',
                    className: 'nav-link px-3 py-2 text-custom'
                },
                {
                    extend: 'excelHtml5',
                    text: 'Export u Excel',
                    className: 'nav-link px-3 py-2 text-custom',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'pdf',
                    text: 'Export u PDF',
                    className: 'nav-link px-3 py-2 text-custom',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    text: 'Štampa',
                    className: 'nav-link px-3 py-2 text-custom',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'colvis',
                    text: 'Kolone',
                    className: 'nav-link px-3 py-2 text-custom'
                },
                {
                    extend: 'pageLength',
                    text: 'Prikaži redova',
                    className: 'nav-link px-3 py-2 text-custom'
                }
            ],
            language: {
                paginate: {
                    first: "Prva",
                    last: "Posljednja",
                    next: "Sljedeća",
                    previous: "Prethodna"
                },
                info: "",
                infoEmpty: "Prikazivanje 0 do 0 od 0 stavki",
                infoFiltered: "(filtrirano iz _MAX_ ukupnih stavki)",
                search: '<i class="ri-search-eye-line fs-4"></i>  ',
                zeroRecords: "Nema pronađenih stavki"
            }
        });

        // Append buttons to header
        table.buttons().container().appendTo('#invoicesTable_wrapper .row .col-md-6:eq(0)');

        // Customize search filter with icon

    });
</script>







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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const token = localStorage.getItem("auth_token");
        const user = JSON.parse(localStorage.getItem("user")); // contains ID
        const avatarBasePath = "/storage/uploads/avatars/";

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
                console.log(" Fetched user data:", data);
                const userData = data.user;
                document.getElementById("user-avatar").src = avatarBasePath + userData.avatar;
                document.getElementById("profile-username").textContent = userData.first_name;
                document.getElementById("profile-lastname").textContent = userData.last_name;

                document.getElementById("profile-location").innerHTML =
                    `<i class="ri-map-pin-user-line align-middle"></i> ${userData.city || 'Nepoznat grad'}, ${userData.country || 'Nepoznata država'}`;
            });

        // 2. Overlay click → open file input
        document.querySelector(".avatar-overlay").addEventListener("click", function() {
            console.log(" Overlay clicked – opening file input");
            document.getElementById("avatar-input").click();
        });

        // 3. On file selected → upload and update avatar
        document.getElementById("avatar-input").addEventListener("change", function() {
            const file = this.files[0];
            if (!file) {
                console.warn(" No file selected.");
                return;
            }

            console.log(" Selected file:", file.name);

            const formData = new FormData();
            formData.append("file", file);
            formData.append("folder", "avatars");

            fetch(`/api/storage/uploads`, {
                    method: "POST",
                    headers: {
                        Authorization: `Bearer ${token}`
                    },
                    body: formData
                })
                .then(async res => {
                    const text = await res.text();
                    console.log(" Raw response from upload:", text);

                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error("Invalid JSON: " + text);
                    }
                })
                .then(uploadResponse => {
                    console.log("Upload response:", uploadResponse);

                    const avatarFileName = uploadResponse.stored_as; // <-- confirm this key
                    if (!avatarFileName) {
                        console.error("avatarFileName not found in upload response.");
                        return;
                    }

                    console.log(" New avatar file name:", avatarFileName);

                    // Update user with new avatar
                    return fetch(`/api/users/${user.id}`, {
                        method: "PUT",
                        headers: {
                            Authorization: `Bearer ${token}`,
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            avatar: avatarFileName
                        })
                    });
                })
                .then(res => res.json())
                .then(updateResponse => {
                    console.log("Update response:", updateResponse);
                    const updatedAvatar = updateResponse.user.avatar;
                    document.getElementById("user-avatar").src = `${avatarBasePath}${updatedAvatar}?t=${Date.now()}`;
                })
                .catch(err => {
                    console.error("Error during avatar upload or update:", err);
                });
        });
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        $(document).on('click', '.view-invoice', function(e) {
            e.preventDefault();
            const invoiceId = $(this).data('id');
            const token = localStorage.getItem("auth_token");

            fetch(`/api/invoices/${invoiceId}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                })
                .then(response => response.json())
                .then(invoice => {
                    // Fetch supplier details
                    fetch(`/api/suppliers/${invoice.supplier_id}`, {
                            headers: {
                                'Authorization': `Bearer ${token}`
                            }
                        })
                        .then(res => res.json())
                        .then(supplier => {
                            // Fill invoice data
                            $('#invoice-no').text(invoice.id);
                            $('#invoice-date').text(new Date(invoice.date_of_issue).toLocaleDateString('hr'));
                            $('#total-amount').text(`${parseFloat(invoice.total_price).toFixed(2)} KM`);
                            $('#card-total-amount').text(`${parseFloat(invoice.total_price).toFixed(2)}`);
                            $('#address-details').text(invoice.country_of_origin || '-');
                            $('#payment-status').text(invoice.scanned === 1 ? 'Skenirano' : 'Nije skenirano');
                            $('#shipping-country').text(invoice.country_of_origin || '--');

                            // Supplier info
                            $('#billing-name').text(supplier.name || '--');
                            $('#billing-address-line-1').text(supplier.address || '--');
                            $('#billing-phone-no').text(supplier.contact_phone || '--');
                            $('#billing-tax-no').text(supplier.tax_id || '--');
                            $('#email').text(supplier.contact_email || '--');

                            // Fill invoice items and calculate total
                            let itemsHTML = '';
                            let totalSum = 0;

                            invoice.items.forEach((item, index) => {
                                const price = parseFloat(item.total_price || 0);
                                totalSum += price;

                                itemsHTML += `
                                        <tr>
                                            <th scope="row">${index + 1}</th>
                                            <td class="text-start fw-medium">${item.item_description}</td> <!-- Artikal -->
                                            <td class="text-muted">${item.item_description_original}</td> <!-- Opis -->
                                            <td>${item.base_price} ${item.currency}</td> <!-- Cijena -->
                                            <td>${item.quantity}</td> <!-- Količina -->
                                            <td class="text-end">${item.total_price} ${item.currency}</td> <!-- Ukupno -->
                                        </tr>`;
                            });

                            $('#products-list').html(itemsHTML);
                            $('#modal-total-amount').text(totalSum.toFixed(2));

                            // Show modal
                            const modal = new bootstrap.Modal(document.getElementById('invoiceDetailsModal'));
                            modal.show();
                        });
                })
                .catch(err => {
                    console.error('Greška pri učitavanju fakture:', err);
                    alert('Greška pri učitavanju fakture.');
                });
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch('/storage/data/tariff.json')
            .then(response => response.json())
            .then(data => {
                const table = $('#tariffTable').DataTable({
                    data: data,
                    scrollX: true,
                    autoWidth: true,
                    lengthChange: false,
                    fixedColumns: {
                        leftColumns: 1,
                        rightColumns: 0
                    },
                    columns: [{
                            data: 'Tarifna oznaka',
                            title: 'Tarifna oznaka'
                        },
                        {
                            data: 'Naziv',
                            title: 'Naziv'
                        },
                        {
                            data: 'Dopunska jedinica',
                            title: 'Jedinica'
                        },
                        {
                            data: 'Carinska stopa (%)',
                            title: 'Stopa (%)'
                        },
                        {
                            data: 'EU',
                            title: 'EU'
                        },
                        {
                            data: 'CEFTA',
                            title: 'CEFTA'
                        },
                        {
                            data: 'IRN',
                            title: 'IRN'
                        },
                        {
                            data: 'TUR',
                            title: 'TUR'
                        },
                        {
                            data: 'CHE, LIE',
                            title: 'CHE, LIE'
                        },
                        {
                            data: 'ISL',
                            title: 'ISL'
                        },
                        {
                            data: 'NOR',
                            title: 'NOR'
                        }
                    ],
                    dom: 'Bfrtip',
                    buttons: [
                        'csv',
                        'excelHtml5',
                        'pdf',
                        'print',
                        'colvis',
                        'pageLength'
                    ],
                    language: {
                        paginate: {
                            first: "Prva",
                            last: "Posljednja",
                            next: "Sljedeća",
                            previous: "Prethodna"
                        },
                        info: "",
                        infoEmpty: "Prikazivanje 0 do 0 od 0 stavki",
                        infoFiltered: "(filtrirano iz _MAX_ ukupnih stavki)",
                        search: "Pretraga:",
                        zeroRecords: "Nema pronađenih stavki"
                    }
                });

                // Append buttons
                table.buttons().container().appendTo('#tariffTable_wrapper .row .col-md-6:eq(0)');

                // Add row click handler
                $('#tariffTable tbody').on('click', 'tr', function() {
                    const rowData = table.row(this).data();
                    alert("Puni naziv: " + rowData["Puni Naziv"]);
                });
            })
            .catch(err => {
                console.error("Greška pri učitavanju tariff podataka:", err);
            });
    });
</script>


<!-- select field -->
<script>
    let processed = [];

    fetch('/storage/data/tariff.json')
        .then(res => res.json())
        .then(data => {
            processed = data
                .filter(item => item["Puni Naziv"] && item["Tarifna oznaka"]) // skip bad rows
                .map(item => {
                    const hierarchy = item["Puni Naziv"];
                    const parts = hierarchy.split(">>>").map(p => p.trim());
                    const leaf = parts[parts.length - 1];
                    const depth = parts.length - 1;
                    const code = item["Tarifna oznaka"];
                    const isLeaf = code && code.replace(/\s/g, '').length === 10;

                    return {
                        id: isLeaf ? code : null, // only leafs are selectable
                        text: leaf,
                        display: `${code} – ${leaf}`,
                        depth: depth,
                        isLeaf: isLeaf,
                        hierarchy: hierarchy,
                        search: [item["Naziv"], hierarchy, code].join(" ").toLowerCase(),
                        full: item
                    };
                });

            $('#tariffSelect').select2({
                placeholder: "Pretraži tarifne stavke...",
                minimumInputLength: 1,
                ajax: {
                    transport: function (params, success, failure) {
                        const term = params.data.q?.toLowerCase() || "";
                        const filtered = processed.filter(item =>
                            item.search.includes(term)
                        );
                        success({ results: filtered });
                    },
                    delay: 200
                },
                templateResult: function (item) {
                    if (!item.id && !item.text) return null;

                    const icon = item.isLeaf ? "•" : "▶";
                    const label = item.display || item.text;
                    return $(`<div style="padding-left:${item.depth * 20}px;">
                        ${icon} ${label}
                    </div>`);
                },
                templateSelection: function (item) {
                    return item.id ? `${item.id} – ${item.text}` : "";
                }
            });

            $('#tariffSelect').on('select2:select', function (e) {
                const selectedData = e.params.data.full;
                alert("Odabrana tarifna stavka:\n" + selectedData["Puni Naziv"]);
                console.log("Selected full object:", selectedData);
            });
        });
</script>


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
    const scanningLoader = document.getElementById("scanningLoader");
    const scanningText = document.getElementById("scanningText");
    const successCheck = document.getElementById("successCheck");

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

    function generateFakeScanData() {
        return [
            { "Tarifna oznaka": "0101 21 00 00", "Naziv": "čistokrvne priplodne životinje", "Quantity": 2, "Unit Price": 540.00 },
            { "Tarifna oznaka": "2710 12 41 00", "Naziv": "kerozin", "Quantity": 120, "Unit Price": 1.25 },
            { "Tarifna oznaka": "0201 30 00 00", "Naziv": "goveđe meso", "Quantity": 46, "Unit Price": 1.14 }
        ];
    }

    function simulateScan() {
        scanningLoader.classList.remove("d-none");
        dropzoneContent.style.display = "none";
        fileList.style.display = "none";

        const spinner = scanningLoader.querySelector(".spinner-border");
        const stages = [
            { text: "Skeniranje fakture...", until: 25 },
            { text: "Prepoznavanje podataka...", until: 60 },
            { text: "Generisanje fakture...", until: 90 },
            { text: "Završeno skeniranje...", until: 100 }
        ];

        let progress = 0;
        const interval = setInterval(() => {
            progress++;
            for (const stage of stages) {
                if (progress <= stage.until) {
                    scanningText.innerText = stage.text;
                    break;
                }
            }

            if (progress >= 100) {
                clearInterval(interval);
                if (spinner) {
                    spinner.classList.add("fade-out");
                    setTimeout(() => {
                        spinner.remove();
                        scanningText.classList.add("d-none");

                        successCheck.classList.remove("d-none");
                        successCheck.classList.add("animate__animated", "animate__fadeIn");

                        const fakeScanResults = generateFakeScanData();
                        console.log("Saving to localStorage:", fakeScanResults);
                        localStorage.setItem("ai_scan_result", JSON.stringify(fakeScanResults));

                        setTimeout(() => {
                            window.location.href = "/apps-invoices-create";
                        }, 500);
                    }, 400);
                }
            }
        }, 50);
    }

    function uploadFiles(files) {
        const formData = new FormData();
        Array.from(files).forEach(file => formData.append('file', file));

        progressContainer.style.display = "block";
        progressBar.style.width = "0%";
        progressBar.innerText = "0%";

        let fakeProgress = 0;
        const fakeInterval = setInterval(() => {
            fakeProgress += 3;
            if (fakeProgress > 100) fakeProgress = 100;

            progressBar.style.width = fakeProgress + "%";
            progressBar.innerText = fakeProgress + "%";

            if (fakeProgress === 100) {
                clearInterval(fakeInterval);
                Swal.fire({
                    icon: "success",
                    title: "Uspješno uploadan dokument",
                    showConfirmButton: false,
                    timer: 1600
                }).then(() => {
                    progressContainer.style.display = "none";
                    simulateScan();
                });
            }
        }, 200);
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