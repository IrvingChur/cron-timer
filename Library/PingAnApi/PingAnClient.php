<?php

namespace Library\PinganApi;


use Library\Common\ConfigureLibrary;

class PingAnClient
{
    const ACCESS_PUSH_MESSAGE = '/api/access/push/message';

    private static $instance = null;
    protected $host = '';
    protected $client_id = '';
    protected $secret = '';

    //推送字段
    protected $fileds = [
        'author_id'=>'user_id|no_request|string',
        'author_name'=>'nickname|request|string',
        'author_followers'=>'|no_request|integer',
        'author_fans'=>'|no_request|integer',
        'origin_category'=>'category_name|request|string',
        'origin_id'=>'id|request|integer',
        'title'=>'title|request|string',
        'cover'=>'cover|request|array',
        'content'=>'content|request|string',
        'publish_time'=>'created|no_request|datetime',
        'is_origin'=>'source_type|request|integer', //是否原创 1是 2 否 0未知 是；source_type：1为原创，2为首发，3为转载
        'tags'=>'tags|no_request|string',
        'url'=>'|no_request|string',
        'area_province'=>'|no_request|string',
        'area_city'=>'|no_request|string',
        'area_county'=>'|no_request|string',
        'page_views'=>'viewnum|no_request|string',
        'comment_count'=>'commentnum|no_request|string',
        'member_id'=>'|no_request|string',
        'extend'=>'|no_request|array',
    ];

    private function __construct()
    {
        $configure   = ConfigureLibrary::getConfigure('Configure\SystemConfigure')['pinganConfigure'];
        $this->host = $configure['host'] ?? '';
        $this->client_id = $configure['client_id'] ?? '';
        $this->secret = $configure['secret'] ?? '';
    }

    protected function __clone(){}

    public static function getInstance()
    {
        ///判断$instance是否是Client的对象，不是则创建
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 文章推送
     * @param $article array 文章内容
     * @return mixed
     */
    public function articlePush($article){

        $data= $this->articleToPingAn($article);
        $timestamp = time();
        $params=[
            'client_id'=>$this->client_id,
            'timestamp'=>$timestamp,
            'data'=>$data,
            'sign'=>$this->getSign($data,$timestamp),
        ];

        $res = $this->curl(PingAnClient::ACCESS_PUSH_MESSAGE,$params);
        $res = json_decode($res,true);

        return $res;
    }

    /**
     * 文章字段转换
     * @param $article
     * @return array|false|string
     */
    public function articleToPingAn($article){

        $data = [];
        foreach ($this->fileds as $key => $value){
            $item = explode('|',$value);
            $filed = $item['0'];
            $is_request = $item['1'];
            $type = $item['2'];
            if(empty($key)){
                continue;
            }
//            if($is_request == 'request' && !isset($article[$filed])){
//                //错误提示
//                echo '必须字段请传入：'.$key;
//                return false;
//            }s
            if(isset($article[$filed])){
                if($type == 'array'){
                    $data[$key][] = $article[$filed];
                }else{
                    $data[$key] = $article[$filed];
                }
                //额外字段判断
                if($key == 'is_origin'){
                    $data[$key] = in_array($article[$filed],[1,2]) ? 1 : 0;
                }
            }
        }

        $data = json_encode($data);
        return $data;
    }

    /**
     * 获取签名
     * @param $data string 内容
     * @param $timestamp int 时间戳
     * @return string
     */
    private function getSign($data, $timestamp)
    {
        //md5
        $sign = md5(sprintf('%s|%s|%s', $this->secret, $data,$timestamp));
        return $sign;
    }


    /**
     * 发起 server 请求
     * @param $action
     * @param $params
     * @param $httpHeader
     * @return mixed
     */
    private function curl($action, $params) {
        $action = 'https://'.$this->host.$action;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $action);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false); //处理http证书问题
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ret = curl_exec($ch);
        if (false === $ret) {
            $ret =  curl_errno($ch);
        }
        curl_close($ch);
        return $ret;
    }

}