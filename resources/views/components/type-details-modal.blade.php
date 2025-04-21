<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{ $modalId }}Label">{{ $title }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <hr id="features-topline">
      </div>
      <div class="modal-body">
         <div class="modal-features">
                <div class="modal-features-header">
                    <img src="{{ asset('images/info.svg') }}">
                    <h6>Room Features</h6>
                </div>
                <div class="features-wrapper">
                    @foreach($features as $index => $modal_feature)
                        <div class="modal-feature-item">
                            <img src="{{ asset($modal_feature->feature_icon) }}">
                            <p>{{ $modal_feature->feature_name }}</p>
                        </div>
                    @endforeach
                </div>
         </div>
         <hr id="feautres-middleLine">
         <div class="modal-description">
            <div class="modal-description-header">
                <img src="{{ asset('images/description.svg') }}">
                <h6>Description</h6>
            </div>
            <p>{!! str_replace('\n', '<br><br>', $roomType->description) !!}</p>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="gotoEditDetails()" id="cancel-button">Edit Details</button>
      </div>
    </div>
  </div>
</div>
