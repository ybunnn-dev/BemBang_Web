<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); padding: 10px;">
      <div class="modal-header border-0 pb-0 pt-4 px-4">
        <h5 class="modal-title w-100 text-center" id="{{ $modalId }}Label" 
            style="font-family: 'Poppins', sans-serif; font-weight: 700; color: #566A7F; font-size: 22px; letter-spacing: -0.2px;">
          {{ $title }}
        </h5>
      </div>
      <div class="modal-body pt-3 pb-4 px-4">
        <div style="background-color: #f8f9fa; border-radius: 12px; padding: 20px;">
          <label for="roomTypeSelect" class="form-label d-block text-center mb-3"
                 style="font-family: 'Poppins', sans-serif; color: #697A8D; font-size: 15px; text-transform: uppercase; font-weight: 500; letter-spacing: 0.5px;">
            Room Type
          </label>
          <select class="form-select" aria-label="Room type selection" id="roomTypeSelect"
                  style="font-family: 'Poppins', sans-serif; padding: 12px 15px; border-radius: 8px; border-color: #dfe3e7;">
            <option selected value="0">Select Type</option>
            @foreach($room_type as $type)
              <option value="{{ $type->_id }}">{{ $type->type_name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer border-0 pt-2 px-4 pb-4 justify-content-center gap-3">
        <button type="button" class="btn btn-light px-4 py-2" data-bs-dismiss="modal"
                style="font-family: 'Poppins', sans-serif; font-weight: 600; 
                       background-color: #f7f7f7; border: 1px solid #578FCA; 
                       color: #578FCA; border-radius: 8px; min-width: 120px;">
          Close
        </button>
        <button type="button" class="btn btn-primary px-4 py-2" id="confirm-add-room"
                style="font-family: 'Poppins', sans-serif; font-weight: 600; 
                       background-color: #578FCA; color: #ffffff; border: none; 
                       border-radius: 8px; min-width: 120px;">
          Add Room
        </button>
      </div>
    </div>
  </div>
</div>