<div class="row">
  <div class="col-md-9">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Calendar</h5>
        <hr>
        <div id="calendar" style="height: 800px;"></div>
      </div>
    </div>
  </div>

  <div class="col-md-3">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Filter</h5>
        <hr>
      </div>
    </div>
  </div>
</div>

<!-- Add custom CSS to style all buttons -->
<style>
  .fc-button {
    background-color: #8c2f39 !important;
    /* Set background color for all buttons */
    color: #fff !important;
    /* Set text color to white */
    border: none !important;
    /* Remove border */
    padding: 5px 10px;
    /* Adjust padding */
    border-radius: 5px;
    /* Rounded corners */
  }

  .fc-button:hover {
    background-color: #a0303e !important;
    /* Darker shade for hover effect */
  }

  /* Set a light grey background color for Saturdays and Sundays */
  .fc-day-sat,
  .fc-day-sun {
    background-color: #f0f0f0 !important;
    /* Light grey */
  }

  /* Adjust color for the days in month view */
  .fc-daygrid-day.fc-day-sat,
  .fc-daygrid-day.fc-day-sun {
    background-color: #f0f0f0 !important;
  }
</style>

<!-- Add Event Form -->
<?php include 'add_event.php'; ?>
<!-- End Add Event Form -->


<script>
  var fetchUrl = "<?= BASE_URL; ?>index.php?r=calendar/fetchEvent";
  var createEventUrl = "<?= BASE_URL; ?>index.php?r=calendar/createEvent";

  document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next',
        center: 'title',
        right: 'today addEventButton'
      },
      customButtons: {
        addEventButton: {
          text: 'Add Event',
          click: function () {
            $('#event_entry_modal').modal('show'); // Show the modal
          }
        }
      },
      // Fetch events from the database
      eventSources: [
        {
          url: fetchUrl, // URL to your PHP endpoint
          method: 'GET',
          failure: function (error) {
            console.error('Error fetching events:', error);
          }
        }
      ], // <-- Added a comma here

      eventClick: function(info) {
        // Show a confirmation dialog
        if (confirm("Are you sure you want to delete this event?")) {
          // If user clicks "OK", proceed to delete the event
          delete_event(info.event.id); // Pass the event ID to delete
        }
        // If "Cancel" is clicked, no action is taken
      }
    });

    calendar.render();
  });

  function save_event() {
    var formData = new FormData(document.getElementById("eventForm"));

    fetch("<?= BASE_URL; ?>index.php?r=calendar/createEvent", {
      method: "POST",
      body: formData,
    })
      .then(response => response.json())
      .then(data => {
        if (data.status === "success") {
          // Close the modal and refresh the calendar to show the new event
          $('#event_entry_modal').modal('hide');
          alert(data.message);
          location.reload();
          // calendar.refetchEvents();
          console.log("Events refetched successfully"); // Debug line // Refresh the events in FullCalendar
        } else {
          alert(data.message);
        }
      })
      .catch(error => {
        console.error("Error:", error);
        alert("An error occurred while saving the event.");
      });
  }

  function delete_event(eventId) {
    // Create a form data object for deletion
    var formData = new FormData();
    formData.append("event_id", eventId);

    fetch("<?= BASE_URL; ?>index.php?r=calendar/deleteEvent", {
      method: "POST",
      body: formData,
    })
      .then(response => response.json())
      .then(data => {
        if (data.status === "success") {
          // Remove event from calendar if deletion is successful
          alert(data.message);
          // Optionally, you can also use calendar.refetchEvents() to reload events
          location.reload();
          console.log("Event deleted successfully");
        } else {
          alert(data.message);
        }
      })
      .catch(error => {
        console.error("Error:", error);
        alert("An error occurred while deleting the event.");
      }); // <-- Closed the missing parenthesis here
  }
</script>
