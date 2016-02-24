<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	$price = $this->randFloat();
       	$this->display('main');
    }
    
}