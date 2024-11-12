<style>
    /* Custom style for Save Event button */
    .custom-save-btn {
        background-color: #8c2f39 !important;
        /* Custom button background color */
        color: #fff !important;
        /* Custom button text color */
        border: none;
        /* Remove border */
        padding: 8px 16px;
        /* Custom padding */
        border-radius: 5px;
        /* Rounded corners */
    }

    .custom-save-btn:hover {
        background-color: #a0303e !important;
        /* Hover effect color */
    }

    .modal-header.custom-header {
        background-color: #8c2f39; 
        color: #fff; 
    }

</style>


<!-- Edit Event Modal -->
<div class="modal fade" id="edit_event_modal" tabindex="-1" aria-labelledby="editEventLabel" aria-hidden="true"
      data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header custom-header">
        <h5 class="modal-title" id="editEventLabel">Edit Event</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editEventForm">
          <input type="hidden" id="edit_event_id">
          <div class="mb-3">
            <label for="edit_event_name" class="form-label">Event Name</label>
            <input type="text" class="form-control" id="edit_event_name" required>
          </div>
          <div class="mb-3">
            <label for="edit_event_start_date" class="form-label">Start Date</label>
            <input type="date" class="form-control onlydatepicker" id="edit_event_start_date" required>
          </div>
          <div class="mb-3">
            <label for="edit_event_end_date" class="form-label">End Date</label>
            <input type="date" class="form-control onlydatepicker" id="edit_event_end_date" required>
          </div>
          <div class="mb-3">
            <label for="edit_event_type" class="form-label">Event Type</label>
            <select class="form-control" id="edit_event_type" required>
              <option value="" disabled selected>Select event type</option>
              <option value="Personal">Personal</option>
              <option value="Academic">Academic</option>
              <option value="Entrepreneurship">Entrepreneurship</option>
              <option value="Sport">Sport</option>
              <option value="Volunteering">Volunteering</option>
            </select>
          <div class="mb-3">
            <label for="edit_event_description" class="form-label">Description</label>
            <textarea class="form-control" id="edit_event_description"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn custom-save-btn" onclick="update_event()">Save Changes</button>
        <button type="button" class="btn btn-secondary" data-bs-target="#event_details_modal" data-bs-toggle="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
