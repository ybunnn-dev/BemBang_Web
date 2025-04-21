<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{ $modalId }}Label">{{ $title }}</h5>
      </div>
      <div class="modal-body">
        Are you sure you want to save changes?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" id="cancel-button" onclick="switchBackFromConfirm()">Cancel</button>
        <button type="button" class="btn btn-primary" id="confirm-changes-desc">Confirm</button>
      </div>
    </div>
  </div>
</div>
