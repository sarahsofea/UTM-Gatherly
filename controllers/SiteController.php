<?php
require_once ROOT_PATH . 'core/Controller.php';

class SiteController extends Controller
{
    public function login()
    {   
        require_once ROOT_PATH . 'config/db.php'; // Include your database connection settings

        $error = ''; // Variable to store any error messages

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            echo 'hi';
            exit;
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Prepare SQL statement to fetch the user by username
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
        

            // Check if the user exists
            if ($user) {
                // Verify the password
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id']; // Set the user ID in session
                    header("Location: " . BASE_URL . "index.php?r=site/index");
                    exit;
                } else {
                    $error = "Invalid password.";
                }
            } else {
                $error = "Invalid username.";
            }
        }

        $data = ['title' => 'UTM Gatherly', 'error' => $error];
        $this->renderPartial('login', $data);
    }

    public function index()
    {
        // Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            // If not logged in, redirect to the login page
            header("Location: " . BASE_URL . "index.php?r=site/login");
            exit; // Stop further execution
        }

        // If logged in, proceed to render the home page
        $data = ['title' => 'Home'];
        $this->render('index', $data);
    }

    public function logout()
    {
        session_destroy();
        header("Location: " . BASE_URL . "index.php?r=site/login");
        exit;
    }

    public function about()
    {

        $data = ['title' => 'About'];
        $this->render('about', $data);
    }

}