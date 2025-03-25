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
                            <!-- Livestock ID -->
                            <input type="text" name="animalid" class="form-control me-2"
                                placeholder="Livestock ID" value="{{ request()->get('animalid') }}">
                            <!-- Species Dropdown -->
                            <select name="species" class="form-select me-2">
                                <option value="">Select Species</option>
                                <option value="Cattle" {{ request()->get('species') === 'Cattle' ? 'selected' : '' }}>Cattle
                                </option>
                                <option value="Goat" {{ request()->get('species') === 'Goat' ? 'selected' : '' }}>Goat
                                </option>
                                <!-- Add other species as needed -->
                            </select>

                            <!-- Breed Dropdown -->
                            <select name="breed" class="form-select me-2">
                                <option value="">Select Breed</option>
                                <option value="Brahman" {{ request()->get('breed') === 'Brahman' ? 'selected' : '' }}>
                                    Brahman</option>
                                <option value="Kedah-Kelantan"
                                    {{ request()->get('breed') === 'Kedah-Kelantan' ? 'selected' : '' }}>Kedah-Kelantan
                                </option>
                                <option value="Boer" {{ request()->get('breed') === 'Boer' ? 'selected' : '' }}>Boer
                                </option>
                                <option value="Jamnapari" {{ request()->get('breed') === 'Jamnapari' ? 'selected' : '' }}>
                                    Jamnapari</option>
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

                            <!-- Manager Name -->
                            <input type="text" name="manager" class="form-control me-2" placeholder="Manager Name"
                                value="{{ request()->get('manager') }}">

                            <!-- Age Dropdown -->
                            <select name="age" class="form-select me-2">
                                <option value="">Select Age</option>
                                <option value="1" {{ request()->get('age') === '1' ? 'selected' : '' }}>More than 1
                                    year</option>
                                <option value="2" {{ request()->get('age') === '2' ? 'selected' : '' }}>More than 2
                                    years</option>
                            </select>

                            <!-- Buttons -->
                            <button type="submit" class="btn btn-primary">Filter</button>
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
                                        <th>Manager Name</th>
                                        <th>Manager Phone</th>
                                        <th>Checkup</th>
                                        <th>Livestock Report</th>
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
                                            <td>{{ $item['mname'] }}</td>
                                            <td>{{ $item['mphone'] }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-info phyExamination-button"
                                                    animalKey="{{ $key }}">
                                                    Details
                                                </button>
                                            </td>
                                            <td>
                                                <a href="{{ route('animal.report', $item['animalid']) }}"
                                                    class="btn btn-sm btn-primary">
                                                    Download Report
                                                </a>
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
@endsection

<!-- Physical Examination Modal -->
<div class="modal fade" id="phyExaminationModal" tabindex="-1" aria-labelledby="phyExaminationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="phyExaminationModalLabel">Physical Checkup Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="phyExaminationModalBody" style="overflow-x: auto; white-space: nowrap;">
                <!-- Content will be injected dynamically -->
            </div>
        </div>
    </div>
</div>

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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.phyExamination-button').forEach(button => {
            button.addEventListener('click', () => {
                const livestockUid = button.getAttribute('animalKey'); // Get the animal ID
                console.log("Fetching checkup data for animalKey:", livestockUid);

                const modalBody = document.getElementById('phyExaminationModalBody');

                // Show loading state in the modal
                modalBody.innerHTML = '<p>Loading physical checkup data...</p>';

                // Fetch the physical examination data for the animal
                fetch(`/get-checkup-data/${livestockUid}`)
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
                                '<p>This livestock has not undergone any physical checkup yet.</p>';
                        } else {
                            // Build a table to display the data
                            modalBody.innerHTML = `
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Examined Date</th>
                                    <th>Weight (kg)</th>
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
                                            <td>${checkup.weight ?? 'N/A'}</td>
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
    });
</script>
