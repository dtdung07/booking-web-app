<?php

class ContactController extends BaseController 
{
    public function index() 
    {
        $this->render('contact/index');
    }
    
    public function send() 
    {
        // Xử lý gửi liên hệ
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Logic gửi email liên hệ ở đây
            $this->render('contact/success');
        } else {
            $this->redirect('?page=contact');
        }
    }
}