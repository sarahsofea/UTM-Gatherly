<!-- Event Details Modal -->
<div class="modal fade" id="event_details_modal" tabindex="-1" role="dialog" aria-labelledby="eventDetailsLabel" aria-hidden="true"
data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <!-- Header with Event Title and Close Button -->
            <div class="modal-header custom-header">
                <h5 class="modal-title" id="eventDetailsLabel">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Event Details Content -->
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <div class="mb-3">
                                <label class="form-label"><strong>Event Name:</strong></label>
                                <p class="form-control-plaintext" id="event_name_display">[Event Name]</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><strong>Start Date:</strong></label>
                                <p class="form-control-plaintext" id="event_start_date_display">[Start Date]</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><strong>End Date:</strong></label>
                                <p class="form-control-plaintext" id="event_end_date_display">[End Date]</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><strong>Event Type:</strong></label>
                                <p class="form-control-plaintext" id="event_type_display">[Event Type]</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><strong>Description:</strong></label>
                                <p class="form-control-plaintext" id="event_description_display">[Description]</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Footer with Action Buttons -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="edit_event()">Edit</button>
                <button type="button" class="btn btn-danger" onclick="delete_event()">Delete</button>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-header.custom-header {
        background-color: #8c2f39; 
        color: #fff; 
    }
    .modal-body .card {
        border: none;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }
    .modal-body .form-label {
        font-weight: bold;
        color: #333;
    }
    .form-control-plaintext {
        background-color: #f9f9f9;
        padding: 10px 15px;
        border-radius: 5px;
        border: 1px solid #e3e3e3;
        color: #555;
    }
</style>
