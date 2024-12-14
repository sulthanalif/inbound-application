@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section class="section dashboard">
       @include('dashboard-headwarehouse')

        <div class="row">

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Transaction Amount</h5>

                        <!-- Column Chart -->
                        <div id="columnChart"></div>

                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                new ApexCharts(document.querySelector("#columnChart"), {
                                    series: [{
                                        name: 'Outbound',
                                        data: [44, 55, 57, 56, 61, 58, 63, 60, 66, 55, 34, 67]
                                    }, {
                                        name: 'Inbound',
                                        data: [76, 85, 101, 98, 87, 105, 91, 114, 94, 63, 60, 66]
                                    }, {
                                        name: 'Return',
                                        data: [35, 41, 36, 26, 45, 48, 52, 53, 41, 85, 101, 98]
                                    }],
                                    chart: {
                                        type: 'bar',
                                        height: 350
                                    },
                                    plotOptions: {
                                        bar: {
                                            horizontal: false,
                                            columnWidth: '55%',
                                            endingShape: 'rounded'
                                        },
                                    },
                                    dataLabels: {
                                        enabled: false
                                    },
                                    stroke: {
                                        show: true,
                                        width: 2,
                                        colors: ['transparent']
                                    },
                                    xaxis: {
                                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct',
                                            'Nov', 'Dec'
                                        ],
                                    },
                                    yaxis: {
                                        title: {
                                            text: ''
                                        }
                                    },
                                    fill: {
                                        opacity: 1
                                    },
                                    tooltip: {
                                        y: {
                                            formatter: function(val) {
                                                return val
                                            }
                                        }
                                    }
                                }).render();
                            });
                        </script>
                        <!-- End Column Chart -->

                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Comparison Type Items</h5>
                            <div class="d-flex">
                                <select name="filter_month" class="form-select" id="filter_month">
                                    <option value="" selected disabled></option>
                                    <option value="all">All</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}"
                                            {{ request()->filter_month == $i ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create(request()->filter_year, $i, 1)->locale('id_ID')->monthName }}
                                        </option>
                                    @endfor
                                </select>
                                <select name="filter_year" class="form-select" id="filter_year">
                                    <option value="" selected disabled></option>
                                    {{-- <option value="all">All</option> --}}
                                    @for ($i = 2023; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}"
                                            {{ request()->filter_year == $i ? 'selected' : (date('Y') == $i ? 'selected' : '') }}>
                                            {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <!-- Donut Chart -->
                        <div id="donutChart"></div>

                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                new ApexCharts(document.querySelector("#donutChart"), {
                                    series: [{{ $chart_data_type_items['Rentable'] }},
                                        {{ $chart_data_type_items['Consumable'] }}
                                    ],
                                    chart: {
                                        height: 350,
                                        type: 'donut',
                                        toolbar: {
                                            show: true
                                        }
                                    },
                                    labels: ['Rentable', 'Consumable'],
                                }).render();
                            });
                        </script>
                        <!-- End Donut Chart -->

                    </div>
                </div>
            </div>

        </div>
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
