<div class="modal fade" id="nextModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Next Project
                    <br>
                    {{-- <small>Remaining Payment: Rp.{{ number_format($outbound->payments->sum('remaining'), 0, ',', '.') }}</small> --}}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('projects.nextProject', $project) }}" method="POST" id="nextForm">
                @csrf
                <div class="modal-body">
                    <div class="row justify-content-center">
                        {{-- <input type="hidden" name="inbound_id" value="{{ $inbound->id }}"> --}}

                        <div class="col-12">
                            <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" value="{{ now()->format('Y-m-d') }}" name="date"
                                class="form-control @error('date') is-invalid @enderror" id="date">
                        </div>

                        <div class="col-12 mt-3">
                            <label for="project_id" class="form-label">Project <span class="text-danger">*</span></label>
                            <select name="project_id" id="project_id" class="form-select select2" onchange="changeProject(this.value)" required>
                                <option value="" selected disabled>Choose...</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->code }} | {{ $project->name }}
                                    </option>
                                @endforeach
                                <option value="new">New Project</option>
                            </select>
                        </div>

                        <div style="display: none" id="newProject">
                            <div class="col-12 mt-3">
                                <label for="code_new" class="form-label">Project Code <span class="text-danger">*</span></label>
                                <input type="text"  name="code_new"
                                    class="form-control @error('code_new') is-invalid @enderror" id="code_new">
                            </div>
                            <div class="col-12 mt-3">
                                <label for="name_new" class="form-label">Project Name <span class="text-danger">*</span></label>
                                <input type="text"  name="name_new"
                                    class="form-control @error('name_new') is-invalid @enderror" id="name_new">
                            </div>
                            <div class="col-12 mt-3">
                                <label for="address_new" class="form-label">Project Address <span class="text-danger">*</span></label>
                                <textarea name="address_new" class="form-control @error('address_new') is-invalid @enderror" id="address_new" cols="30" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Item Name</th>
                                        <th scope="col">Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($outboundGoods as $item)
                                        @if ($item['type'] == 'Rentable')
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item['name'] }}</td>
                                                <td>{{ $item['qty'] }}{{ $item['symbol'] }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

