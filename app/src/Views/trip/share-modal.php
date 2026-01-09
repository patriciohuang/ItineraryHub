<div class="modal fade" id="shareModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Share Trip</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">Share these links to add people to your trip.</p>

                <ul class="nav nav-tabs mb-3" id="inviteTabs">
                    <li class="nav-item">
                        <button class="nav-link active" id="participant-tab" data-bs-toggle="tab" data-bs-target="#participant-pane" type="button">
                            <i class="bi bi-emoji-smile"></i> Participant
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="collaborator-tab" data-bs-toggle="tab" data-bs-target="#collaborator-pane" type="button">
                            <i class="bi bi-pencil-square"></i> Collaborator
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="inviteTabsContent">
                    
                    <div class="tab-pane fade show active" id="participant-pane">
                        <div class="alert alert-warning py-2 small">
                            <i class="bi bi-exclamation-triangle"></i> Participants can <strong>view details</strong> and <strong>suggest items</strong>, but <strong>cannot edit</strong> the itinerary.
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" id="linkParticipant" readonly placeholder="Generating link...">
                            <button class="btn btn-outline-secondary" onclick="handleLinkAction('PARTICIPANT', this)">
                                <i class="bi bi-link-45deg"></i> Get Link
                            </button>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="collaborator-pane">
                        <div class="alert alert-warning py-2 small">
                            <i class="bi bi-exclamation-triangle"></i> Collaborators can <strong>suggest items</strong>.
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" id="linkCollaborator" readonly placeholder="Generating link...">
                            <button class="btn btn-outline-secondary" onclick="handleLinkAction('COLLABORATOR', this)">
                                <i class="bi bi-link-45deg"></i> Get Link
                            </button>
                        </div>
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
        
        btn.classList.remove('btn-primary', 'btn-outline-secondary');
        btn.classList.add('btn-success');
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Copied!';
        
        setTimeout(() => {
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
            btn.innerHTML = '<i class="bi bi-clipboard"></i> Copy';
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy', err);
    });
}
</script>