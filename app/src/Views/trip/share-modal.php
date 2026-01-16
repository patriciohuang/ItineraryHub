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
    const inputField = role === 'PARTICIPANT' ? document.getElementById('linkParticipant') : document.getElementById('linkCollaborator');
    const errorDiv = role === 'PARTICIPANT' ? document.getElementById('errorParticipant') : document.getElementById('errorCollaborator');
    const btnTextSpan = btn.querySelector('.btn-text');
    const originalText = btnTextSpan.innerText;

    if (inputField.value.trim() !== "") {
        copyToClipboard(inputField, btn, btnTextSpan);
        return;
    }

    try {
        btn.disabled = true;
        btnTextSpan.innerText = 'Generating...';
        inputField.placeholder = "Contacting server...";
        errorDiv.innerText = '';

        const tripId = document.getElementById('shareModal').getAttribute('data-trip-id');
        
        const response = await fetch(`/api/trip/generate-invite?trip_id=${tripId}&role=${role}`);
        const data = await response.json();

        if (data.success) {
            inputField.value = data.url;
            copyToClipboard(inputField, btn, btnTextSpan);
        } else {
            throw new Error(data.error || "Could not generate link");
        }

    } catch (error) {
        console.error(error);
        errorDiv.innerText = error.message;
        btnTextSpan.innerText = "Retry";
        inputField.placeholder = "Error generating link";
    } finally {
        btn.disabled = false;
    }
}

function copyToClipboard(inputElement, btn, textSpan) {
    inputElement.select();
    inputElement.setSelectionRange(0, 99999);

    navigator.clipboard.writeText(inputElement.value).then(() => {
        const originalClass = btn.className;
        btn.className = 'btn btn-success';
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Copied!';
        
        setTimeout(() => {
            btn.className = 'btn btn-outline-secondary';
            btn.innerHTML = '<i class="bi bi-clipboard"></i> Copy Link';
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy', err);
        try {
            document.execCommand('copy');
            btn.innerHTML = '<i class="bi bi-check-lg"></i> Copied!';
        } catch (ex) {
            textSpan.innerText = "Copy Failed";
        }
    });
}
</script>