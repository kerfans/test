<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller { // 原文这里写错
    public function index()
    {
        $addess = new Address();
        $res = $addess->get_address();

        // //$this->load->view('welcome_message');
        // $data['title'] = '标题';
        // $data['num'] = '123456789';
        // //$this->cismarty->assign('data',$data); // 亦可
        // $this->assign('data',$data);
        // $this->assign('tmp','你好');
        // //$this->cismarty->display('test.html'); // 亦可
        // $this->display('welcome/index.html');
    }
}