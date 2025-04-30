<div class="modal fade" id="cancelConfirm" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
      <div class="modal-header border-0 pb-0 pt-3 px-4" style="padding: 30px 30px 30px 30px;">
        <h5 class="modal-title w-100 text-center" style="font-family: 'Poppins', sans-serif; font-weight: 600; color: #566A7F; font-size: 20px; padding-top: 40px;">
          Approve Transaction
        </h5>
      </div>
      <div class="modal-body pt-3 px-4" style="padding: 30px;">
        <div style="background-color: #f8f9fa; border-radius: 12px; padding: 16px; margin-bottom: 5px;">
          <p id="confirmation-content" style="font-family: 'Poppins', sans-serif; color: #566A7F; margin-bottom: 0; font-size: 15px; text-align: center;">
            Are you sure you want to approve this transaction?
          </p>
        </div>
      </div>
      <div class="modal-footer border-0 pt-2 px-4 pb-4 justify-content-end gap-2" style="padding: 0 30px 30px 30px;">
        <button type="button" class="btn btn-light px-4 py-2" data-bs-dismiss="modal"
                style="font-family: 'Poppins', sans-serif; font-weight: 600; 
                       background-color: #f7f7f7; border: 1px solid #578FCA; 
                       color: #578FCA; border-radius: 8px; min-width: 100px;">
          Cancel
        </button>
        <button type="button" class="btn btn-primary confirm-button px-4 py-2"
                style="font-family: 'Poppins', sans-serif; font-weight: 600; 
                       background-color: #578FCA; color: #ffffff; border: none; 
                       border-radius: 8px; min-width: 100px;" id="confirmApproval">
          Confirm
        </button>
      </div>
    </div>
  </div>
</div>