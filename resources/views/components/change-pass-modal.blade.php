<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label for="formControlInput2" class="form-label" id="formControlInput4Label">ENTER NEW PASSWORD</label>
        <input type="password" class="form-control" id="formControlInput4" placeholder="Enter here">

        <label for="formControlInput2" class="form-label" id="formControlInput5Label">RE-ENTER NEW PASSWORD</label>
        <input type="password" class="form-control" id="formControlInput5" placeholder="Enter here">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Change Password</button>
      </div>
    </div>
  </div>
</div>
