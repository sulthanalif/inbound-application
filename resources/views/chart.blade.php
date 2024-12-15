@hasrole('Super Admin|Admin Warehouse|Head warehouse')
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
                                        data: {{ json_encode($chart_transaction_amount['outbound']) }}
                                    }, {
                                        name: 'Inbound',
                                        data: {{ json_encode($chart_transaction_amount['inbound']) }}
                                    }, {
                                        name: 'Return',
                                        data: {{ json_encode($chart_transaction_amount['return']) }}
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
                                            {{ request()->filter_year == $i ? 'selected' : '' }}>
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
        @endhasrole
