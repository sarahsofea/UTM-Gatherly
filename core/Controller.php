<?php
// require_once ROOT_PATH . 'config/config.php'; // Ensure config is included to use VIEW_PATH

class Controller
{
    protected $layout = 'main';

    // Method to render views with layout
    public function render($view, $data = [])
    {
        extract($data);
        $content = $this->renderPartial($view, $data);
        
        // Include layout with embedded view content
        include VIEW_PATH . "layout/{$this->layout}.php";
    }

    // Method to render only the view file without layout
    protected function renderPartial($view, $data)
    {
        extract($data);
        // Start output buffering
        ob_start();
        
        // Dynamically build the path to the view file
        $controllerName = strtolower(str_replace('Controller', '', get_class($this)));
        $viewFile = VIEW_PATH . $controllerName . DIRECTORY_SEPARATOR . $view . '.php';
        
        
        if (file_exists($viewFile)) {
            include $viewFile;
            
        } else {
            echo "View file $view.php not found.";
        }
        
        // Return the captured content
        // return ob_get_clean();
        if($view != 'login'){
            return ob_get_clean();
        } 
    }
}