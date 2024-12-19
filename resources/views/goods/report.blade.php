@extends('layouts.app')

@section('title', 'Report Goods')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">Report Goods</h5>

            <div class="d-flex justify-content-end align-items-center">
                <select id="filter_month" class="form-select form-select-sm me-2">
                    <option value="all" {{ request('filter_month') === null ? 'selected' : '' }}>All</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('filter_month') == $i ? 'selected' : '' }}>{{ \Carbon\Carbon::create(2022, $i, 1)->format('F') }}</option>
                    @endfor
                </select>

                <select id="filter_year" class="form-select form-select-sm">
                    {{-- <option value="all">All</option> --}}
                    @for ($i = 2022; $i <= date('Y'); $i++)
                        <option value="{{ $i }}" {{ request('filter_year') == $i || (request('filter_year') === null && $i == date('Y')) ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
          </div>


          <!-- Default Table -->
          <table class="table" id="report-table">
            <thead>
              <tr>
                <th scope="col">No</th>
                <th scope="col">Code</th>
                <th scope="col">Name</th>
                <th scope="col">Inbound</th>
                <th scope="col">Outbound</th>
                <th scope="col">Type</th>
                {{-- <th scope="col" style="text-align: center;">Action</th> --}}
              </tr>
            </thead>
            <tbody>
                @foreach ($datas as $item)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item['code'] }}</td>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['goodsInboundCount'] }}</td>
                        <td>{{ $item['goodsOutboundCount'] }}</td>
                        <td>{{ $item['type'] }}</td>

                        {{-- <td align="center">
                            <a href="{{ route('goods.edit', $item) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-fill"></i></a>
                            <a href="{{ route('goods.destroy', $item) }}" class="btn btn-danger btn-sm" data-confirm-delete="true"><i class="bi bi-trash-fill"></i></a>
                        </td> --}}
                    </tr>
                @endforeach
            </tbody>
          </table>
        </div>
      </div>
</section>
@endsection

@push('scripts')
    <script>
        $('#filter_month').on('change', function() {
            var month = $(this).val();
            var currentParams = new URLSearchParams(window.location.search);
            if (month === 'all') {
                currentParams.delete('filter_month');
            } else {
                currentParams.set('filter_month', month);
            }
            window.location.href = "{{ route('goods.reportGoods') }}" + '?' + currentParams.toString();
        });

        $('#filter_year').on('change', function() {
            var year = $(this).val();
            var currentParams = new URLSearchParams(window.location.search);
            if (year === 'all') {
                currentParams.delete('filter_year');
            } else {
                currentParams.set('filter_year', year);
            }
            window.location.href = "{{ route('goods.reportGoods') }}" + '?' + currentParams.toString();
        });

         // DataTables initialisation
         var table = $('#report-table').DataTable({
            layout: {
                top1Start: {
                    buttons: [
                        'copy',
                        {
                            extend: 'csv',
                            title: 'Report Goods',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4]
                            }
                        },
                        {
                            extend: 'excel',
                            title: 'Report Goods',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4]
                            }
                        },
                        {
                            extend: 'pdf',
                            title: 'Report Goods',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4]
                            }
                        },
                        {
                            extend: 'print',
                            title: 'Report Goods',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4]
                            }
                        },

                    ]
                }
            },
        });
    </script>
@endpush
