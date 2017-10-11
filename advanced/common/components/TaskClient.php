<?php
/**
 * Created by PhpStorm.
 * User: hisammy
 * Date: 2016/4/15
 * Time: 10:02
 */

namespace common\components;


class TaskClient{


    private $client;
    private $fp;

    public function __construct() {
        $this->client = new \swoole_client(SWOOLE_SOCK_TCP|SWOOLE_KEEP);
        $this->connect();
    }

    public function connect() {
        $this->fp = $this->client->connect('127.0.0.1', 9502 , 1);

        if( !$this->fp ) {
            echo "Error: {$this->fp->errMsg}[{$this->fp->errCode}]\n";
            return;
        }
    }

    /*
     * 发送任务给task系统
     * $type 任务ID
     * $params  参数数组
     *
     */
    public function sendData($type,$params){

        if(!is_array($params)){
            throw new Exception('参数 不能为空');
        }

        $data = [
            'type'=>$type,
            'params' =>$params,
        ];

        $json_data = json_encode($data);

        return $this->client->send( $json_data );

    }

    public function close(){

        $this->client->close();
    }
}