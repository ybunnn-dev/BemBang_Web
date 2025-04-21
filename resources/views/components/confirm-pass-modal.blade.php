<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
        <p>Please enter your old password to continue.</p>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label for="formControlInput6" class="form-label" id="formControlInput6Label">ENTER OLD PASSWORD</label>
        <input type="password" class="form-control" id="formControlInput6" placeholder="Enter password">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="confirmPassModalBtn" disabled>Confirm</button>
      </div>
    </div>
  </div>
</div>

