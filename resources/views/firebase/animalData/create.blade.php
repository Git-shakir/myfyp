{{-- myproject\resources\views\firebase\animalData\create.blade.php --}}

@extends('firebase.app')

@section('content')
    <style>
        /* Make the placeholder text red for all input fields */
        .form-control::placeholder {
            color: #bababa;
            /* Red for placeholder text */
            opacity: 1;
            /* Ensure placeholder text is visible */
        }
    </style>


    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Add Animal Data
                            <a href="{{ route('list-animalData') }}" class="btn btn-sm btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <form action="{{ route('add-animalData-post') }}" method="POST" novalidate>
                            @csrf

                            <!-- Auto-fill UID if session exists -->
                            <input type="hidden" name="uid" value="{{ session('new_uid') ?? request('uid') }}">

                            <div class="form-group">
                                <label>Livestock ID</label>
                                <input type="text" name="animalid" class="form-control" value="{{ old('animalid') }}"
                                    placeholder="100">
                                @error('animalid')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Species</label>
                                <input type="text" name="species" class="form-control" value="{{ old('species') }}"
                                    placeholder="Sheep">
                                @error('species')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Breed</label>
                                <input type="text" name="breed" class="form-control" value="{{ old('breed') }}"
                                    placeholder="Merino">
                                @error('breed')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Birth Date</label>
                                <div class="input-group">
                                    <input type="text" name="bdate" id="bdate" class="form-control"
                                        value="{{ old('bdate') }}" placeholder="DD-MM-YYYY">
                                    <span class="input-group-text" id="calendar-icon">
                                        <i class="bi bi-calendar"></i> <!-- Calendar icon -->
                                    </span>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>Sex</label>
                                <select name="sex" id="sex" class="form-control" style="appearance: auto;">
                                    <option value="" disabled selected>Select Sex</option>
                                    <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('sex')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Weight (kg)</label>
                                <input type="text" name="weight" class="form-control" value="{{ old('weight') }}"
                                    placeholder="120">
                                @error('weight')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Manager Name</label>
                                <input type="text" name="mname" class="form-control" value="{{ old('mname') }}"
                                    placeholder="Ahmad">
                                @error('mname')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Manager Phone</label>
                                <input type="text" name="mphone" class="form-control" value="{{ old('mphone') }}"
                                    placeholder="0123456789">
                                @error('mphone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Farm Location</label>
                                <input type="text" name="flocation" class="form-control" value="{{ old('flocation') }}"
                                    placeholder="Ladang UPM">
                                @error('flocation')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Temperature</label>
                                <input type="text" name="temperature" class="form-control"
                                    value="{{ old('temperature') }}" placeholder="38.5°C">
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

                            <script>
                                $(document).ready(function() {
                                    $('input[name="bdate"]').datepicker({
                                        format: 'dd-mm-yyyy',
                                        autoclose: true,
                                        todayHighlight: true,
                                    });

                                    $('#calendar-icon').on('click', function() {
                                        $('#bdate').focus(); // Focus on the input field to open the datepicker
                                    });

                                    // Calculate age when the birth date changes
                                    $('#bdate').on('change', function() {
                                        const birthDate = $(this).val(); // Get the entered birth date
                                        const ageDetails = calculateAgeDetails(birthDate);
                                        if (ageDetails !== null) {
                                            $('#age').val(ageDetails); // Populate the Age field
                                        } else {
                                            $('#age').val(''); // Clear the field if the date is invalid
                                        }
                                    });

                                    // Function to calculate age in years, months, and days
                                    function calculateAgeDetails(birthDate) {
                                        if (!birthDate) return null;

                                        // Parse the birth date
                                        const [day, month, year] = birthDate.split('-');
                                        const birthDateObj = new Date(year, month - 1, day);

                                        // Ensure the date is valid
                                        if (isNaN(birthDateObj)) return null;

                                        const today = new Date();
                                        let years = today.getFullYear() - birthDateObj.getFullYear();
                                        let months = today.getMonth() - birthDateObj.getMonth();
                                        let days = today.getDate() - birthDateObj.getDate();

                                        // Adjust for negative days
                                        if (days < 0) {
                                            months -= 1;
                                            const prevMonth = new Date(today.getFullYear(), today.getMonth(), 0);
                                            days += prevMonth.getDate();
                                        }

                                        // Adjust for negative months
                                        if (months < 0) {
                                            years -= 1;
                                            months += 12;
                                        }

                                        // Return the formatted age
                                        return `${years} years, ${months} months, ${days} days`;
                                    }
                                });
                            </script>


                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
