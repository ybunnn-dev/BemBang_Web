<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{ $modalId }}Label">{{ $title }}</h5>
      </div>
      <div class="modal-body">
        <label for="formControlInput2" class="form-label" id="formControlInput3Label">ROOM TYPE</label>
        <select class="form-select" aria-label="Default select" id="roomTypeSelect">
          <option selected="0">Select Type</option>
          @foreach($room_type as $type)
            <option value="{{ $type->_id }}">{{ $type->type_name }}</option>
          @endforeach
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="confirm-add-room">Add Room</button>
      </div>
    </div>
  </div>
</div>
