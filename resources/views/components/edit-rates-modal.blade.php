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
         <div class="checkin-rates-labels">
            <p>12 HOURS (CHECKIN)</p>
            <p>24 HOURS (CHECKIN)</p>
         </div>
         <div class="checkin-rates-input">
            <input type="number" class="form-control" id="edit-check-12" placeholder="Enter name" value="{{ $roomType->rates['checkin_12h'] }}">
            <input type="number" class="form-control" id="edit-check-24" placeholder="Enter guest number" value="{{ $roomType->rates['checkin_24h'] }}">
         </div>

         <div class="reserve-rates-labels">
            <p>12 HOURS (RESERVATION)</p>
            <p>24 HOURS (RESERVATION)</p>
         </div>
         <div class="reserve-rates-input">
            <input type="number" class="form-control" id="edit-reserve-12" placeholder="Enter name" value="{{ $roomType->rates['reservation_12h'] }}">
            <input type="number" class="form-control" id="edit-reserve-24" placeholder="Enter guest number" value="{{ $roomType->rates['checkin_24h'] }}">
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" id="close-edit-rate-modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="save-edit-rate-modal" disabled>Save Changes</button>
      </div>
    </div>
  </div>
</div>
