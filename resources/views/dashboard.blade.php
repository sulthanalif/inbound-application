@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section class="section dashboard">
        @include('dashboard-headwarehouse')
        @include('dashboard-adminwarehouse')
        @include('dashboard-adminengineer')
        @include('dashboard-outboundinbound')
        @include('chart')
    </section>
@endsection

@push('scripts')
    <script>
        $('document').ready(function() {
            $('#filter_month').select2({
                placeholder: 'Month',
                theme: 'bootstrap4',
            });

            $('#filter_year').select2({
                placeholder: 'Year',
                theme: 'bootstrap4',
            });
        })

        $('#filter_month').on('change', function() {
            var month = $(this).val();
            var currentParams = new URLSearchParams(window.location.search);
            if (month === 'all') {
                currentParams.delete('filter_month');
            } else {
                currentParams.set('filter_month', month);
            }
            window.location.href = "{{ route('dashboard') }}" + '?' + currentParams.toString();
        });

        $('#filter_year').on('change', function() {
            var year = $(this).val();
            var currentParams = new URLSearchParams(window.location.search);
            if (year === 'all') {
                currentParams.delete('filter_year');
            } else {
                currentParams.set('filter_year', year);
            }
            window.location.href = "{{ route('dashboard') }}" + '?' + currentParams.toString();
        });
    </script>
@endpush
