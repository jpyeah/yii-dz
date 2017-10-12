<?php

namespace backend\controllers;
use Yii;
use yii\web\Response;

class WechatController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    protected function jsonSuccess($data=[], $message='', $code=1, $share=array())
    {
        $message = $message ? $message : '调用成功';
        $this->jsonEncode(true, $data, $message, $code, $share);
    }

    /**
     * api返回的json
     * @param $status
     * @param $code
     * @param $message
     * @param $data
     * @param array $share
     * @return string
     * @apiVersion 1.0
     */
    protected function jsonEncode($status, $data=[], $message='', $code=1, $share=[])
    {
        $status     = boolval($status);
        $data       = $data ? $data : (object)array();
        $message    = strval($message);
        $code       = intval($code);
        $share      = $share ? $share : (object)array();

        $result = [
            'status'     => $status,
            'code' => $code,
            'message'    => $message,
            'data'       => $data,
            'share'      => $share,
        ];

        //设置响应对象
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        $response->data = $result;
    }


    public function actionSetWechatMenu(){


        $menu =Yii::$app->wechat->menu;

        if(Yii::$app->request->isPost){
            $buttons = Yii::$app->request->post();

            $res =  $menu->add($buttons['button']);

            return $this->jsonSuccess(array(),'提交成功');

        }else{

            $menus = $menu->current();

            $menus_info =  $menus['selfmenu_info'];

            foreach( $menus_info['button'] as $k => $val){


                // print_r($menus['selfmenu_info']['button'][$k]['sub_button']);exit;
                if( @$val['sub_button'] && $val['sub_button']['list']){
                    $menus_info['button'][$k]['sub_button'] = $val['sub_button']['list'];

                    foreach($menus_info['button'][$k]['sub_button'] as $j => $h){

                        $menus_info['button'][$k]['sub_button'][$j]['sub_button']=array();
                        $menus_info['button'][$k]['sub_button'][$j]['key']="";
                    }
                }else{
                    $menus_info['button'][$k]['sub_button'] = array();
                }

                $menus_info['button'][$k]['key']="";

            }
            return  $this->render('setwechatmenu',['menu'=> $menus_info]);

        }


    }

}
