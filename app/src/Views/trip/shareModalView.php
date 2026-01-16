<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true" data-trip-id="<?= $trip->id ?? 0 ?>">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="shareModalLabel">Share Trip</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">Share these links to add people to your trip.</p>

                <ul class="nav nav-tabs mb-3" id="inviteTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="participant-tab" data-bs-toggle="tab" data-bs-target="#participant-pane" type="button" role="tab" aria-controls="participant-pane" aria-selected="true">
                            <i class="bi bi-emoji-smile" aria-hidden="true"></i> Participant
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="collaborator-tab" data-bs-toggle="tab" data-bs-target="#collaborator-pane" type="button" role="tab" aria-controls="collaborator-pane" aria-selected="false">
                            <i class="bi bi-pencil-square" aria-hidden="true"></i> Collaborator
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="inviteTabsContent">
                    
                    <div class="tab-pane fade show active" id="participant-pane" role="tabpanel" aria-labelledby="participant-tab">
                        <div class="alert alert-warning py-2 small">
                            <i class="bi bi-exclamation-triangle-fill me-1" aria-hidden="true"></i>
                            Participants can <strong>view</strong> and <strong>suggest</strong>, but cannot edit.
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" id="linkParticipant" readonly placeholder="Click button to generate →" aria-label="Participant Invite Link">
                            <button class="btn btn-outline-primary" onclick="handleLinkAction('PARTICIPANT', this)">
                                <i class="bi bi-link-45deg" aria-hidden="true"></i> <span class="btn-text">Get Link</span>
                            </button>
                        </div>
                        <div id="errorParticipant" class="text-danger small mt-2" aria-live="polite"></div>
                    </div>

                    <div class="tab-pane fade" id="collaborator-pane" role="tabpanel" aria-labelledby="collaborator-tab">
                        <div class="alert alert-warning py-2 small">
                            <i class="bi bi-exclamation-triangle-fill me-1" aria-hidden="true"></i>
                            Collaborators can <strong>suggest items</strong> to the itinerary.
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" id="linkCollaborator" readonly placeholder="Click button to generate →" aria-label="Collaborator Invite Link">
                            <button class="btn btn-outline-primary" onclick="handleLinkAction('COLLABORATOR', this)">
                                <i class="bi bi-link-45deg" aria-hidden="true"></i> <span class="btn-text">Get Link</span>
                            </button>
                        </div>
                        <div id="errorCollaborator" class="text-danger small mt-2" aria-live="polite"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
async function handleLinkAction(role, btn) {
    const originalText = btn.innerHTML;
    const inputField = role === 'PARTICIPANT' ? document.getElementById('linkParticipant') : document.getElementById('linkCollaborator');
    try {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Generating...';
        inputField.placeholder = "Contacting server...";
        const tripId = <?= $trip->id ?>;
        const response = await fetch(`/api/trip/generate-invite?trip_id=${tripId}&role=${role}`);
        const data = await response.json();

        if (data.success) {
            inputField.value = data.url;
            copyToClipboard(inputField, btn);
        } else {
            alert("Error: " + (data.error || "Could not generate link"));
            btn.innerHTML = originalText; 
        }
    } catch (error) {
        console.error(error);
        alert("Network error. Please try again.");
        btn.innerHTML = originalText;
    } finally {
        btn.disabled = false;
    }
}

function copyToClipboard(inputElement, btn) {
    navigator.clipboard.writeText(inputElement.value).then(() => {
        const originalHtml = btn.innerHTML;
        
        btn.classList.remove('btn-primary', 'btn-outline-primary');
        btn.classList.add('btn-success');
        btn.innerHTML = '<i class="bi bi-check-lg"></i> <span class="btn-text">Copied!</span>';
        
        setTimeout(() => {
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-primary');
            btn.innerHTML = '<i class="bi bi-clipboard"></i> <span class="btn-text">Copy Link</span>';
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy', err);
    });
}
</script>