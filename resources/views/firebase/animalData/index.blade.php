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
                        <h4>Number of Livestock (Total): {{ $total_animalDatas }}</h4>
                    </div>
                    <div class="card-body">
                        @if (empty($animalsData))
                            <div class="text-center p-3">
                                <h5>No Livestock Found</h5>
                                <p>Start by adding a new livestock record.</p>
                            </div>
                        @else
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Livestock ID</th>
                                        <th>Species</th>
                                        <th>Breed</th>
                                        <th>Birth Date</th>
                                        <th>Age (Months)</th>
                                        <th>Sex</th>
                                        <th>Weight (kg)</th>
                                        <th>Manager Name</th>
                                        <th>Manager Phone</th>
                                        <th>Location (Farm)</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                        <th>Data History</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($animalsData as $key => $item)
                                        <tr>
                                            <td>{{ $item['processed_at'] ?? 'N/A' }}</td>
                                            <td>{{ $item['animalid'] }}</td>
                                            <td>{{ $item['species'] }}</td>
                                            <td>{{ $item['breed'] }}</td>
                                            <td>{{ $item['bdate'] }}</td>
                                            <td>{{ $item['age'] }}</td>
                                            <td>{{ $item['sex'] }}</td>
                                            <td>{{ $item['weight'] }}</td>
                                            <td>{{ $item['mname'] }}</td>
                                            <td>{{ $item['mphone'] }}</td>
                                            <td>{{ $item['flocation'] }}</td>
                                            <td>
                                                <a href="{{ route('edit-animalData', ['uid' => $key]) }}"
                                                    class="btn btn-sm btn-success">Edit</a>
                                            </td>

                                            <td>
                                                <form action="{{ url('delete-animalData/' . $key) }}" method="POST" onsubmit="return confirmDelete()">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info history-button"
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
                                                    <td>${history.flocation}</td>
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
        function confirmDelete() {
            return confirm("Are you sure you want to delete this item? This action cannot be undone.");
        }
    </script>

@endsection
