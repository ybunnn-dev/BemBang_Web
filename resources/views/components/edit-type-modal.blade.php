<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
<div id="edit-modal" 
     data-name="{{ $roomType->type_name }}" 
     data-guest="{{ $roomType->guest_num }}" 
     data-description="{{ $roomType->description }}">
</div>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{ $modalId }}Label">{{ $title }}</h5>
      </div>
      <div class="modal-body">
         <div class="name-num-labels">
            <p>Room Name</p>
            <p>Number of Guests</p>
         </div>
         <div class="name-num-input">
            <input type="text" class="form-control" id="edit-type-name" placeholder="Enter name" value="{{ $roomType->type_name }}">
            <input type="number" class="form-control" id="edit-guest-num" placeholder="Enter guest number" value="{{ $roomType->guest_num }}">
         </div>
         <div class="edit-features-section">
            <p class="features-p-head">Room Features</p>
            <div class="features-content-list">
                <div class="edit-features-list" id="current-feature-list">
                    <div id="current-feature-list"></div>

                </div>
            </div>
         </div>
         <div class="description-div">
            <p>Description</p>
            <textarea id="edit-description">{{ str_replace('\\n', "\n", $roomType->description) }}</textarea>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" id="close-edit-modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="save-edit-modal" onclick="confirm_details()" disabled>Save Changes</button>
      </div>
    </div>
  </div>
</div>
