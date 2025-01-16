{{-- myproject\resources\views\firebase\animalData\edit.blade.php --}}

@extends('firebase.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Animal Data
                            <a href="{{ route('list-animalData') }}" class="btn btn-sm btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('update-animalData', ['id' => $uid]) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Add a hidden input for the scanned timestamp -->
                            <input type="hidden" name="scanned_timestamp" value="{{ $scannedTimestamp }}">

                            <div class="form-group mb-3">
                                <label>Livestock ID</label>
                                <input type="text" name="animalid" value="{{ $editdata['animalid'] }}"
                                    class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label>Species</label>
                                <input type="text" name="species" value="{{ $editdata['species'] }}"
                                    class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label>Breed</label>
                                <input type="text" name="breed" value="{{ $editdata['breed'] }}" class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label>Birth Date</label>
                                <input type="text" name="bdate" value="{{ $editdata['bdate'] }}" class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label>Age(Months)</label>
                                <input type="text" name="age" value="{{ $editdata['age'] }}" class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label>Sex</label>
                                <input type="text" name="sex" value="{{ $editdata['sex'] }}" class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label>Weight(kg)</label>
                                <input type="text" name="weight" value="{{ $editdata['weight'] }}"
                                    class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label>Manager Name</label>
                                <input type="text" name="mname" value="{{ $editdata['mname'] }}" class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label>Manager Phone</label>
                                <input type="text" name="mphone" value="{{ $editdata['mphone'] }}"
                                    class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <label>Farm Location</label>
                                <input type="text" name="flocation" value="{{ $editdata['flocation'] }}"
                                    class="form-control">
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

@endsection
