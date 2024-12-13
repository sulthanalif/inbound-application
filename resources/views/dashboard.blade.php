@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section class="section dashboard">
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
                                        data: [35, 41, 36, 26, 45, 48, 52, 53, 41, 85, 101, 98                                  ]
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
                                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
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
                                                return  val
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
                        <h5 class="card-title">Comparison Type Items</h5>

                        <!-- Donut Chart -->
                        <div id="donutChart"></div>

                        <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                new ApexCharts(document.querySelector("#donutChart"), {
                                    series: [44, 55],
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
