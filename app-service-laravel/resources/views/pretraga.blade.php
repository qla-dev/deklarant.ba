@extends('layouts.master')
@section('title')
    @lang('translation.search-results')
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/glightbox/css/glightbox.min.css') }}">

@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Pages
        @endslot
        @slot('title')
            Rezultati pretrage
        @endslot
    @endcomponent
  <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-0">
                <h5 class="fs-16 fw-semibold text-start mb-0">
                    Prikazivanje rezultata za "<span class="text-info fw-medium fst-italic">{{ request('keyword') }}</span>"
                </h5>
            </div>

            <div>
                <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#all" role="tab">
                            <i class="ri-file-line text-muted align-bottom me-1"></i> Moje deklaracije
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#images" role="tab">
                            <i class="ri-truck-line text-muted align-bottom me-1"></i> Moji dobavljači
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#news" role="tab">
                            <i class="ri-list-unordered text-muted align-bottom me-1"></i> Tarife
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body p-4">
                <div class="tab-content text-muted">
                    <!-- Moje deklaracije -->
                    <div class="tab-pane active" id="all" role="tabpanel">
                        <div id="invoice-results" class="pt-3">
                            <div id="invoice-loading" class="text-center my-5">
                                <div class="spinner-border text-info" role="status"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Dobavljači -->
                    <div class="tab-pane" id="images" role="tabpanel">
                        <p class="text-muted">Nema rezultata za dobavljače.</p>
                    </div>

                    <!-- Tarife -->
                    <div class="tab-pane" id="news" role="tabpanel">
                        <p class="text-muted">Nema rezultata za tarifne stavke.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS script for fetching invoices -->
<script>
    document.addEventListener("DOMContentLoaded", async function () {
        const container = document.getElementById("invoice-results");
        const loading = document.getElementById("invoice-loading");
        const keyword = "{{ request('keyword') }}";

        try {
            const res = await fetch(`/api/invoices/search?keyword=${encodeURIComponent(keyword)}`);
            const data = await res.json();

            loading.remove(); // remove spinner

            if (!data || !Array.isArray(data) || data.length === 0) {
                container.innerHTML = `<p class="text-muted">Nema pronađenih faktura za "<strong>${keyword}</strong>".</p>`;
                return;
            }

            let html = '';

            data.forEach(invoice => {
                html += `
                    <div class="pb-3">
                        <h5 class="mb-1">
                            <a href="/invoices/${invoice.id}" class="text-body">
                                ${invoice.invoice_number}
                            </a>
                        </h5>
                        <p class="text-success mb-2">${invoice.supplier?.name || 'Nepoznat dobavljač'}</p>
                        <p class="text-muted mb-2">${invoice.description || 'Nema opisa'}</p>
                        <ul class="list-inline d-flex align-items-center g-3 text-muted fs-14 mb-0">
                            <li class="list-inline-item me-3">
                                <i class="ri-calendar-2-line align-middle me-1"></i>${invoice.date}
                            </li>
                            <li class="list-inline-item me-3">
                                <i class="ri-money-dollar-circle-line align-middle me-1"></i>${invoice.total} KM
                            </li>
                        </ul>
                    </div>
                    <div class="border border-dashed mb-3"></div>
                `;
            });

            container.innerHTML = html;

        } catch (err) {
            console.error("Greška prilikom dohvata faktura:", err);
            loading.remove();
            container.innerHTML = `<p class="text-danger">Greška prilikom dohvata faktura.</p>`;
        }
    });
</script>

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
