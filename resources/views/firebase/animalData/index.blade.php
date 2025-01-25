{{-- myproject\resources\views\firebase\animalData\index.blade.php --}}

@extends('firebase.app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                @if (session('status'))
                    <h4 class="alert alert-warning mb-2">{{ session('status') }}</h4>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4>Total Livestock: {{ $total_animalDatas }}</h4>
                    </div>
                    <div class="card-body">
                        @if (empty($animalsData))
                            <div class="text-center p-3">
                                <h5>No Livestock Found</h5>
                                <p>Start by adding a new livestock record.</p>
                            </div>
                        @else
                        <div style="overflow-x: auto;">
                            <table class="table table-bordered">
                                <thead class="table table-primary">
                                    <tr>
                                        {{-- <th>Date</th> --}}
                                        <th>Livestock ID</th>
                                        <th>Species</th>
                                        <th>Breed</th>
                                        <th>Birth Date</th>
                                        <th>Age</th>
                                        <th>Sex</th>
                                        <th>Weight (kg)</th>
                                        <th>Manager Name</th>
                                        <th>Manager Phone</th>
                                        <th>Physical Examination</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                        <th>Data History</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($animalsData as $key => $item)
                                        <tr>
                                            {{-- <td>{{ $item['processed_at'] ?? 'N/A' }}</td> --}}
                                            <td>{{ $item['animalid'] }}</td>
                                            <td>{{ $item['species'] }}</td>
                                            <td>{{ $item['breed'] }}</td>
                                            <td>{{ $item['bdate'] }}</td>
                                            <td>{{ $item['age'] }}</td> <!-- Display pre-calculated age -->
                                            <td>{{ $item['sex'] }}</td>
                                            <td>{{ $item['weight'] }}</td>
                                            <td>{{ $item['mname'] }}</td>
                                            <td>{{ $item['mphone'] }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-info phyExamination-button"
                                                    data-animalid="{{ $key }}">
                                                    Details
                                                </button>
                                                <!-- Check-up Button -->
                                                <button class="btn btn-sm btn-success checkup-button"
                                                    onclick="window.location='{{ route('checkup-animal', ['livestockUid' => $key]) }}'">
                                                    Check-up
                                                </button>
                                            </td>

                                            <td>
                                                <a href="{{ route('edit-animalData', ['livestockUid' => $key]) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                            </td>

                                            <td>
                                                <form action="{{ url('delete-animalData/' . $key) }}" method="POST"
                                                    class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger delete-button"
                                                        data-action="{{ url('delete-animalData/' . $key) }}">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>

                                            <td>
                                                <button class="btn btn-sm btn-primary history-button"
                                                    data-animalid="{{ $item['animalid'] }}">
                                                    View
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
                    <h5 class="modal-title" id="historyModalLabel">Livestock History</h5>
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
                    <h5 class="modal-title" id="phyExaminationModalLabel">Physical Examination Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="phyExaminationModalBody" style="overflow-x: auto; white-space: nowrap;">
                    <!-- Content will be injected dynamically -->
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
                                        <th>Sex</th>
                                        <th>Weight</th>
                                        <th>Manager Name</th>
                                        <th>Manager Phone</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${Object.entries(data).map(([timestamp, history]) => `
                                                                                                                                <tr>
                                                                                                                                    <td>${timestamp}</td>
                                                                                                                                    <td>${history.animalid}</td>
                                                                                                                                    <td>${history.species}</td>
                                                                                                                                    <td>${history.breed}</td>
                                                                                                                                    <td>${history.sex}</td>
                                                                                                                                    <td>${history.weight}</td>
                                                                                                                                    <td>${history.mname}</td>
                                                                                                                                    <td>${history.mphone}</td>
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


    <!-- Custom Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content custom-modal-bg">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Delete Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this item? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
                </div>
            </div>
        </div>
    </div>

    {{-- delete modal --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let deleteFormAction = null; // Store the action URL of the delete request

            // Attach click event to all delete buttons
            document.querySelectorAll('.delete-button').forEach(button => {
                button.addEventListener('click', () => {
                    deleteFormAction = button.getAttribute(
                        'data-action'); // Get the form's action URL
                    const deleteModal = new bootstrap.Modal(document.getElementById(
                        'deleteConfirmationModal')); // Show the modal
                    deleteModal.show();
                });
            });

            // Handle delete confirmation
            document.getElementById('confirmDeleteButton').addEventListener('click', () => {
                if (deleteFormAction) {
                    // Create and submit a temporary form with the stored action URL
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = deleteFormAction;

                    // Add CSRF token input
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content');
                    form.appendChild(csrfInput);

                    // Add DELETE method input
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit(); // Submit the form
                }
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
                            modalBody.innerHTML = '<p>This livestock has not undergone any physical examination yet. Click Check-up button to add.</p>';
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
