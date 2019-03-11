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


namespace app\admin\controller;
use app\common\library\enum\CodeEnum;

/**
 * 文章控制器
 */
class Article extends BaseAdmin
{

    /**
     * 文章列表
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 通知公告
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function notice(){

        return $this->fetch();
    }

    /**
     * 文章列表
     * @url /article/getList?page=1&limit=10&id=8&author=&title=
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function getList(){
        $where = [];

        !empty($this->request->param('id')) && $where['id']
            = ['eq', $this->request->param('id')];

        !empty($this->request->param('author')) && $where['author']
            = ['like', '%'.$this->request->param('author').'%'];

        !empty($this->request->param('title')) && $where['title']
            = ['like', '%'.$this->request->param('title').'%'];

        //时间搜索  时间戳搜素
        $where['create_time'] = $this->parseRequestDate();

        $data = $this->logicArticle->getArticleList($where, true, 'create_time desc', false);

        $count = $this->logicArticle->getArticleCount($where);

        $this->result($data || !empty($data) ?
                [
                    'code' => CodeEnum::SUCCESS,
                    'msg'=> '',
                    'count'=>$count,
                    'data'=>$data
                ] : [
                    'code' => CodeEnum::ERROR,
                    'msg'=> '暂无数据',
                    'count'=>$count,
                    'data'=>$data
            ]
        );
    }

    /**
     * 通知列表
     * @url /article/getNoticeList?page=1&limit=10&id=8&author=&title=
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function getNoticeList(){
        $where = [];

        !empty($this->request->param('id')) && $where['id']
            = ['eq', $this->request->param('id')];

        !empty($this->request->param('author')) && $where['author']
            = ['like', '%'.$this->request->param('author').'%'];

        !empty($this->request->param('title')) && $where['title']
            = ['like', '%'.$this->request->param('title').'%'];

        //时间搜索  时间戳搜素
        $where['create_time'] = $this->parseRequestDate();

        $data = $this->logicArticle->getNoticeList($where, true, 'id desc', false);

        $count = $this->logicArticle->getNoticeCount($where);

        $this->result($data || !empty($data) ?
                [
                    'code' => CodeEnum::SUCCESS,
                    'msg'=> '',
                    'count'=>$count,
                    'data'=>$data
                ] : [
                    'code' => CodeEnum::ERROR,
                    'msg'=> '暂无数据',
                    'count'=>$count,
                    'data'=>$data
            ]
        );
    }

    /**
     * 文章添加
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function add()
    {
        
        $this->articleCommon();
        
        return $this->fetch();
    }

    /**
     * 通知添加
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function addNotice()
    {

        $this->noticeCommon();

        return $this->fetch();
    }

    /**
     * 文章编辑
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function edit()
    {
        $this->articleCommon();
        
        return $this->fetch();
    }

    /**
     * 通知编辑
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @return mixed
     */
    public function editNotice()
    {

        $this->noticeCommon();


        return $this->fetch();
    }


    /**
     * 删除文章
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param int $id
     *
     */
    public function delArticle($id = 0) {

        $this->result($this->logicArticle->delArticle(['id' => $id]));
    }

    /**
     * 删除通知
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     * @param int $id
     *
     */
    public function delNotice($id = 0) {

        $this->result($this->logicArticle->delNotice(['id' => $id]));
    }

    /**
     * 文章添加与编辑通用方法
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function articleCommon()
    {
        $this->request->isPost() && $this->result($this->logicArticle->saveArticle($this->request->post()));

        $article = $this->logicArticle->getArticleInfo(['id' => $this->request->param('id')]);

        !empty($article) && $article['img_ids_array'] = str2arr($article['img_ids']);

        $this->assign('article',$article);
    }

    /**
     * 通知添加与编辑通用方法
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function noticeCommon()
    {
        $this->request->isPost() && $this->result($this->logicArticle->saveNotice($this->request->post()));

        $notice = $this->logicArticle->getNoticeInfo(['id' => $this->request->param('id')]);

        $this->assign('notice',$notice);
    }

    /**
     * 数据状态设置
     */
    public function changeStatus()
    {
        $this->result($this->logicArticle->setStatus('Article', $this->request->param()));
    }

    /**
     * 文章图片上传
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function uploadPicture(){
        $this->request->isPost() && $this->result($this->logicFile->picUpload('pic','attachments/'));
    }

    /**
     * 文章附件上传
     *
     * @author 勇敢的小笨羊 <brianwaring98@gmail.com>
     *
     */
    public function uploadAttach(){
        $this->request->isPost() && $this->result($this->logicFile->fileUpload('file','attachments'));
    }
}
