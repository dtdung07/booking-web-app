<?php

class MenuController extends BaseController 
{
    public function index() 
    {
        $this->render('menu/index');
    }
    
    public function show() 
    {
        $menuId = $_GET['id'] ?? null;
        $this->render('menu/show', ['menuId' => $menuId]);
    }
    
    public function category() 
    {
        $category = $_GET['category'] ?? 'all';
        $this->render('menu/category', ['category' => $category]);
    }
}