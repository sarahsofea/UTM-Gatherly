<?php
require_once ROOT_PATH . 'core/Controller.php';

class SiteController extends Controller
{

    public function index()
    {
        $data = ['title' => 'Home'];
        $this->render('index', $data);
    }

    public function about()
    {
        $data = ['title' => 'About'];
        $this->render('about', $data);
    }

    public function notification()
    {
        require_once ROOT_PATH . 'config/db.php';

        $sql = "
            SELECT e.event_name AS name, 
                   e.description, 
                   TIMESTAMPDIFF(HOUR, NOW(), e.start_date) AS time_left,
                   r.name AS reminder_name
            FROM event e
            JOIN reminder_type r ON e.reminder_time = r.name
            WHERE TIMESTAMPDIFF(HOUR, NOW(), e.start_date) <= r.minutes / 60
              AND TIMESTAMPDIFF(HOUR, NOW(), e.start_date) > 0
            ORDER BY e.start_date ASC";

        $result = $conn->query($sql);

        $notifications = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $notifications[] = $row;
            }
        }

        header('Content-Type: application/json');
        echo json_encode($notifications);

        $conn->close();
    }
}
