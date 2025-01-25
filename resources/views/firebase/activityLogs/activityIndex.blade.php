@extends('firebase.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-2">
                    <div class="card-header mt-3 d-flex justify-content-between align-items-center">
                        <h4>Activity Logs</h4>
                        <button id="clearAllButton" class="btn btn-danger btn-sm">Clear All</button>
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
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($logs)
                                    @foreach ($logs as $key => $log)
                                        <tr data-key="{{ $key }}">
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
                                            <td>
                                                <button class="btn btn-danger btn-sm delete-button"
                                                    data-key="{{ $key }}">
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">No logs found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="modal" tabindex="-1" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content custom-modal-bg">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalTitle"></h5>
                    <button type="button" class="btn-close" onclick="hideConfirmationModal()"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmationModalMessage"></p>
                </div>
                <div class="modal-footer">
                    <button id="confirmButton" class="btn btn-danger">Yes</button>
                    <button type="button" class="btn btn-secondary" onclick="hideConfirmationModal()">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let modalCallback = null;

        // Show confirmation modal
        function showConfirmationModal(title, message, callback) {
            document.getElementById('confirmationModalTitle').innerText = title;
            document.getElementById('confirmationModalMessage').innerText = message;
            modalCallback = callback;

            const modal = document.getElementById('confirmationModal');
            modal.style.display = 'block';
            modal.classList.add('show');
        }

        // Hide confirmation modal
        function hideConfirmationModal() {
            const modal = document.getElementById('confirmationModal');
            modal.style.display = 'none';
            modal.classList.remove('show');
        }

        // Handle modal confirmation
        document.getElementById('confirmButton').addEventListener('click', () => {
            if (modalCallback) {
                modalCallback();
                modalCallback = null;
            }
            hideConfirmationModal();
        });

        // Handle delete button click
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', () => {
                const key = button.getAttribute('data-key');

                showConfirmationModal(
                    'Confirm Deletion',
                    'Are you sure you want to delete this log?',
                    () => {
                        fetch(`/delete-log/${key}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Failed to delete log');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    document.querySelector(`tr[data-key="${key}"]`).remove();
                                } else {
                                    alert('Failed to delete log');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Failed to delete log.');
                            });
                    }
                );
            });
        });


        // Handle clear all button click
        document.getElementById('clearAllButton').addEventListener('click', () => {
            showConfirmationModal(
                'Confirm Clear All',
                'Are you sure you want to clear all logs?',
                () => {
                    fetch('/clear-all-logs', {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Failed to clear logs');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                document.querySelector('tbody').innerHTML =
                                    '<tr><td colspan="6" class="text-center">No logs found</td></tr>';
                            } else {
                                alert('Failed to clear logs');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Failed to clear logs.');
                        });
                }
            );
        });
    </script>
@endsection
