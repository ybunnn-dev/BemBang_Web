<div class="modal fade" id="cancelConfirm" tabindex="-1" aria-labelledby="cancelConfirmLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); padding: 10px;">
      <div class="modal-header border-0 pb-0 pt-4 px-4">
        <h5 class="modal-title w-100 text-center" id="confirmationModalLabel" 
            style="font-family: 'Poppins', sans-serif; font-weight: 700; color: #566A7F; font-size: 22px; letter-spacing: -0.2px;">
          Cancel
        </h5>
      </div>
      <div class="modal-body pt-3 pb-4 px-4">
        <p class="text-center" style="font-family: 'Poppins', sans-serif; color: #697A8D; font-size: 14px; margin-top: 15px;">
          Are you sure you want to cancel this transaction?
        </p>
      </div>
      <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center gap-3">
        <button type="button" class="btn btn-light px-4 py-2" data-bs-dismiss="modal"
                style="font-family: 'Poppins', sans-serif; font-weight: 600; 
                       background-color: #f7f7f7; border: 1px solid #578FCA; 
                       color: #578FCA; border-radius: 8px; min-width: 120px;"
                       >
          Cancel
        </button>
        <button type="button" class="btn btn-primary px-4 py-2" id="finalCancel"
                style="font-family: 'Poppins', sans-serif; font-weight: 600; 
                       background-color: #578FCA; color: #ffffff; border: none; 
                       border-radius: 8px; min-width: 120px;">
          Confirm
        </button>
      </div>
    </div>
  </div>
</div>