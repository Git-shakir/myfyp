@extends('firebase.app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                @if (session('status'))
                    <h4 class="alert alert-warning mb-2">{{ session('status') }}</h4>
                @endif

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Total Livestock: {{ $total_animalDatas ?? '0' }}</h4>
                        <form action="{{ route('reports') }}" method="GET" class="d-flex">
                            <!-- Search Box -->
                            <input type="text" name="search" class="form-control me-2"
                                placeholder="Search by ID, Species, etc." value="{{ request()->get('search') }}">

                            <!-- Species Breakdown -->
                            <select name="species" class="form-select me-2">
                                <option value="">Select Species</option>
                                <option value="Cattle" {{ request()->get('species') === 'Cattle' ? 'selected' : '' }}>Cattle
                                </option>
                                <option value="Sheep" {{ request()->get('species') === 'Sheep' ? 'selected' : '' }}>Sheep
                                </option>
                                <option value="Goat" {{ request()->get('species') === 'Goat' ? 'selected' : '' }}>Goat
                                </option>
                                <!-- Add other species as needed -->
                            </select>

                            <!-- Breed Breakdown -->
                            <select name="breed" class="form-select me-2">
                                <option value="">Select Breed</option>
                                <option value="Angus" {{ request()->get('breed') === 'Angus' ? 'selected' : '' }}>Angus
                                </option>
                                <option value="Holstein" {{ request()->get('breed') === 'Holstein' ? 'selected' : '' }}>
                                    Holstein</option>
                                <option value="Saanen" {{ request()->get('breed') === 'Saanen' ? 'selected' : '' }}>Saanen
                                </option>
                                <!-- Add other breeds as needed -->
                            </select>

                            <!-- Sex Dropdown -->
                            <select name="sex" class="form-select me-2">
                                <option value="">Select Sex</option>
                                <option value="Male" {{ request()->get('sex') === 'Male' ? 'selected' : '' }}>Male
                                </option>
                                <option value="Female" {{ request()->get('sex') === 'Female' ? 'selected' : '' }}>Female
                                </option>
                            </select>

                            <!-- Buttons -->
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="{{ route('generate-pdf', request()->all()) }}"
                                class="btn btn-success float-end">Download PDF</a>
                        </form>
                    </div>
                    <div class="card-body">
                        @if (empty($animalsData))
                            <div class="text-center p-3">
                                <h5>No results found</h5>
                                <p>Try searching with different keywords or filters.</p>
                            </div>
                        @else
                            <table class="table table-bordered">
                                <thead class="table table-primary">
                                    <tr>
                                        <th>Livestock ID</th>
                                        <th>Species</th>
                                        <th>Breed</th>
                                        <th>Birth Date</th>
                                        <th>Age</th>
                                        <th>Sex</th>
                                        <th>Weight (kg)</th>
                                        <th>Manager Name</th>
                                        <th>Manager Phone</th>
                                        <th>Checkup</th>
                                        <th>Data History</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($animalsData as $key => $item)
                                        <tr>
                                            <td>{{ $item['animalid'] }}</td>
                                            <td>{{ $item['species'] }}</td>
                                            <td>{{ $item['breed'] }}</td>
                                            <td>{{ $item['bdate'] }}</td>
                                            <td>{{ $item['age'] ?? 'N/A' }}</td>
                                            <td>{{ $item['sex'] }}</td>
                                            <td>{{ $item['weight'] }}</td>
                                            <td>{{ $item['mname'] }}</td>
                                            <td>{{ $item['mphone'] }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-info phyExamination-button"
                                                    data-animalid="{{ $key }}">Details</button>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary history-button"
                                                    data-animalid="{{ $item['animalid'] }}">View</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>


            </div>
        </div>
    </div>

    <!-- History Modal -->
    <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel">Data History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="historyModalBody">
                    <!-- History details will be dynamically loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Physical Examination Modal -->
    <div class="modal fade" id="phyExaminationModal" tabindex="-1" aria-labelledby="phyExaminationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="phyExaminationModalLabel">Physical Checkup Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Wrap content in table-responsive -->
                    <div class="table-responsive" id="phyExaminationModalBody">
                        <!-- Physical Examination details will be dynamically loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <script>
        // Attach click event to history buttons
        document.querySelectorAll('.history-button').forEach(button => {
            button.addEventListener('click', () => {
                const animalId = button.getAttribute('data-animalid');

                fetch(`/get-animal-history/${animalId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch history');
                        }
                        return response.json();
                    })
                    .then(data => {
                        const modalBody = document.getElementById('historyModalBody');
                        if (Object.keys(data).length === 0) {
                            modalBody.innerHTML = `<p>No history available for this livestock.</p>`;
                        } else {
                            modalBody.innerHTML = `<table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Timestamp</th>
                                        <th>Livestock ID</th>
                                        <th>Species</th>
                                        <th>Breed</th>
                                        <th>Age</th>
                                        <th>Sex</th>
                                        <th>Weight</th>
                                        <th>Location</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${Object.entries(data).map(([timestamp, history]) => `
                                                                                                                <tr>
                                                                                                                    <td>${timestamp}</td>
                                                                                                                    <td>${history.animalid}</td>
                                                                                                                    <td>${history.species}</td>
                                                                                                                    <td>${history.breed}</td>
                                                                                                                    <td>${history.age}</td>
                                                                                                                    <td>${history.sex}</td>
                                                                                                                    <td>${history.weight}</td>
                                                                                                                </tr>
                                                                                                            `).join('')}
                                </tbody>
                            </table>`;
                        }
                        const modal = new bootstrap.Modal(document.getElementById('historyModal'));
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Error fetching history:', error);
                        alert('Failed to fetch history.');
                    });
            });
        });
    </script>

    <script>
        document.querySelectorAll('.phyExamination-button').forEach(button => {
            button.addEventListener('click', () => {
                const livestockUid = button.getAttribute('data-animalid'); // Get the animal ID
                console.log("Fetching checkup data for animalId:", livestockUid);

                const modalBody = document.getElementById('phyExaminationModalBody');

                // Show loading state in the modal
                modalBody.innerHTML = '<p>Loading physical examination data...</p>';

                // Fetch the physical examination data for the animal
                fetch(`/get-checkup-data/${livestockUid }`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch checkup data');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log("Fetched Checkup Data:", data);

                        // Check if the data is empty
                        if (Object.keys(data).length === 0) {
                            modalBody.innerHTML =
                                '<p>This livestock has not undergone any physical examination yet.</p>';
                        } else {
                            // Build a table to display the data
                            modalBody.innerHTML = `
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Examined At</th>
                                <th>Temperature</th>
                                <th>General Appearance</th>
                                <th>Mucous Membrane</th>
                                <th>Integument</th>
                                <th>Nervous</th>
                                <th>Musculoskeletal</th>
                                <th>Eyes</th>
                                <th>Ears</th>
                                <th>Gastrointestinal</th>
                                <th>Respiratory</th>
                                <th>Cardiovascular</th>
                                <th>Reproductive</th>
                                <th>Urinary</th>
                                <th>Mammary Gland</th>
                                <th>Lymphatic</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${Object.keys(data).map(checkupId => {
                                const checkup = data[checkupId];
                                return `
                                                            <tr>
                                                                <td>${checkup.examined_at ?? 'N/A'}</td>
                                                                <td>${checkup.temperature ?? 'N/A'}</td>
                                                                <td>${checkup.genApp ?? 'N/A'}</td>
                                                                <td>${checkup.mucous ?? 'N/A'}</td>
                                                                <td>${checkup.integument ?? 'N/A'}</td>
                                                                <td>${checkup.nervous ?? 'N/A'}</td>
                                                                <td>${checkup.musculoskeletal ?? 'N/A'}</td>
                                                                <td>${checkup.eyes ?? 'N/A'}</td>
                                                                <td>${checkup.ears ?? 'N/A'}</td>
                                                                <td>${checkup.gastrointestinal ?? 'N/A'}</td>
                                                                <td>${checkup.respiratory ?? 'N/A'}</td>
                                                                <td>${checkup.cardiovascular ?? 'N/A'}</td>
                                                                <td>${checkup.reproductive ?? 'N/A'}</td>
                                                                <td>${checkup.urinary ?? 'N/A'}</td>
                                                                <td>${checkup.mGland ?? 'N/A'}</td>
                                                                <td>${checkup.lymphatic ?? 'N/A'}</td>
                                                            </tr>
                                                        `;
                            }).join('')}
                        </tbody>
                    </table>`;
                        }

                        // Show the modal
                        const modal = new bootstrap.Modal(document.getElementById(
                            'phyExaminationModal'));
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Error fetching checkup data:', error);
                        modalBody.innerHTML =
                            '<p class="text-danger">Failed to fetch checkup data.</p>';
                    });
            });
        });
    </script>

    <script>
        function showLogoutModal() {
            document.getElementById('logoutModal').style.display = 'block';
        }

        function hideLogoutModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }
    </script>

    <!-- Custom Styles -->
    <style>
        /* Custom modal background color */
        .custom-modal-bg {
            background-color: #b5c7d9;
            /* Beige color */
            color: #333;
            /* Text color */
            border-radius: 10px;
            /* Optional rounded corners */
        }

        /* Optional: Add padding or other styles */
        .custom-modal-bg .modal-header,
        .custom-modal-bg .modal-footer {
            border: none;
            /* Remove borders */
        }
    </style>


@endsection
