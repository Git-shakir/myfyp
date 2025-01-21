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
                                        <th>Farm Location</th>
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
                                            <td>{{ $item['flocation'] }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-info phyExamination-button"
                                                    data-animalid="{{ $key }}">
                                                    Details
                                                </button>
                                                <button class="btn btn-sm btn-success checkup-button"
                                                    data-animalid="{{ $key }}">
                                                    Check-up
                                                </button>
                                            </td>
                                            <td>
                                                <a href="{{ route('edit-animalData', ['livestockUid' => $key]) }}"
                                                    class="btn btn-sm btn-success">Edit</a>
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
                    <h5 class="modal-title" id="historyModalLabel">Animal History</h5>
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
                            modalBody.innerHTML = `<p>No history available for this animal.</p>`;
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
                                        <th>Location</th>
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
                                                                                                        <td>${history.flocation}</td>
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
                const animalId = button.getAttribute('data-animalid');
                const modalBody = document.getElementById('phyExaminationModalBody');

                // Show loading state
                modalBody.innerHTML = '<p>Loading physical examination data...</p>';

                fetch(`/get-phy-examination/${animalId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch physical examination data');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log("Fetched Physical Examination Data:", data); // Log the fetched data

                        if (Object.keys(data).length === 0) {
                            modalBody.innerHTML =
                                `<p>No physical examination data available for this animal.</p>`;
                        } else {
                            modalBody.innerHTML = `<table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
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
                            <tr>
                                <td>${data.examined_at ?? 'N/A'}</td>
                                <td>${data.temperature ?? 'N/A'}</td>
                                <td>${data.genApp ?? 'N/A'}</td>
                                <td>${data.mucous ?? 'N/A'}</td>
                                <td>${data.integument ?? 'N/A'}</td>
                                <td>${data.nervous ?? 'N/A'}</td>
                                <td>${data.musculoskeletal ?? 'N/A'}</td>
                                <td>${data.eyes ?? 'N/A'}</td>
                                <td>${data.ears ?? 'N/A'}</td>
                                <td>${data.gastrointestinal ?? 'N/A'}</td>
                                <td>${data.respiratory ?? 'N/A'}</td>
                                <td>${data.cardiovascular ?? 'N/A'}</td>
                                <td>${data.reproductive ?? 'N/A'}</td>
                                <td>${data.urinary ?? 'N/A'}</td>
                                <td>${data.mGland ?? 'N/A'}</td>
                                <td>${data.lymphatic ?? 'N/A'}</td>
                            </tr>
                        </tbody>
                    </table>`;
                        }

                        // Show the modal
                        const modal = new bootstrap.Modal(document.getElementById(
                            'phyExaminationModal'));
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Error fetching physical examination data:', error);
                        modalBody.innerHTML =
                            `<p class="text-danger">Failed to fetch physical examination data.</p>`;
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
