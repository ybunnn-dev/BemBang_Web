<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label for="formControlInput1" class="form-label" id="formControlInput1Label">ENTER NEW EMAIL</label>
        <input type="email" class="form-control" id="formControlInput1" value="{{ Auth::user()->email }}">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="email-submit" onclick="insertInput('formControlInput1', 1)" disabled>Confirm</button>
      </div>
    </div>
  </div>
</div>

