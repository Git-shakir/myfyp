@extends('firebase.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Livestock Form
                            <a href="{{ route('list-animalData') }}" class="btn btn-sm btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <form action="{{ route('update-animalData', ['livestockUid' => $livestockUid]) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Add a hidden input for the scanned timestamp -->
                            <input type="hidden" name="scanned_timestamp" value="{{ $scannedTimestamp }}">

                            <div class="form-group mb-3">
                                <label>Livestock ID</label>
                                <input type="text" name="animalid" class="form-control"
                                    value="{{ old('animalid', $editdata['animalid']) }}" placeholder="Enter Livestock ID"
                                    readonly>
                                @error('animalid')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Species</label>
                                <input type="text" name="species" class="form-control"
                                    value="{{ old('species', $editdata['species']) }}" placeholder="Enter Species">
                                @error('species')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Breed</label>
                                <input type="text" name="breed" class="form-control"
                                    value="{{ old('breed', $editdata['breed']) }}" placeholder="Enter Breed">
                                @error('breed')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Birth Date</label>
                                <input type="text" name="bdate" class="form-control"
                                    value="{{ old('bdate', $editdata['bdate']) }}" placeholder="DD-MM-YYYY">
                                @error('bdate')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Sex</label>
                                <select name="sex" class="form-control">
                                    <option value="" disabled>Select Sex</option>
                                    <option value="Male" {{ old('sex', $editdata['sex']) === 'Male' ? 'selected' : '' }}>
                                        Male</option>
                                    <option value="Female"
                                        {{ old('sex', $editdata['sex']) === 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('sex')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Manager Name</label>
                                <input type="text" name="mname" class="form-control"
                                    value="{{ old('mname', $editdata['mname']) }}" placeholder="Enter Manager Name">
                                @error('mname')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Manager Phone</label>
                                <input type="text" name="mphone" class="form-control"
                                    value="{{ old('mphone', $editdata['mphone']) }}" placeholder="Enter Manager Phone">
                                @error('mphone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>


                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- error submission modal --}}
    <div class="modal fade" id="validationModal" tabindex="-1" aria-labelledby="validationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="validationModalLabel">Form Submission Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Please fulfill all the required fields before submitting the form.</p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if ($errors->any())
                var validationModal = new bootstrap.Modal(document.getElementById('validationModal'));
                validationModal.show();
            @endif
        });
    </script>

    <style>
        .modal-content {
            border-radius: 10px;
        }

        .modal-header {
            background-color: #f8d7da;
            color: #721c24;
        }

        .modal-body {
            font-size: 16px;
        }
    </style>
@endsection
