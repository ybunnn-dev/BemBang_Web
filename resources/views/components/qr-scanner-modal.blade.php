<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{ $modalId }}Label">{{ $title }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
              <div class="qr-content">
                  <div class="scanner-holder">
                    <div id="qr-reader-main" style="width: 100%; height: 100%;"></div>
                  </div>
                  <p>Please position the QR code in front of the camera.</p>
                  <hr class="bottom-line">
              </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancel-button">Cancel</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="confirm-button">Confirm</button>
      </div>
    </div>
  </div>
</div>
