@extends('firebase.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Livestock Checkup Form
                            <a href="{{ route('list-animalData') }}" class="btn btn-sm btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <form action="{{ route('add-checkup-post') }}" method="POST" novalidate>
                            @csrf

                            <input type="hidden" name="livestockUid" value="{{ $livestockUid }}">

                            <div class="form-group mb-3">
                                <label>Weight (kg)</label>
                                <input type="text" name="weight" class="form-control" value="{{ old('weight', $latestWeight) }}"
                                    placeholder="120.0">
                                @error('weight')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Temperature (°C)</label>
                                <input type="text" name="temperature" class="form-control"
                                    value="{{ old('temperature') }}" placeholder="38.5">
                                @error('temperature')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>General Appearance</label>
                                <select name="genApp" id="genApp" class="form-control" style="appearance: auto;">
                                    <option value="" disabled selected>Select One</option>
                                    <option value="Normal" {{ old('genApp') == 'Normal' ? 'selected' : '' }}>Normal
                                    </option>
                                    <option value="Abnormal" {{ old('genApp') == 'Abnormal' ? 'selected' : '' }}>Abnormal
                                    </option>
                                </select>
                                @error('genApp')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Mucous Membrane</label>
                                <select name="mucous" id="mucous" class="form-control" style="appearance: auto;">
                                    <option value="" disabled selected>Select One</option>
                                    <option value="Normal" {{ old('mucous') == 'Normal' ? 'selected' : '' }}>Normal
                                    </option>
                                    <option value="Abnormal" {{ old('mucous') == 'Abnormal' ? 'selected' : '' }}>Abnormal
                                    </option>
                                </select>
                                @error('mucous')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Integument</label>
                                <select name="integument" id="integument" class="form-control"
                                    style="appearance: auto;">
                                    <option value="" disabled selected>Select One</option>
                                    <option value="Normal" {{ old('integument') == 'Normal' ? 'selected' : '' }}>Normal
                                    </option>
                                    <option value="Abnormal" {{ old('integument') == 'Abnormal' ? 'selected' : '' }}>
                                        Abnormal</option>
                                </select>
                                @error('integument')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Nervous</label>
                                <select name="nervous" id="nervous" class="form-control" style="appearance: auto;">
                                    <option value="" disabled selected>Select One</option>
                                    <option value="Normal" {{ old('nervous') == 'Normal' ? 'selected' : '' }}>Normal
                                    </option>
                                    <option value="Abnormal" {{ old('nervous') == 'Abnormal' ? 'selected' : '' }}>Abnormal
                                    </option>
                                </select>
                                @error('nervous')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Musculoskeletal</label>
                                <select name="musculoskeletal" id="musculoskeletal" class="form-control"
                                    style="appearance: auto;">
                                    <option value="" disabled selected>Select One</option>
                                    <option value="Normal" {{ old('musculoskeletal') == 'Normal' ? 'selected' : '' }}>
                                        Normal</option>
                                    <option value="Abnormal" {{ old('musculoskeletal') == 'Abnormal' ? 'selected' : '' }}>
                                        Abnormal</option>
                                </select>
                                @error('musculoskeletal')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Eyes</label>
                                <select name="eyes" id="eyes" class="form-control" style="appearance: auto;">
                                    <option value="" disabled selected>Select One</option>
                                    <option value="Normal" {{ old('eyes') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="Abnormal" {{ old('eyes') == 'Abnormal' ? 'selected' : '' }}>Abnormal
                                    </option>
                                </select>
                                @error('eyes')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Ears</label>
                                <select name="ears" id="ears" class="form-control" style="appearance: auto;">
                                    <option value="" disabled selected>Select One</option>
                                    <option value="Normal" {{ old('ears') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="Abnormal" {{ old('ears') == 'Abnormal' ? 'selected' : '' }}>Abnormal
                                    </option>
                                </select>
                                @error('ears')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Gastrointestinal</label>
                                <select name="gastrointestinal" id="gastrointestinal" class="form-control"
                                    style="appearance: auto;">
                                    <option value="" disabled selected>Select One</option>
                                    <option value="Normal" {{ old('gastrointestinal') == 'Normal' ? 'selected' : '' }}>
                                        Normal</option>
                                    <option value="Abnormal"
                                        {{ old('gastrointestinal') == 'Abnormal' ? 'selected' : '' }}>Abnormal</option>
                                </select>
                                @error('gastrointestinal')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Respiratory</label>
                                <select name="respiratory" id="respiratory" class="form-control"
                                    style="appearance: auto;">
                                    <option value="" disabled selected>Select One</option>
                                    <option value="Normal" {{ old('respiratory') == 'Normal' ? 'selected' : '' }}>Normal
                                    </option>
                                    <option value="Abnormal" {{ old('respiratory') == 'Abnormal' ? 'selected' : '' }}>
                                        Abnormal</option>
                                </select>
                                @error('respiratory')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Cardiovascular</label>
                                <select name="cardiovascular" id="cardiovascular" class="form-control"
                                    style="appearance: auto;">
                                    <option value="" disabled selected>Select One</option>
                                    <option value="Normal" {{ old('cardiovascular') == 'Normal' ? 'selected' : '' }}>
                                        Normal</option>
                                    <option value="Abnormal" {{ old('cardiovascular') == 'Abnormal' ? 'selected' : '' }}>
                                        Abnormal</option>
                                </select>
                                @error('cardiovascular')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Reproductive</label>
                                <select name="reproductive" id="reproductive" class="form-control"
                                    style="appearance: auto;">
                                    <option value="" disabled selected>Select One</option>
                                    <option value="Normal" {{ old('reproductive') == 'Normal' ? 'selected' : '' }}>Normal
                                    </option>
                                    <option value="Abnormal" {{ old('reproductive') == 'Abnormal' ? 'selected' : '' }}>
                                        Abnormal</option>
                                </select>
                                @error('reproductive')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Urinary</label>
                                <select name="urinary" id="urinary" class="form-control" style="appearance: auto;">
                                    <option value="" disabled selected>Select One</option>
                                    <option value="Normal" {{ old('urinary') == 'Normal' ? 'selected' : '' }}>Normal
                                    </option>
                                    <option value="Abnormal" {{ old('urinary') == 'Abnormal' ? 'selected' : '' }}>Abnormal
                                    </option>
                                </select>
                                @error('urinary')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Mammary Gland</label>
                                <select name="mGland" id="mGland" class="form-control" style="appearance: auto;">
                                    <option value="" disabled selected>Select One</option>
                                    <option value="Normal" {{ old('mGland') == 'Normal' ? 'selected' : '' }}>Normal
                                    </option>
                                    <option value="Abnormal" {{ old('mGland') == 'Abnormal' ? 'selected' : '' }}>Abnormal
                                    </option>
                                </select>
                                @error('mGland')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Lymphatic</label>
                                <select name="lymphatic" id="lymphatic" class="form-control" style="appearance: auto;">
                                    <option value="" disabled selected>Select One</option>
                                    <option value="Normal" {{ old('lymphatic') == 'Normal' ? 'selected' : '' }}>Normal
                                    </option>
                                    <option value="Abnormal" {{ old('lymphatic') == 'Abnormal' ? 'selected' : '' }}>
                                        Abnormal</option>
                                </select>
                                @error('lymphatic')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
