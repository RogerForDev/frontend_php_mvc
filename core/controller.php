<?php

class controller {
        
    private $users;
    
    public function __construct() {
        $this->users = new User(Connection::getInstance()->getConnection());
    }
    public function loadView($viewName, $viewData = array()) {
        extract($viewData);
        include 'views/'.$viewName.'.php';
    }
    
    public function loadtemplate($viewName, $viewData = array()) {               
        $user = array();
        $user = $this->users->getUser();        
        extract($user);
        include 'views/template.php';
    }
    
    public function loadViewInTemplate($viewName, $viewData) {
        extract($viewData);
        include 'views/'.$viewName.'.php';
    }
}
