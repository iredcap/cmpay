<?php

/**
 *  +----------------------------------------------------------------------
 *  | 草帽支付系统 [ WE CAN DO IT JUST THINK ]
 *  +----------------------------------------------------------------------
 *  | Copyright (c) 2018 http://www.iredcap.cn All rights reserved.
 *  +----------------------------------------------------------------------
 *  | Licensed ( https://www.apache.org/licenses/LICENSE-2.0 )
 *  +----------------------------------------------------------------------
 *  | Author: Brian Waring <BrianWaring98@gmail.com>
 *  +----------------------------------------------------------------------
 */

namespace app\common\logic;
use app\common\library\enum\CodeEnum;
use think\Image;


/**
 * 文件处理逻辑
 */
class File extends BaseLogic
{

    /**
     * 上传文件
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $name
     * @param string $path_name
     *
     * @return array
     */
    public function fileUpload($name = 'file',$path_name= ''){
        $uploadObj = request()->file($name);

        $pic_dir_path = UPLOAD_PATH . $path_name;

        $fileObj = $uploadObj->move($pic_dir_path);

        $fileInfo = $fileObj->getInfo();

        $filename = $fileObj->getFilename();

        $data = [
            'name' => $filename,
            'src' =>  '/uploads/'. $path_name  . DS . $filename,
            'size' => bcdiv($fileInfo['size'], 1024, 2)
        ];

        unset($fileObj);

        return [ 'code' => CodeEnum::SUCCESS , 'msg' => 'Upload Success' ,'data' => $data];
    }

    /**
     * 图片上传
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param string $name
     * @param string $path_name
     * @param array $thumb_config
     *
     * @return array
     */
    public function picUpload($name = 'file',$path_name= '', $thumb_config = ['small' => 100, 'medium' => 500, 'big' => 1000])
    {

        $uploadObj = request()->file($name);

        $sha1  = $uploadObj->hash();

        $pic_dir_path = UPLOAD_PATH . $path_name;

        $fileObj = $uploadObj->rule('uniqid')->move($pic_dir_path);

        $save_name = $fileObj->getSaveName();

        $save_path = $pic_dir_path .  $save_name;

        $picture_dir_name = substr($save_name, 0, strrpos($save_name, DS));

        $filename = $fileObj->getFilename();

        $thumb_dir_path = $pic_dir_path . 'thumb';

        !file_exists($thumb_dir_path) && @mkdir($thumb_dir_path, 0777, true);

        //缩略图
        $imagesObj = Image::open($save_path);
        $imagesObj->thumb($thumb_config['small']   , $thumb_config['small'])
            ->save($thumb_dir_path  . DS . 'small_'  . $filename);
        $imagesObj->thumb($thumb_config['medium']  , $thumb_config['medium'])
            ->save($thumb_dir_path . DS . 'medium_' . $filename);
        $imagesObj->thumb($thumb_config['big']     , $thumb_config['big'])
            ->save($thumb_dir_path    . DS . 'big_'    . $filename);

        $data = ['name' => $filename, 'src' =>  '/uploads/'. $path_name .$picture_dir_name. DS . $filename, 'sha1' => $sha1];

        unset($fileObj);

        return [ 'code' => CodeEnum::SUCCESS , 'msg' => 'Upload Success' ,'data' => $data];
    }


    /**
     * 保存64位编码图片
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param $base64_image_content
     * @param $path_name
     *
     * @return mixed
     */
    function saveBase64Image($base64_image_content, $path_name= ''){

        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){

            //图片后缀
            $type = $result[2];
            if($type=='jpeg'){
                $type='jpg';
            }

            //保存位置--图片名
            $image_name=date('His').str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT).".".$type;

            $image_url = UPLOAD_PATH . $path_name . date('Ymd').'/' . $image_name;
            if(!is_dir(dirname($image_url))){
                mkdir(dirname($image_url), 0777, true);
                chmod(dirname($image_url), 0777);
            }
            //解码
            $decode=base64_decode(str_replace($result[1], '', $base64_image_content));
            if (file_put_contents($image_url, $decode)){
                //返回消息
                $data['code']= CodeEnum::SUCCESS;
                $data['msg'] = '保存成功';
                $data['data'] = [
                    'name'  =>  $image_name,
                    'type'  =>  $type,
                    'src'   =>  '/uploads/' . $path_name . date('Ymd') . '/'.$image_name
                ];
            }else{
                $data['code']= CodeEnum::ERROR;
                $data['msg'] ='图片保存失败！';
                $data['data'] = [];
            }
        }else{
            $data['code'] = CodeEnum::SUCCESS;
            $data['msg']='base64图片格式有误！';
            $data['data'] = [];

        }
        return $data;


    }
    /**
     * 获取指定目录下的所有文件
     * @param null $path
     * @return array
     */
    public function getFileByPath($path = null)
    {
        $dirs = new \FilesystemIterator($path);
        $arr = [];
        foreach ($dirs as $v)
        {
            if($v->isdir())
            {
                $_arr = $this->getFileByPath($path ."/". $v->getFilename());
                $arr = array_merge($arr,$_arr);
            }else{
                $arr[] = $path . "/" . $v->getFilename();
            }
        }
        return $arr;
    }

    /**
     * 上传公钥
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function uploadRsaPublicKey(){

    }
}