<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{ $modalId }}Label">{{ $title }}</h5>
      </div>
      <div class="modal-body">
            <div class="feature-wrapper">
                <table class="table table-borderless feature-table">
                    <thead>
                        <tr>
                            <th style="width: 10%;"></th>
                            <th style="width: 60%;">Feature Name</th>
                            <th style="width: 30%;">Icon</th>
                        </tr>
                    </thead>
                    <tbody id="features-table-body">
                        <!-- Table rows will be rendered by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" id="cancel-button">Return</button>
        <button type="button" class="btn btn-primary" onclick="addCheckedFeatures()" id="add-feature-button">Add</button>
      </div>
    </div>
  </div>
</div>
