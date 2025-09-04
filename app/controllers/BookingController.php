<?php

class BookingController extends BaseController 
{
    public function index() 
    {
        $this->render('booking/index');
    }
    
    public function create() 
    {
        $this->render('booking/create');
    }
    
    public function store() 
    {
        // Xử lý lưu booking
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Logic lưu booking ở đây
            $this->redirect('?page=booking&action=success');
        }
    }
    
    public function success() 
    {
        $this->render('booking/success');
    }
}