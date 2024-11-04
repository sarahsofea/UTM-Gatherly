<?php
require_once ROOT_PATH . 'core/Controller.php';

class CalendarController extends Controller
{

    public function index()
    {
           $this->render('index');
    }

    public function fetchEvent()
    {
        require_once ROOT_PATH . 'config/db.php';

        $sql = "SELECT * FROM event";
        $result = $conn->query($sql);
        $event = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Adjust the array to match FullCalendar's event structure
                $event[] = [
                    'id' => $row['event_id'],  
                    'title' => $row['event_name'],
                    'start' => $row['start_date'],  // Adjust these to match your column names
                    'end' => $row['end_date'],
                    'description' => $row['description'] ?? '', // Optional additional fields
                ];
            }
        }

        $conn->close();

        header('Content-Type: application/json');
        echo json_encode($event);
    }

    public function createEvent()
    {
        require_once ROOT_PATH . 'config/db.php'; // Ensure $conn is defined for the database connection

        // Check if the request is a POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $event_name = $_POST['event_name'];
            $start_date = $_POST['event_start_date'];
            $end_date = $_POST['event_end_date'];
            $description = $_POST['event_description'];

            // Insert data into the event table
            $stmt = $conn->prepare("INSERT INTO event (event_name, start_date, end_date, description) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $event_name, $start_date, $end_date, $description);

            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Event created successfully"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error creating event"]);
            }

            $stmt->close();
        }
        $conn->close();
    }

    public function deleteEvent()
{
    require_once ROOT_PATH . 'config/db.php'; // Ensure $conn is defined for the database connection

    // Check if the request is a POST and if the event_id is provided
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
        $event_id = $_POST['event_id'];

        // Prepare and execute the SQL delete statement
        $stmt = $conn->prepare("DELETE FROM event WHERE event_id = ?");
        $stmt->bind_param("i", $event_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Event deleted successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error deleting event"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid request"]);
    }

    $conn->close();
}

}
