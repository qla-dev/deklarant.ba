@extends('layouts.master')
@section('title')
@lang('translation.details')
@endsection
@section('css')
<style>
    .detached-fixed-buttons {

        position: fixed !important;
        top: calc(70.8px + 40.5px);
        /* 110.91px total offset */
        margin-top: 6px;
        width: 13.19vw;
        
        z-index: 1050;
    }

   

    /* Push down below the fixed topbar */

    /* Or whatever horizonta  offset fits your layout */


    /* Above most content */


    /* Optional: for better visibility */
   
</style>
@endsection
@section('content')


<div class="row justify-content-center mt-0 mb-3">


    <div class="card" id="demo">
        <div class="row">

            <div class="col-lg-12">
                <div class="card-header border-0  p-4">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <img src="{{ URL::asset('build/images/logo-dek.png') }}" class="card-logo card-logo-dark" alt="logo dark" height="34">
                            <img src="{{ URL::asset('build/images/logo-light.png') }}" class="card-logo card-logo-light" alt="logo light" height="17">

                        </div>
                    </div>
                    <!--end card-header-->


                </div>
                <div class="col-lg-12">
                    <div class="card-body p-4  ">
                        <div class="row g-4 mb-3 ">

                            <!--end col-->
                            <div class="col-12 text-start">
                                <h6 class="text-muted text-uppercase fw-semibold mb-3 mt-2">Osnovni podaci</h6>
                                <p class="text-muted mb-1"><span>E-mail: </span><span id="shipping-phone-no">deklarant@skeniraj.ba</span></p>
                                <p class="text-muted mb-1"><span>Tel: </span><span id="shipping-phone-no">+(387) 63974234</span></p>
                                <p class="text-muted mb-1"><span>VAT: </span><span id="shipping-phone-no">BA12314519</span></p>
                                <p class="text-muted mb-1"><span>Adresa: </span><span id="shipping-phone-no">Vilsonovo 9, Sarajevo</span></p>

                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end card-body-->
                </div>
                <!--end col-->
                <div class="col-lg-12">
                    <div class="card-body p-4 border-top border-top-dashed">
                        <div class="row g-4 mb-3 ">
                            <div class="col-6 text-start">
                                <h6 class="text-muted text-uppercase fw-semibold mb-3">Podaci o dobavljaču</h6>
                                <p class="fw-medium mb-2" id="supplier-name">Motorex</p>
                                <p class="text-muted mb-1" id="supplier-address">305 S San Gabriel Blvd</p>
                                <p class="text-muted mb-1"><span>Tel: +</span><span id="billing-phone-no">(387)
                                        456-789</span></p>
                                <p class="text-muted mb-0"><span>Tax: </span><span id="billing-tax-no">12-3456789</span> </p>
                            </div>
                            <!--end col-->
                            <div class="col-6 text-end">
                                <h6 class="text-muted text-uppercase fw-semibold mb-3">Podaci o uvozniku</h6>
                                <p class="fw-medium mb-2" id="carrier-name">SCHENKER</p>
                                <p class="text-muted mb-1" id="carrier-address-line-1">305 S San Gabriel Blvd</p>
                                <p class="text-muted mb-1"><span>Tel: +</span><span id="shipping-phone-no">(123)
                                        456-7890</span></p>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end card-body-->
                </div>
                <div class="col-lg-12">
                    <div class="card-body p-4 border-top border-top-dashed">
                        <div class="row g-4 mb-3 justify-content-between align-items-center ">
                            <div class="col-lg-4 text-start">
                                <p class="text-muted mb-2 text-uppercase fw-semibold">Broj fakture:</p>
                                <h5 class="fs-14 mb-0">#<span id="invoice-no">25000355</span></h5>
                            </div>
                            <!--end col-->
                            <div class="col-lg-4 text-center">
                                <p class="text-muted mb-2 text-uppercase fw-semibold">Datum izdavanja</p>
                                <h5 class="fs-14 mb-0"><span id="invoice-date">23. 05. 2025.</span> </h5>
                            </div>
                            <!--end col-->
                            <!--end col-->
                            <div class="col-lg-4 text-end">
                                <p class="text-muted mb-2 text-uppercase fw-semibold">Ukupan iznos:</p>
                                <h5 class="fs-14 mb-0" id="currency">KM<span id="total-amount"> 755.96</span></h5>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end card-body-->
                </div>
                <!--end col-->

                <!--end col-->
                <div class="col-lg-12">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-borderless text-center table-nowrap align-middle mb-0">
                                <thead>
                                    <tr class="table-active">
                                        <th scope="col" style="width: 50px;">#</th>
                                        <th scope="col">Proizvodi</th>
                                        <th scope="col">Tarifna oznaka</th>
                                        <th scope="col">Cijena</th>
                                        <th scope="col">Količina</th>
                                        <th scope="col">Zemlja porijekla</th>
                                        <th scope="col" class="text-end">Ukupna cijena</th>
                                    </tr>
                                </thead>
                                <tbody id="products-list">
                                    <tr>
                                        <th scope="row">01</th>
                                        <td class="text-start">
                                            <span class="fw-medium">Sweatshirt for Men (Pink)</span>
                                            <p class="text-muted mb-0">Graphic Print Men & Women Sweatshirt</p>
                                        </td>
                                        <td>8764 32 21 20</td>
                                        <td>$119.99</td>
                                        <td>02</td>
                                        <td>Njemačka</td>
                                        <td class="text-end">$239.98</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">02</th>
                                        <td class="text-start">
                                            <span class="fw-medium">Sweatshirt for Men (Pink)</span>
                                            <p class="text-muted mb-0">Graphic Print Men & Women Sweatshirt</p>
                                        </td>
                                        <td>8764 32 21 20</td>
                                        <td>$119.99</td>
                                        <td>02</td>
                                        <td>Njemačka</td>
                                        <td class="text-end">$239.98</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">03</th>
                                        <td class="text-start">
                                            <span class="fw-medium">350 ml Glass Grocery Container</span>
                                            <p class="text-muted mb-0">Glass Grocery Container (Pack of 3, White)</p>
                                        </td>
                                        <td>8764 32 21 20</td>
                                        <td>$119.99</td>
                                        <td>03</td>
                                        <td>Njemačka</td>
                                        <td class="text-end">$239.98</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">04</th>
                                        <td class="text-start">
                                            <span class="fw-medium">Fabric Dual Tone Living Room Chair</span>
                                            <p class="text-muted mb-0">Chair (White)</p>
                                        </td>
                                        <td>8764 32 21 20</td>
                                        <td>$119.99</td>
                                        <td>04</td>
                                        <td>Njemačka</td>
                                        <td class="text-end">$239.98</td>
                                    </tr>
                                </tbody>
                            </table>
                            <!--end table-->
                        </div>
                        <div class="border-top border-top-dashed mt-2">
                            <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto" style="width:250px">
                                <tbody class="border-bottom-dashed">

                                    <tr class="border-top border-top-dashed fs-15">
                                        <th scope="row">Ukupan iznos</th>
                                        <th class="text-end">KM 755.96</th>
                                    </tr>
                                </tbody>
                            </table>
                            <!--end table-->

                            </table>
                            <!--end table-->
                        </div>




                    </div>
                    <!--end card-body-->
                </div>

                <!--end col-->
            </div>
            <!--end row-->
        </div>

        <!--end card-->
    </div>
    <div class="col-2 d-print-none" id="sidebar-buttons-container">
        <div id="fixed-buttons" class="d-flex flex-column gap-2">
            <a href="javascript:window.print()" class="btn btn-info">
                <i class="ri-printer-line align-bottom me-1"></i> Isprintaj deklaraciju
            </a>
            <a href="javascript:void(0);" class="btn btn-info">
                <i class="ri-download-2-line align-bottom me-1"></i> Preuzmi
            </a>
        </div>
    </div>

</div>


<!--end row-->
@endsection
@section('script')
<script src="{{ URL::asset('build/js/pages/invoicedetails.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('sidebar-buttons-container');
        const fixedButtons = document.getElementById('fixed-buttons');
        const topBarHeight = 70.8; // exact topbar height

        window.addEventListener('scroll', () => {
            if (window.scrollY >= topBarHeight) {
                fixedButtons.classList.add('detached-fixed-buttons');
            } else {
                fixedButtons.classList.remove('detached-fixed-buttons');
            }
        });
    });
</script>
@endsection