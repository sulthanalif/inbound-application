@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <section class="section dashboard">
        <div class="row">

            <div class="col-lg-6">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Line Chart</h5>

                    <!-- Line Chart -->
                    <div id="lineChart"></div>

                    <script>
                      document.addEventListener("DOMContentLoaded", () => {
                        new ApexCharts(document.querySelector("#lineChart"), {
                          series: [{
                            name: "Desktops",
                            data: [10, 41, 35, 51, 49, 62, 69, 91, 148]
                          }],
                          chart: {
                            height: 350,
                            type: 'line',
                            zoom: {
                              enabled: false
                            }
                          },
                          dataLabels: {
                            enabled: false
                          },
                          stroke: {
                            curve: 'straight'
                          },
                          grid: {
                            row: {
                              colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                              opacity: 0.5
                            },
                          },
                          xaxis: {
                            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                          }
                        }).render();
                      });
                    </script>
                    <!-- End Line Chart -->

                  </div>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Pie Chart</h5>

                    <!-- Pie Chart -->
                    <div id="pieChart"></div>

                    <script>
                      document.addEventListener("DOMContentLoaded", () => {
                        new ApexCharts(document.querySelector("#pieChart"), {
                          series: [{{ $count_inbound }}, {{ $count_outbound }}],
                          chart: {
                            height: 350,
                            type: 'pie',
                            toolbar: {
                              show: true
                            }
                          },
                          labels: ['Inbound', 'Outbound']
                        }).render();
                      });
                    </script>
                    <!-- End Pie Chart -->

                  </div>
                </div>
              </div>

        </div>
    </section>
@endsection
