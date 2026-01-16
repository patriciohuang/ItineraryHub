<div class="mb-3">
    <label for="category" class="form-label">Type of Activity <span class="text-danger" aria-hidden="true">*</span></label>
    <select name="category_id" id="category" class="form-select" required>
        <option value="" disabled <?= empty($item->category_id) && empty($oldInput['category_id']) ? 'selected' : '' ?>>
            Choose a category...
        </option>
        
        <?php foreach ($categories as $category): ?>
            <?php 
                $currentId = $oldInput['category_id'] ?? $item->category_id;
                $isSelected = ($currentId == $category->id);
            ?>
            <option value="<?= $category->id ?>" <?= $isSelected ? 'selected' : '' ?>>
                <?= htmlspecialchars($category->name) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mb-3">
    <label for="title" class="form-label">Title (e.g., Flight to Paris, Hilton Hotel) <span class="text-danger" aria-hidden="true">*</span></label>
    <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($oldInput['title'] ?? $item->title ?? '') ?>">
</div>

<fieldset class="row">
    <legend class="col-form-label col-12 pt-0 fw-bold small text-uppercase text-muted">Timing</legend>
    <div class="col-md-6 mb-3">
        <label for="start_date" class="form-label">Start Time</label>
        <input type="datetime-local" name="start_date" id="start_date" class="form-control" value="<?= htmlspecialchars($oldInput['start_date'] ?? $item->start_date ?? '') ?>">
    </div>
    <div class="col-md-6 mb-3">
        <label for="end_date" class="form-label">End Time</label>
        <input type="datetime-local" name="end_date" id="end_date" class="form-control" value="<?= htmlspecialchars($oldInput['end_date'] ?? $item->end_date ?? '') ?>">
    </div>
</fieldset>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="url" class="form-label">Booking Link / Location URL (Optional)</label>
        <input type="url" name="url" id="url" class="form-control" placeholder="https://..." value="<?= htmlspecialchars($oldInput['url'] ?? $item->url ?? '') ?>">
    </div>
    <div class="col-md-6 mb-3">
        <label for="attachment" class="form-label">Attach File (Image/PDF)</label>
        <input type="file" name="attachment" id="attachment" class="form-control">
        <div class="form-text">Optional. Upload a ticket, reservation confirmation, or photo.</div>
    </div>
</div>

<div class="mb-3">
    <label for="notes" class="form-label">Notes</label>
    <textarea name="notes" id="notes" rows="3" class="form-control" placeholder="Reservation numbers, gate info, etc."><?= htmlspecialchars($oldInput['notes'] ?? $item->notes ?? '') ?></textarea>
</div>