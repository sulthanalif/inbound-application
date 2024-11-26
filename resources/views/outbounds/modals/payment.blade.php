<div class="modal fade" id="basicModal2" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Total Payment: Rp.{{ number_format($outbound->total_price, 0, ',', '.') }}
                    <br>
                    {{-- <small>Remaining Payment: Rp.{{ number_format($outbound->payments->sum('remaining'), 0, ',', '.') }}</small> --}}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('payment.outbound') }}" method="POST" id="paymentForm"
                enctype="multipart/form-data">
                @csrf
                {{-- @method('PUT') --}}
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <input type="hidden" name="outbound_id" value="{{ $outbound->id }}">

                        @if ($outbound->payment == 'Down Payment')
                            <div class="col-12">
                                <label for="paid" class="form-label">Paid <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('paid') is-invalid @enderror"
                                    name="paid" id="paid" max="{{ $outbound->payments->sum('remaining') ?? $outbound->total_price }}" required>
                            </div>
                        @endif
                        <div class="col-md-12 mt-2">
                            <label for="payment_method" class="form-label ">Method<span
                                    class="text-danger">*</span></label>
                            <select id="payment_method" name="payment_method"
                                class="form-select @error('payment_method')  is-invalid @enderror" required>
                                <option value="" selected disabled>Choose...</option>
                                <option value="Cash">Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                            </select>
                        </div>

                        <div class="col-md-12 mt-2" id="bankSelect" style="display: none">
                            <label for="bank" class="form-label ">Bank<span class="text-danger">*</span></label>
                            <select id="bank" name="bank"
                                class="form-select @error('bank')  is-invalid @enderror">
                                <option value="" selected disabled>Choose...</option>
                                <option value="BCA">BCA</option>
                                <option value="Bank Mandiri">Bank Mandiri</option>
                                <option value="BRI">BRI</option>
                                <option value="BNI">BNI</option>
                            </select>
                        </div>

                        <div class="col-12 mt-2">
                            <label for="image" class="form-label">Proof of payment <span
                                    class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="image" id="image"
                                onchange="previewImage(this)" required>
                            <span class="text-danger" style="font-size: 10px">*Max 2MB</span>
                            {{-- <img src="" id="previewImage" style="max-width: 150px; margin-top: 10px"> --}}
                            <div id="img-preview" class="hidden mt-2 text-center"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="{{ route('outbounds.show', $outbound) }}" class="btn btn-secondary">Reset</a>
                    <button type="submit" class="btn btn-primary">Pay</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- <script>
    function previewImage() {
        const image = document.querySelector('#image');
        const previewImage = document.querySelector('#previewImage');
        const file = image.files[0];
        const reader = new FileReader();
        reader.onloadend = function() {
            previewImage.src = reader.result;
        }
        if(file) {
            reader.readAsDataURL(file);
        } else {
            previewImage.src = "";
        }
    }
</script> --}}

@push('scripts')
    <script>
        function previewImage(input) {
            const file = input.files[0];
            const preview = document.getElementById('img-preview');

            if (file.size > 2 * 1024 * 1024) {
                alert("Ukuran gambar lebih dari 2MB. Silahkan pilih gambar yang lebih kecil");
                preview.innerHTML = '';
                preview.classList.add('hidden');
                input.value = '';
                return;
            }

            const allowedExtensions = ['jpg', 'jpeg', 'png'];
            const extension = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(extension)) {
                alert("Hanya file dengan tipe (jpg, jpeg, png) yang diperbolehkan!!");
                preview.innerHTML = '';
                preview.classList.add('hidden');
                input.value = '';
                return;
            }

            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                preview.innerHTML = '';
                preview.classList.remove('hidden');
                img.classList.add('img-fluid', 'mt-2');
                img.style.maxWidth = '450px';
                preview.appendChild(img);
            };

            reader.readAsDataURL(file);
        }

        document.getElementById('payment_method').addEventListener('change', function() {
            const bank = document.getElementById('bankSelect');
            // const label = document.querySelector('.form-check-label');

            if (this.value === 'Cash') {
                bank.style.display = 'none';
            } else {
                bank.style.display = 'block';
                bank.required = true;
            }
        });
    </script>
@endpush
