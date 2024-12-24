@extends('layouts.app')

@section('title', 'Return')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <h5 class="card-title">Multi Columns Form</h5> --}}

                        <!-- Multi Columns Form -->
                        <form class="row g-3 mt-1" id="requestForm" method="POST" action="{{ route('projects.storeReturn', $project) }}">
                            @csrf
                            <div class="col-6">
                                <label for="date" class="form-label">Tanggal<span class="text-danger">*</span></label>
                                <input type="date" value="{{ now()->format('Y-m-d') }}" name="date"
                                    class="form-control @error('date') is-invalid @enderror" id="date" readonly>
                                @error('date')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-6">
                                <label for="code" class="form-label">Inbound Code<span class="text-danger">*</span></label>
                                <input type="text" value="{{ $code }}" name="code"
                                    class="form-control @error('code') is-invalid @enderror" id="code" readonly>
                                @error('code')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">Description<span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description" cols="30" rows="3"></textarea>
                            </div>
                            <div class="col-12">
                                <label for="outbound_id" class="form-label">Outbound<span
                                        class="text-danger">*</span></label>
                                <select id="outbound_id" name="outbound_id"
                                    class="form-select select2" required onchange="getItems(this)">
                                    <option value="" selected disabled>Choose...</option>
                                    @foreach ($project->outbounds as $outbound)
                                       @if ($outbound->items->pluck('goods.type')->contains('Rentable') && $outbound->status == 'Success' && $outbound->is_return == false)
                                       <option value="{{ $outbound->id }}" data-items="{{ json_encode($outbound->items->load('goods.area.warehouse')) }}">
                                        {{ Carbon\Carbon::parse($outbound->date)->format('d F Y') }} | {{ $outbound->code }}</option>
                                       @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-borderless" id="return-table">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Quantity</th>
                                                <th>Warehouse</th>
                                                {{-- <th>Action</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody id="return-table-body" style="display: none;">

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Choose..',
                theme: 'bootstrap4',
            });
        })




        function getItems(el) {
            var items = JSON.parse(el.options[el.selectedIndex].getAttribute('data-items'));
            // console.log(items[0]);
            var html = '';
            items.forEach(item => {
                if (item.goods.type == 'Rentable') {
                    html += `<tr>
                            <td>${item.goods.code}</td>
                            <td>${item.goods.name}</td>
                            <td>${item.qty}</td>
                            <td>${item.goods.area.warehouse.name}</td>

                        </tr>`;
                }
            });

            document.getElementById('return-table-body').innerHTML = html;
            document.getElementById('return-table-body').style.display = 'table-row-group';
        }


    </script>
@endpush

