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
}