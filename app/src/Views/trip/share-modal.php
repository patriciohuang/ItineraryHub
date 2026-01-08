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
                            <input type="text" class="form-control" id="linkParticipant" readonly
                                   value="<?= $this->generateInviteUrl($trip->id, 'PARTICIPANT') ?>">
                            <button class="btn btn-outline-secondary" onclick="copyToClipboard('linkParticipant', this)">
                                Copy
                            </button>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="collaborator-pane">
                        <div class="alert alert-warning py-2 small">
                            <i class="bi bi-exclamation-triangle"></i> Collaborators can <strong>suggest items</strong>.
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" id="linkCollaborator" readonly
                                   value="<?= $this->generateInviteUrl($trip->id, 'COLLABORATOR') ?>">
                            <button class="btn btn-outline-secondary" onclick="copyToClipboard('linkCollaborator', this)">
                                Copy
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
function copyToClipboard(elementId, btn) {
    var copyText = document.getElementById(elementId);
    navigator.clipboard.writeText(copyText.value).then(() => {
        const originalContent = btn.innerHTML;

        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-success');
        btn.classList.add('text-white');
        btn.innerHTML = '<i class="bi bi-check-lg"></i> Link Copied!';
        
        setTimeout(() => {
            btn.classList.remove('btn-success');
            btn.classList.remove('text-white');
            btn.classList.add('btn-outline-secondary');
            btn.innerHTML = originalContent;
        }, 2000);
    });
}
</script>
