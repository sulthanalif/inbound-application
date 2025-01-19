@extends('layouts.app')

@section('title', 'Create Warehouse')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <h5 class="card-title">Multi Columns Form</h5> --}}

                        <!-- Multi Columns Form -->
                        <form class="row g-3 mt-1" method="POST" action="{{ route('warehouses.store') }}" id="warehouseForm">
                            @csrf
                            <div class="col-md-6">
                                <label for="code" class="form-label">Code<span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" id="code" required>
                                @error('code')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name<span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" required>
                                @error('name')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="address" class="form-label">Address<span
                                        class="text-danger">*</span></label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address" rows="3"  required></textarea>
                                @error('address')
                                    <p class="text-danger text-xs mt-2">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="col-6" id="admin">
                                <div class="">
                                    <label for="item-select" class="form-label">Select Admin</label>
                                    <select id="item-select" name="admin_id" class="form-select select2"
                                        onchange="addAdmin(this)">
                                        <option value="" selected disabled>Choose...</option>
                                        @foreach ($admins as $admin)
                                            <option value="{{ $admin->id }}" data-name="{{ $admin->name }}" data-email="{{ $admin->email }}">{{ $admin->name }}|{{ $admin->email }}</option>
                                        @endforeach
                                    </select>

                                    {{-- <input type="text" name="other_category" id="other_category" style="display: none"> --}}
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-borderless" id="admin-table">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

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
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        const warehouseForm = document.getElementById('warehouseForm');
        const adminTable = document.getElementById('admin-table');
        const data = [];

        const addAdmin = (selectElement) => {
            const selectedAdmin = selectElement.options[selectElement.selectedIndex];
            const adminId = selectedAdmin.value;
            const adminName = selectedAdmin.getAttribute('data-name');
            const adminEmail = selectedAdmin.getAttribute('data-email');

            if (!adminId) return;

            const existingRow = document.querySelector(`tr[data-admin-id="${adminId}"]`);

            if (!existingRow) {
                const newRow = `
                    <tr data-admin-id="${adminId}">
                        <td>${adminName}</td>
                        <td>${adminEmail}</td>
                        <td><button type="button" class="btn btn-danger" onclick="removeRow(this)">Remove</button></td>
                    </tr>
                `;
                adminTable.insertAdjacentHTML('beforeend', newRow);
                data.push({
                    admin_id: adminId,
                });
            }

            $('.select2').select2({
                placeholder: 'Choose..',
                theme: 'bootstrap4',
            });
        };

        const removeRow = (el) => {
            const row = el.closest('tr');
            const adminId = row.getAttribute('data-admin-id');
            const index = data.findIndex(item => item.adminId === adminId);
            data.splice(index, 1);
            row.remove();
            calculateTotal();
        };


        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Choose..',
                theme: 'bootstrap4',
            });
        });

        // Add a hidden input to the form to send the data array
        warehouseForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'data';
            hiddenInput.value = JSON.stringify(data);
            warehouseForm.appendChild(hiddenInput);
            warehouseForm.submit();
        });

    </script>
@endpush
