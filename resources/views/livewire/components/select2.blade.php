<div class="col-6" wire:ignore>
    <label class="form-label">{{ $name }}</label>
    <select id="{{ $name }}" class="" wire:model='value' class="form-select"  autocomplete="off">
    <option value="">Choose...</option>
    @foreach ($options as $option)
        <option value="{{ $option->id }}">{{ $option->name }}</option>
    @endforeach
    </select>
</div>

@assets
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.1/dist/js/tom-select.complete.min.js"></script>
@endassets

@script
    <script>
        new TomSelect("#{{ $name }}", {
            create: true,
            sortField: {
                field: "text",
                direction: "asc"
            },
        });
    </script>
@endscript
