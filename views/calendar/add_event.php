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
</style>

<!-- Start popup dialog box -->
<div class="modal fade" id="event_entry_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Add New Event</h5>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="eventForm" action="<?= BASE_URL; ?>index.php?r=calendar/createEvent" method="POST">
                    <!-- Wrap inputs in a form -->
                    <div class="img-container">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="event_name">Event name</label>
                                    <input type="text" name="event_name" id="event_name" class="form-control"
                                        placeholder="Enter your event name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="event_start_date">Event start</label>
                                    <input type="date" name="event_start_date" id="event_start_date"
                                        class="form-control onlydatepicker" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="event_end_date">Event end</label>
                                    <input type="date" name="event_end_date" id="event_end_date" 
                                        class="form-control onlydatepicker" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="event_type">Event type</label>
                                    <select name="event_type" id="event_type" class="form-control" required>
                                        <option value="" disabled selected>Select event type</option>
                                        <option value="Personal">Personal</option>
                                        <option value="Academic">Academic</option>
                                        <option value="Entrepreneurship">Entrepreneurship</option>
                                        <option value="Sport">Sport</option>
                                        <option value="Volunteering">Volunteering</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="event_description">Event description</label>
                                    <input type="text" name="event_description" id="event_description"
                                        class="form-control" placeholder="Enter your event description" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Save Event Button in Modal -->
            <div class="modal-footer">
                <button type="button" class="btn custom-save-btn" onclick="save_event()">Save Event</button>
            </div>
        </div>
    </div>
</div>
<!-- End popup dialog box -->