{{--
<!-- Physical Examination Modal -->
<div class="modal fade" id="phyExaminationModal" tabindex="-1" aria-labelledby="phyExaminationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="phyExaminationModalLabel">Physical Examination Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="phyExaminationModalBody">
                <!-- Physical Examination details will be dynamically loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



{{--


<script>
    // Attach click event to history buttons
    document.querySelectorAll('.phyExamination-button').forEach(button => {
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
                                    <th>Temperature</th>
                                    <th>Pulse/min</th>
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
                            // <tbody>
                            //     ${Object.entries(data).map(([timestamp, history]) => `
                            //                 <tr>
                            //                     <td>${timestamp}</td>
                            //                     <td>${history.animalid}</td>
                            //                     <td>${history.species}</td>
                            //                     <td>${history.breed}</td>
                            //                     <td>${history.age}</td>
                            //                     <td>${history.sex}</td>
                            //                     <td>${history.weight}</td>
                            //                     <td>${history.flocation}</td>
                            //                 </tr>
                            //             `).join('')}
                            // </tbody>
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
</script> --}} 
