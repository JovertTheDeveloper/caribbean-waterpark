<!-- Modal Confirmation -->
<div class="modal fade" id="modalConfirmation" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
               <div class="m-scrollable" data-scrollbar-shown="true" data-scrollable="true" data-max-height="100">
                    <p>{{ $slot }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn-cancel" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="btn-confirm" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>
<!--/. Modal -->