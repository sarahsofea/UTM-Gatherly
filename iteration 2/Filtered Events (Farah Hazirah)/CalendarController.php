<?php
require_once ROOT_PATH . 'core/Controller.php';

class CalendarController extends Controller
{

    public function index()
    {
           $this->render('index');
    }

    public function fetchEvent()//event showed in calendar
    {
        require_once ROOT_PATH . 'config/db.php';

        // Retrieve the type parameter
        $type = isset($_GET['type']) ? $_GET['type'] : null;

        // Initialize an empty array for events
        $event = [];

        $colour = ['Personal' => '#42a5f5', 'Academic' => '#ec407a', 'Sport' => '#ffb300', 'Entrepreneurship' => '#66bb6a', 'Volunteering' => '#ff7043'];

        // Check if type is provided
        if ($type !== null) {
            // Prepare the SQL query to filter by event_type
            $sql = "SELECT * FROM event WHERE event_type = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $type); // Bind the type parameter to the query
            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch the results and format them for FullCalendar
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $event[] = [
                        'id' => $row['event_id'], // Unique identifier for the event
                        'title' => $row['event_name'], // Event title
                        'start' => $row['start_date'], // Start date of the event
                        'end' => date('Y-m-d', strtotime('+1 day', strtotime($row['end_date']))), // Adjusted end date
                        'type' => $row['event_type'], // Event type
                        'description' => $row['description'] ?? '', // Optional description
                        'backgroundColor' => $colour[$type],
                    ];
                }
            }
            $stmt->close();
        } else {
            // If no type is provided, return an error message
            echo json_encode(['error' => 'Type parameter is required.']);
            $conn->close();
            exit;
        }

        // Close the database connection
        $conn->close();

        // Return the JSON response
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
            $event_type = $_POST['event_type'];
            $description = $_POST['event_description'];

            // Insert data into the event table
            $stmt = $conn->prepare("INSERT INTO event (event_name, start_date, end_date, event_type, description) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $event_name, $start_date, $end_date, $event_type, $description);

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

    public function fetchSingleEvent()//event showed in modal
    {
        require_once ROOT_PATH . 'config/db.php';

        // Decode the incoming JSON data and add debugging output
        $data = json_decode(file_get_contents("php://input"));

        // Check if event_id exists and is not null
        if (!isset($data->event_id) || empty($data->event_id)) {
            echo json_encode(["status" => "error", "message" => "Invalid event ID received"]);
            return;
        }

        $event_id = $data->event_id;

        $stmt = $conn->prepare("SELECT * FROM event WHERE event_id = ?");
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $eventData = $result->fetch_assoc();
            echo json_encode([
                "status" => "success",
                "event_id" => $eventData['event_id'],
                "event_name" => $eventData['event_name'],
                "start_date" => $eventData['start_date'],
                "end_date" => $eventData['end_date'],
                "event_type" => $eventData['event_type'],
                "description" => $eventData['description']
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Event not found"]);
        }

        $stmt->close();
        $conn->close();
    }

    public function updateEvent() {

        require_once ROOT_PATH . 'config/db.php';

        $data = json_decode(file_get_contents("php://input"));

        $stmt = $conn->prepare("UPDATE event SET event_name = ?, start_date = ?, end_date = ?, event_type = ?, description = ? WHERE event_id = ?");
        $stmt->bind_param("sssssi", $data->event_name, $data->start_date, $data->end_date, $data->event_type, $data->description, $data->event_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Event updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error updating event"]);
        }

        $stmt->close();
        $conn->close();

    }

    //FILTER
    public function fetchEventFilter()
{
    require_once ROOT_PATH . 'config/db.php';

    // Parse the POST request body
    $input = json_decode(file_get_contents('php://input'), true);
    $filters = isset($input['filters']) ? $input['filters'] : [];

    $sql = "SELECT * FROM event";

    if (!empty($filters)) {
        $placeholders = implode(',', array_fill(0, count($filters), '?'));
        $sql .= " WHERE event_type IN ($placeholders)";
    }

    $stmt = $conn->prepare($sql);

    if (!empty($filters)) {
        $stmt->bind_param(str_repeat('s', count($filters)), ...$filters);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $events = [];
    while ($row = $result->fetch_assoc()) {
        $events[] = [
            'id' => $row['event_id'],
            'title' => $row['event_name'],
            'start' => $row['start_date'],
            'end' => date('Y-m-d', strtotime('+1 day', strtotime($row['end_date']))),
            'type' => $row['event_type'],
            'description' => $row['description'] ?? '',
        ];
    }

    $stmt->close();
    $conn->close();

    header('Content-Type: application/json');
    echo json_encode([
        'status' => !empty($events) ? 'success' : 'error',
        'event' => $events,
    ]);
}

}
