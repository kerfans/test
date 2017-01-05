<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller { // 原文这里写错

    function __construct()
    {
        header("Content-type:text/html;charset=utf-8");
        parent::__construct();
        $this->load->model('test_model');
    }
    function index()
    {

        $addess = new Address();
        $res = $addess->get_address();
        var_dump($res);
    }

    /**
     * @name 获取订单信息
     * @param string
     * @date 2016-11-18
     * @return json $hemsJson
     */
    function  now_order()
    {
        header("Content-type:text/html;charset=utf-8");
        $go_ip = $this->getIP();
        if($go_ip != '61.149.46.138')
        {
            return false;
        }
        $start_time = (time()-300);
        $end_time = time();
        $data = $this->test_model->orders($start_time,$end_time);
        $res['order_message'] = $data;
        echo '<pre>';
        var_dump($res);die;

    }

    function test()
    {
        header("Content-type:text/html;charset=utf-8");
        $redis = new Redis();
        $redis->pconnect('192.168.10.22', '6379', 0);

        if(!$redis->exists('man'))
        {
            $start = 0;
            $end = 100;
            $data = $this->test_model->orders($start,$end);
            $save = serialize($data);
            $redis->set('man',$save);
            $redis->expire('man', 60);
            echo '数据库中';
        }else{
            $res = $redis->get('man');
            $data = unserialize($res);
//            $redis->delete('man');
            echo '缓存中';
        }
        var_dump($data);
    }

    function my_redis()
    {
        $redis = new Myredis();
        $data = $this->test_model->orders(0,20);
        $save = serialize($data);
//        $res = $redis->lpush('two',$save);
        $res = $redis->lranges('two',0,100);
        var_dump($res);
    }
    //根据IP获取省份， 城市信息
    function get_address($ip,$ak)
    {
        $url = "http://api.map.baidu.com/location/ip?ak=".$ak."&coor=bd09ll&ip=".$ip."";
        $res = $this->curl_get( $url);
        $res = json_decode($res);
        var_dump($res);
    }
    //根据IP获取经纬度
    function get_addre($ip='61.149.46.138',$ak='K2RTFGsBXGdwCXGj2gO8SprVQyDfCdMP')
    {
        $url = "http://api.map.baidu.com/highacciploc/v1?qcip=".$ip."&qterm=pc&ak=".$ak."&coord=bd09ll";
        $res = $this->curl_get($url);
        $res = json_decode($res);
        var_dump($res);
    }
    //根据经纬度获取详细地址到街道
    function get_street($lat='39.840343',$lng='116.288304',$ak='K2RTFGsBXGdwCXGj2gO8SprVQyDfCdMP')
    {
        $url = "http://api.map.baidu.com/geocoder/v2/?&location=".$lat.",".$lng."&output=json&pois=1&ak=".$ak."";
        $res = $this->curl_get($url);
        $res = json_decode($res);
        var_dump($res);
    }
    private function curl_post($url = '', $data = '') {
        $ch = curl_init();
        //curl_setopt($ch, CURLOP_TIMEOUT, '30');//设置超时
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $return_json = curl_exec($ch);
        curl_close($ch);
        return $return_json;
    }

    private function curl_get($url='')
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * @name 获取当天门店销售统计
     * @param string
     * @date 2016-11-18
     * @return
     */
    function shopper_num()
    {
        $go_ip = $this->getIP();
        if($go_ip != '61.149.46.138')
        {
            return false;
        }
        date_default_timezone_set('PRC');
        $y = date("Y");
        //获取当天的月份
        $m = date("m");
        //获取当天的号数
        $d = date("d");
        $start_time= mktime(0,0,0,$m,$d,$y);
        $start_time = date('Y-m-d H:i:s',$start_time);
        $end_time = date('Y-m-d H:i:s',time());
        $data = $this->echartsmodel->shopper_num($start_time,$end_time);
        return $data;
    }
    //获取访问用户的ip地址
    function getIP()
    {
        global $ip;
        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if(getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if(getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else
            $ip = "Unknow";
        return '61.149.46.138';
    }

}