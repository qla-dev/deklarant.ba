@extends('layouts.master')
@section('title')
@lang('translation.search-results')
@endsection
@section('css')
<link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">
<style>
a:hover + .border {
  border-color: #54cc94 !important;
}



</style>
@endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1')
deklarant.ai
@endslot
@slot('title')
Rezultati pretrage za "<span class="text-info fw-medium ml-0 pl-0">{{ request('keyword') }}</span>"
@endslot
@endcomponent
<div class="row">
  <div class="col-lg-12">
    <div class="card">


      <div class="card-body p-4">
        <div class="tab-content text-muted">
          <!-- Moje deklaracije -->
          <div class="tab-pane active" id="all" role="tabpanel">
            <div id="invoice-loading">
              <div class="text-center my-4">
                <i class="mdi mdi-loading mdi-spin fs-24 text-info"></i>
                <p class="text-muted">Učitavanje rezultata</p>
              </div>
            </div>
            <div id="invoice-results"></div>
          </div>


        </div>
      </div>
    </div>
  </div>
</div>

<!-- JS script for fetching invoices -->
<script>
  document.addEventListener("DOMContentLoaded", async function() {
    const container = document.getElementById("invoice-results");
    const loading = document.getElementById("invoice-loading");
  
    const keyword = "{{ request('keyword') }}";

    if (!user || !token) {
      loading.remove();
      container.innerHTML = `<p class="text-danger">Niste prijavljeni.</p>`;
      return;
    }

    try {
      const res = await fetch(`/api/invoices/users/${user.id}`, {
        headers: {
          Authorization: `Bearer ${token}`
        }
      });

      const data = await res.json();
      loading.remove();

      if (!data || !Array.isArray(data) || data.length === 0) {
        container.innerHTML = `<p class="text-muted mb-0">Nema pronađenih deklaracija za "<strong>${keyword}</strong>".</p>`;
        return;
      }

      // Filter data based on keyword
      const filtered = data.filter(item =>
        item.country_of_origin?.toLowerCase().includes(keyword.toLowerCase()) ||
        item.file_name?.toLowerCase().includes(keyword.toLowerCase()) ||
        item.invoice_number?.toLowerCase().includes(keyword.toLowerCase()) ||
        item.supplier?.name?.toLowerCase().includes(keyword.toLowerCase())
      );

      if (filtered.length === 0) {
        container.innerHTML = `<p class="text-muted mb-0">Nema rezultata koji odgovaraju upitu "<strong>${keyword}</strong>".</p>`;
        return;
      }

      // Render list with direct links to details page
      let html = "";
      filtered.forEach((invoice, index) => {
        html += `
  <a href="/detalji-deklaracije/${invoice.id}" target="_blank" rel="noopener noreferrer" style="text-decoration: none; color: inherit;">
    <div class="invoice-result" style="cursor:pointer;">
      <h5 class="mb-3">
        ${invoice.invoice_number || 'Broj deklaracije nije definisan'}
      </h5>
      <p class="text-success mb-0">
        <i class="ri-file-line align-middle me-1 fs-15" style="margin-top:-4px; font-size:15px!important"></i>${invoice.file_name || 'Nepoznato ime fajla'}
      </p>
      <p class="text-success mb-0">
        <i class="ri-truck-line align-middle me-1 fs-15" style="margin-top:-4px; font-size:15px!important"></i>${invoice.supplier?.name || 'Nepoznati pošiljatelj'}
      </p>
      <p class="text-success mb-0">
        <i class="ri-globe-line align-middle me-1 fs-15" style="margin-top:-4px; font-size:15px!important"></i>${invoice.country_of_origin || 'Nepoznata zemlja projekta'}
      </p>
      <ul class="list-inline d-flex align-items-center g-3 text-muted fs-14 mb-0 mt-3 mb-3">
        <li class="list-inline-item me-3 d-flex" style="align-items:center!important">
          <i class="ri-calendar-2-line align-middle me-1 fs-15" style="font-size:15px!important"></i>${
            (() => {
              const d = new Date(invoice.date_of_issue);
              return `${(d.getMonth() + 1).toString().padStart(2, '0')}.${d.getDate().toString().padStart(2, '0')}.${d.getFullYear()}`;
            })()
          }
        </li>
        <li class="list-inline-item me-3 d-flex" style="align-items:center!important">
          <i class="ri-money-dollar-circle-line align-middle me-1 fs-15" style="font-size:15px!important"></i>${parseFloat(invoice.total_price).toFixed(2)} KM
        </li>
      </ul>
    </div>
  </a>
  ${index < filtered.length - 1 ? '<div class="border border-dashed mb-3"></div>' : ''}
`;

      });

      container.innerHTML = html;

      // Removed modal click handler code here

    } catch (err) {
      console.error("Greška prilikom dohvata faktura:", err);
      loading.remove();
      container.innerHTML = `<p class="text-danger">Greška prilikom dohvata faktura.</p>`;
    }
  });
</script>


