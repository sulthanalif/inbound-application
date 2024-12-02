<div class="modal fade" id="resendModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Resend Items
                    <br>
                    {{-- <small>Remaining Payment: Rp.{{ number_format($outbound->payments->sum('remaining'), 0, ',', '.') }}</small> --}}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('inbounds.resendItems') }}" method="POST" id="resendForm">
                @csrf
                {{-- @method('PUT') --}}
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <input type="hidden" name="inbound_id" value="{{ $inbound->id }}">

                        <div class="col-12">
                            <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" value="{{ now()->format('Y-m-d') }}" name="date"
                                class="form-control @error('date') is-invalid @enderror" id="date">
                        </div>

                        <div class="col-12 mt-3">
                            <table class="table">
                                <thead></thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Item Name</th>
                                        <th scope="col">Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inbound->items as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->goods->name }}</td>
                                            <td>{{ $item->qty }}{{ $item->goods->unit->symbol }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Resend</button>
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

