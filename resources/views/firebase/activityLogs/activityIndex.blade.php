@extends('firebase.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-2">
                    <div class="card-header mt-3">
                        <h4>Activity Logs</h4>
                    </div>
                    <div class="card-body"> <!-- Added margin-top here -->
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="table-primary">
                                    <th>Timestamp</th>
                                    <th>Action</th>
                                    <th>Livestock ID</th>
                                    <th>Description</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($logs)
                                    @foreach ($logs as $key => $log)
                                        <tr>
                                            <!-- Fallback to 'N/A' if timestamp is missing -->
                                            <td>{{ $log['timestamp'] ?? 'No Timestamp' }}</td>
                                            <td>{{ $log['action'] }}</td>
                                            <td>{{ $log['animal_id'] ?? 'N/A' }}</td>
                                            <td>{{ $log['description'] }}</td>
                                            <td>
                                                <button class="btn btn-info btn-sm details-button"
                                                    data-animalid="{{ $log['animal_id'] ?? '' }}"
                                                    data-timestamp="{{ $log['timestamp'] ?? '' }}">
                                                    Details
                                                </button>
                                            </td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">No logs found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Livestock Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-body">
                    <!-- Details will be dynamically inserted here -->
                </div>
            </div>
        </div>
    </div>


    <script>
        document.querySelectorAll('.details-button').forEach(button => {
            button.addEventListener('click', () => {
                const animalId = button.getAttribute('data-animalid');
                const timestamp = button.getAttribute('data-timestamp');

                if (!animalId || !timestamp) {
                    alert('No details available for this entry.');
                    return;
                }

                fetch(`/get-livestock-details/${animalId}?timestamp=${encodeURIComponent(timestamp)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to fetch details');
                        }
                        return response.json();
                    })
                    .then(data => {
                        const modalBody = document.getElementById('modal-body');
                        modalBody.innerHTML = `
                        <table class="table table-bordered">
                            <tr><th>Livestock ID</th><td>${data.animalid}</td></tr>
                            <tr><th>Species</th><td>${data.species}</td></tr>
                            <tr><th>Breed</th><td>${data.breed}</td></tr>
                            <tr><th>Birth Date</th><td>${data.bdate}</td></tr>
                            <tr><th>Age (Months)</th><td>${data.age}</td></tr>
                            <tr><th>Sex</th><td>${data.sex}</td></tr>
                            <tr><th>Weight (kg)</th><td>${data.weight}</td></tr>
                            <tr><th>Manager Name</th><td>${data.mname}</td></tr>
                            <tr><th>Manager Phone</th><td>${data.mphone}</td></tr>
                            <tr><th>Farm Location</th><td>${data.flocation}</td></tr>
                        </table>
                        `;
                        const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to fetch details.');
                    });
            });
        });
    </script>
@endsection