<!-- Invoice Details Modal -->
<div class="modal fade" id="invoiceDetailsModal" tabindex="-1" aria-labelledby="invoiceDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <!-- modal header -->
      <div class="modal-header">
        <h5 class="modal-title text-center w-100">
          <i class="fas fa-file-alt" style="font-size:14px;margin-top:-7px!important"></i> Pregled deklaracije
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvori"></button>
      </div>
      <!-- modal body -->
      <div class="modal-body p-0">
        <!-- Your detailed modal content here -->
        <div class="row justify-content-center">
          <div class="card" id="demo">
            <div class="row">
              <div class="col-lg-12">
                <div class="card-header border-bottom-dashed p-4 d-flex justify-content-between">
                  <div>
                    <img src="{{ URL::asset('build/images/logo-dark-ai.png') }}" class="card-logo" alt="logo" height="30">
                    <div class="mt-4">
                      <h6 class="text-muted text-uppercase fw-semibold">Adresa</h6>
                      <p class="text-muted mb-1" id="address-details">--</p>
                      <p class="text-muted mb-0"><span>Poštanski broj:</span> <span id="zip-code">--</span></p>
                    </div>
                  </div>
                  <div class="text-end">
                    <h6><span class="text-muted fw-normal">Email:</span> <span id="email">--</span></h6>
                    <h6><span class="text-muted fw-normal">Web:</span> <a href="#" class="link-primary" target="_blank" id="website">--</a></h6>
                    <h6 class="mb-0"><span class="text-muted fw-normal">Telefon:</span> <span id="contact-no">--</span></h6>
                  </div>
                </div>
              </div>

              <div class="col-lg-12">
                <div class="card-body p-4">
                  <div class="row g-3">
                    <div class="col-lg-3 col-6">
                      <p class="text-muted mb-2 text-uppercase fw-semibold">Faktura #</p>
                      <h5 class="fs-14 mb-0">#<span id="invoice-no">--</span></h5>
                    </div>
                    <div class="col-lg-3 col-6">
                      <p class="text-muted mb-2 text-uppercase fw-semibold">Datum</p>
                      <h5 class="fs-14 mb-0"><span id="invoice-date">--</span></h5>
                    </div>
                    <div class="col-lg-3 col-6">
                      <p class="text-muted mb-2 text-uppercase fw-semibold">Skenirana</p>
                      <span class="badge bg-light text-dark fs-11" id="payment-status">--</span>
                    </div>
                    <div class="col-lg-3 col-6">
                      <p class="text-muted mb-2 text-uppercase fw-semibold">Ukupan iznos</p>
                      <h5 class="fs-14 mb-0"><span id="total-amount">--</span></h5>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-12">
                <div class="card-body p-4 border-top border-top-dashed">
                  <div class="row g-3">
                    <div class="col-6">
                      <h6 class="text-muted text-uppercase fw-semibold mb-3">pošiljatelj</h6>
                      <p class="fw-medium mb-2" id="billing-name">--</p>
                      <p class="text-muted mb-1" id="billing-address-line-1">--</p>
                      <p class="text-muted mb-1"><span>Telefon: </span><span id="billing-phone-no">--</span></p>
                      <p class="text-muted mb-0"><span>PIB: </span><span id="billing-tax-no">--</span></p>
                    </div>
                    <div class="col-6">
                      <h6 class="text-muted text-uppercase fw-semibold mb-3">Zemlja porijekla</h6>
                      <p class="fw-medium mb-2" id="shipping-country">--</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Invoice Items -->
              <div class="col-lg-12">
                <div class="card-body p-4">
                  <div class="table-responsive">
                    <table class="table table-borderless text-center table-nowrap align-middle mb-0">
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
                    <table class="table table-borderless table-nowrap align-middle mb-0 ms-auto" style="width:250px">
                      <tbody>
                        <tr class="border-top border-top-dashed fs-15">
                          <th scope="row">Ukupno</th>
                          <th class="text-end"><span id="modal-total-amount"></span> KM</th>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="mt-4">
                    <h6 class="text-muted text-uppercase fw-semibold mb-3">Detalji plaćanja:</h6>
                    <p class="text-muted mb-1">Način plaćanja: <span class="fw-medium">Kartica</span></p>
                    <p class="text-muted mb-1">Ime vlasnika kartice: <span class="fw-medium">Tin Tomić</span></p>
                    <p class="text-muted mb-1">Broj kartice: <span class="fw-medium">xxxx xxxx xxxx 1234</span></p>
                    <p class="text-muted">Ukupno za platiti: <span class="fw-medium"><span id="payment-method-amount">--</span> KM</span></p>
                  </div>

                  <div class="mt-4">
                    <div class="alert alert-info">
                      <p class="mb-0"><span class="fw-semibold">Napomena:</span> <span id="note">Račun je informativnog karaktera. Provjerite detalje prije plaćanja.</span></p>
                    </div>
                  </div>

                  <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                    <a href="javascript:void(0);" class="btn btn-success" onclick="printInvoiceModal()">
                      <i class="ri-printer-line align-bottom me-1"></i> Print
                    </a>
                    <a href="javascript:void(0);" class="btn btn-primary">
                      <i class="ri-download-2-line align-bottom me-1"></i> Download
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


<!--end row-->
@endsection
@section('script')
<script src="{{ URL::asset('build/libs/glightbox/js/glightbox.min.js') }}"></script>

<!-- swiper js -->
<script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>

<!-- search-result init js -->
<script src="{{ URL::asset('build/js/pages/search-result.init.js') }}"></script>

<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection