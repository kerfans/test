<?php
if(!defined('BASEPATH')) EXIT('No direct script asscess allowed');

class Address extends Smarty {
    private $ak = 'K2RTFGsBXGdwCXGj2gO8SprVQyDfCdMP';           //百度开发者ak密匙
    private $ip;        //IP地址
    private $lat;       //经度
    private $lng;       //纬度

    function __construct()
    {
        $this->ip = $this->getIP();
    }

    //根据IP获取省份， 城市信息(默认为当前访问ip，可传入ip地址)
    //注意：只能定位到城市，无法定位到具体街道
    function get_address($ip='')
    {
        $ip = empty($ip) ? $this->ip : $ip;
        $ak = $this->ak;
        $url = "http://api.map.baidu.com/location/ip?ak=".$ak."&coor=bd09ll&ip=".$ip."";
        $res = $this->curl_get( $url);
        $res = json_decode($res);
        var_dump($res);
    }

    //根据IP获取经纬度并定位到街道(高精度，适合电脑wifi访问)(默认为当前访问ip，可传入ip地址)
    function get_addre($ip='')
    {
        $ip = empty($ip) ? $this->ip : $ip;
        $ak = $this->ak;
        $url = "http://api.map.baidu.com/highacciploc/v1?qcip=".$ip."&qterm=pc&ak=".$ak."&coord=bd09ll";
        $res = $this->curl_get($url);
        $res = json_decode($res);
        $this->lat = $res->content->location->lat;
        $this->lng = $res->content->location->lng;
        $ren = $this->get_street($this->lat,$this->lng);
        var_dump($ren);
    }

    //根据经纬度获取详细地址到街道(默认为当前访问经纬度，可传入经纬度)
    function get_street($lat='',$lng='')
    {
        $ak = $this->ak;
        $lat = empty($lat) ? $this->lat : $lat;
        $lng = empty($lng) ? $this->lng : $lng;
        $url = "http://api.map.baidu.com/geocoder/v2/?&location=".$lat.",".$lng."&output=json&pois=1&ak=".$ak."";
        $res = $this->curl_get($url);
        $res = json_decode($res);
        return $res;
    }

    //获取当前访问用户的IP地址
    private function getIP()
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
        //return $ip;
        return '61.149.46.138';
    }

    //curl post提交
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

    //curl get提交
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
}
