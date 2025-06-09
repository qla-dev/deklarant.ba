@extends('layouts.master')
@section('css')
@endsection
@section('title') @lang('translation.pricing') @endsection
@section('content')




<div class="row justify-content-center mb-2">
    <div class="col-lg-5 mt-4 mb-4">
        <div class="text-center pb-2">
            <h4 class="fw-semibold fs-22">Odaberi paket koji odgovara tvojim potrebama <span class="fs-17" style="color:gray!important"><br>(odustani bez naknade bilo kada)</span></h4>
            <p class="text-muted fs-15 mb-3">
                Provjeri kako se prenose preostala AI skeniranja u slučaju nadogradnje paketa, koliko dugo čuvamo podatke nakon isteka pretplate, kao i ostale korisne informacije u 
                <a href="/faqs" class="text-info text-decoration-underline">čestim pitanjima</a>.
            </p>
           
      </div>
    </div><!--end col-->
</div><!--end row-->
 @include('components.pricing')


@endsection
@section('script')
<script src="{{ URL::asset('build/js/pages/pricing.init.js') }}"></script>

<script src="{{ URL::asset('build/js/app.js') }}"></script>


@endsection