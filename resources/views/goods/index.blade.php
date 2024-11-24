@extends('layouts.app')

@section('title', 'Goods')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-body">
              <h5 class="card-title">Goods Data</h5>


              <div class="flex">
                <a href="{{ route('goods.create') }}" class="btn btn-primary btn-sm mb-3">Create</a>
              </div>

              <!-- Default Table -->
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Code</th>
                    <th scope="col">Name</th>
                    <th scope="col">Vendor</th>
                    <th scope="col">Price</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Category</th>
                    <th scope="col">Warehouse</th>
                    {{-- <th scope="col">User</th> --}}
                    <th scope="col" style="text-align: center;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($goods->count() > 0)
                    @foreach ($goods as $item)
                        <tr>
                            <th scope="row">{{ ($goods->currentPage() - 1) * $goods->perPage() + $loop->iteration }}</th>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->vendor->name }}</td>
                            <td>Rp.{{ number_format($item->price, 0, ',', '.') }}</td>
                            <td>{{ $item->qty }}{{ $item->unit->symbol }}</td>
                            <td>{{ $item->category->name }}</td>
                            <td>{{ $item->warehouse->name }}</td>
                            {{-- <td>{{ $item->user->name }}</td> --}}

                            <td align="center">
                                <a href="{{ route('goods.edit', $item) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-fill"></i></a>
                                <a href="{{ route('goods.destroy', $item) }}" class="btn btn-danger btn-sm" data-confirm-delete="true"><i class="bi bi-trash-fill"></i></a>
                            </td>
                        </tr>
                    @endforeach
                  @else
                    <tr>
                        <td colspan="10" align="center">No Data</td>
                    </tr>
                  @endif
                </tbody>
              </table>
              <!-- End Default Table Example -->
              {{ $goods->links('layouts.paginate') }}

            </div>
          </div>
    </section>
@endsection
