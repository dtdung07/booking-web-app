<?php

class HomeController extends BaseController 
{
    public function index() 
    {
        $this->render('home/index');
    }
    
    public function about() 
    {
        $this->render('home/about');
    }
    
    public function notFound() 
    {
        http_response_code(404);
        $this->render('errors/404');
    }
}
