<div>
        <div class="col-sm-6">
            <label for="date" class="form-label">Tanggal<span class="text-danger">*</span></label>
            <input type="date" wire:model='date' name="date" class="form-control @error('date') is-invalid @enderror"
                id="date" readonly>
            @error('date')
                <p class="text-danger text-xs mt-2">
                    {{ $message }}
                </p>
            @enderror
        </div>
        <div class="col-sm-6">
            <label for="code_outbound" class="form-label">Code Outbound<span class="text-danger">*</span></label>
            <input type="text" wire:model='code_outbound' name="code_outbound"
                class="form-control @error('code_outbound') is-invalid @enderror" id="code_outbound" readonly>
            @error('code_outbound')
                <p class="text-danger text-xs mt-2">
                    {{ $message }}
                </p>
            @enderror
        </div>
        <div class="col-12">
            <label for="project_id" class="form-label ">Project<span class="text-danger">*</span></label>
            <select id="project_id" class="form-select select2 @error('project_id')  is-invalid @enderror" wire:model='project_id'>
                <option disabled selected value="">Select a project...</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}">
                        {{ $project->code }} | {{ $project->name }}
                    </option>
                @endforeach
            </select>
            @error('project_id')
                <p class="text-danger text-xs mt-2">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="col-6">
            <label class="form-label">Category</label>
            <select id="categoryId"  wire:model.live="selectedCategory" class="form-select select2">
                <option value="" selected disabled>Choose...</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-6">
            <label class="form-label">Goods</label>
            <select id="goodsId" wire:model.live="selectedGoods" class="form-select select2">
                <option value="" selected disabled>Choose...</option>

            </select>
        </div>

        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-borderless" id="request-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th width="10%">Quantity</th>
                            <th width="15%">Price</th>
                            <th width="10%">Unit</th>
                            <th width="15%">Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr></tr>
                        <td colspan="4"></td>
                        <td>Total</td>
                        <td><input type="number" name="total_price" class="form-control" id="total_price" readonly>
                        </td>
                        <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="col-md-4">
            <label for="payment" class="form-label ">Payment<span class="text-danger">*</span></label>
            <select id="payment" name="payment" class="form-select select2 @error('payment')  is-invalid @enderror"
                required wire:model='payment'>
                <option value="" selected disabled>Choose...</option>
                <option value="Full Payment">Full Payment</option>
                <option value="Down Payment">Down Payment</option>
            </select>
            @error('payment')
                <p class="text-danger text-xs mt-2">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>

{{-- @script
    <script>
        const selectCategory = document.getElementById('categoryId');
        const selectGoods = document.getElementById('goodsId');

        const populateGoods = (categoryId, categories) => {
            const selectedCategory = categories.find(category => category.id == categoryId);

            if (selectedCategory) {
                selectGoods.innerHTML = '<option value="" selected disabled>Choose...</option>';

                selectedCategory.goods.forEach(good => {
                    const option = document.createElement('option');
                    option.value = good.id;
                    option.setAttribute('data-code', good.code);
                    option.setAttribute('data-name', good.name);
                    option.setAttribute('data-price', good.price);
                    option.setAttribute('data-unit-symbol', good.unit?.symbol || '');
                    option.text = `${good.code} | ${good.name}`;
                    selectGoods.appendChild(option);
                });
            } else {
                selectGoods.innerHTML = '<option value="" selected disabled>Choose...</option>';
            }
        };
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: 'Choose...'
            });
        })
    </script>
@endscript --}}

