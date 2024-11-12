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

<!-- View Event Form -->
<?php include 'view_event.php'; ?>
<!-- End View Event Form -->

<!-- Update Event Form -->
<?php include 'update_event.php'; ?>
<!-- End Update Event Form -->



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
      ], 
      eventColor: '#A78A7F',

      eventClick: function(info) { //info=event yang show kat calendar
      document.getElementById("event_name_display").innerText = info.event.title;

      var startDate = moment(info.event.start).format('DD/MM/YYYY');
      document.getElementById("event_start_date_display").innerText = startDate;

      let endDate = info.event.end ? new Date(info.event.end) : null;
      if (endDate) {
        endDate.setDate(endDate.getDate() - 1); // Add one day
        endDate = moment(endDate).format('DD/MM/YYYY'); 
      }
      
      document.getElementById("event_end_date_display").innerText = endDate ? endDate : '';
      document.getElementById("event_type_display").innerText = info.event.extendedProps.type;
      document.getElementById("event_description_display").innerText = info.event.extendedProps.description;

      // Store event ID for edit and delete operations
      document.getElementById("event_details_modal").dataset.eventId = info.event.id;

      // Show the event details modal
      $('#event_details_modal').modal('show');
        
    }
    });
    
    calendar.render();
  });

  function save_event() {
    var event_name=$("#event_name").val();
    var event_start_date=$("#event_start_date").val();
    var event_end_date=$("#event_end_date").val();
    var event_type=$("#event_type").val();
    var event_description=$("#event_description").val();
    if(event_name=="" || event_start_date=="" || event_end_date=="" || event_type=="" || event_description=="")
    {
    alert("Please enter all required details.");
    return false;
    }
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

  function delete_event() {

    var eventId = document.getElementById("event_details_modal").dataset.eventId;
    
    if (confirm("Are you sure you want to delete this event?")) {
        // Proceed with deletion if confirmed
        var formData = new FormData();
        formData.append("event_id", eventId);

        fetch("<?= BASE_URL; ?>index.php?r=calendar/deleteEvent", {
            method: "POST",
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                alert(data.message); // Show a success message
                $('#event_details_modal').modal('hide'); // Hide modal after deletion
                location.reload(); // Reload the page or refresh events on the calendar
                console.log("Event deleted successfully");
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("An error occurred while deleting the event.");
        });
    }
}

  function view_event(eventId) {
      fetch("<?= BASE_URL; ?>index.php?r=calendar/fetchSingleEvent", {
            method: "POST",
            body: JSON.stringify({ event_id: eventId })
        })
        .then(response => response.json())
        .then(data => {
            // Fill modal with event data
            document.getElementById("view_event_name").textContent = data.title;
            document.getElementById("view_event_start_date").textContent = data.start;
            document.getElementById("view_event_end_date").textContent = data.end;
            document.getElementById("view_event_type").textContent = data.type;
            document.getElementById("view_event_description").textContent = data.description;

            // Show the modal
            $('#event_details_modal').modal('show');
        })
        .catch(error => console.error("Error fetching event data:", error));
  }

  function edit_event(eventId) {

    var eventId = document.getElementById("event_details_modal").dataset.eventId;

    $('#event_details_modal').modal('hide')
  
    // Fetch current event details
    fetch("<?= BASE_URL; ?>index.php?r=calendar/fetchSingleEvent", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ event_id: eventId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            // Populate the form fields with the existing event data
            document.getElementById("edit_event_id").value = data.event_id;
            document.getElementById("edit_event_name").value = data.event_name;
            document.getElementById("edit_event_start_date").value = data.start_date;
            document.getElementById("edit_event_end_date").value = data.end_date;
            document.getElementById("edit_event_description").value = data.description;
            const eventTypeDropdown = document.getElementById("edit_event_type");
            eventTypeDropdown.value = data.event_type;

            // Show the edit event modal
            $('#edit_event_modal').modal('show');
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error("Error fetching event data:", error));
}

  function update_event() {

    const eventId = document.getElementById("edit_event_id").value;
    const eventName = document.getElementById("edit_event_name").value;
    const startDate = document.getElementById("edit_event_start_date").value;
    const endDate = document.getElementById("edit_event_end_date").value;
    const eventType = document.getElementById("edit_event_type").value;
    const description = document.getElementById("edit_event_description").value;
    

    fetch("<?= BASE_URL; ?>index.php?r=calendar/updateEvent", {
      method: "POST",
      body: JSON.stringify({
        event_id: eventId,
        event_name: eventName,
        start_date: startDate,
        end_date: endDate,
        event_type: eventType,
        description: description
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.status === "success") {
        alert(data.message);
        $('#edit_event_modal').modal('hide');
        location.reload(); // Reload calendar to reflect changes
      } else {
        alert(data.message);
      }
    })
    .catch(error => console.error("Error updating event:", error));
  }
</script>

