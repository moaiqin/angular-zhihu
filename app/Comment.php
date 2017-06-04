<?php
/**
 * Created by PhpStorm.
 * User: moshaobu
 * Date: 2017/4/8
 * Time: 15:37
 */
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\Request;
class Comment extends Model{
    public $timestamps=true;
    protected function getDateFormat()
    {
        return time();
    }

    /*添加评论api*/
    public function add(){
        if(!user_ins()->login_or_out())
            return ['status'=>0,'msg'=>'login require'];
        /*
         * 检查是否id
         * */
        if(!rq('question_id')&&!rq('answer_id'))
            return ['status'=>0,'msg'=>'question_id or answer_id need'];
        /*
         *检查是否question_id和answer_id都存在
         * */
        if(rq('question_id')&&rq('answer_id'))
            return ['status'=>0,'msg'=>'answer_id and question_id not to all set'];

        /*
         * 查找question或answer是否存在且写如数据
         * */
        if(rq('question_id')){
            $question=question_ins()->find(rq('question_id'));
            if(!$question)
                return ['status'=>0,'msg'=>'question not found'];
            $this->question_id=rq('question_id');
        }else{
            $answer=answer_ins()->find(rq('answer_id'));
            if(!$answer)
                return ['status'=>0,'msg'=>'answer not found'];
            $this->answer_id=rq('answer_id');
        }
        /*
         * 检查content的存在
         * */
        if(!rq('content'))
            return ['status'=>0,'msg'=>'content required'];
        $this->content=rq('content');
        /*
         * 是否是回复api
         * */
        if(rq('reply_to')){
            $target=$this->find(rq('reply_to'));
            if(!$target)
                return ['status'=>0,'msg'=>'target not exists'];
            /*
             * 用户本身不能回复自己
             * */
            if($target->user_id==session('user_id'))
                return ['status'=>0,'msg'=>'cannot reply yurself'];
            $this->reply_to=rq('reply_to');
        }
        $this->user_id=session('user_id');
        /*
         * 保存数据
         * */
        return $this->save()?
            ['status'=>1,'id'=>$this->id]:
            ['status'=>0,'msg'=>'save failed'];
    }

    /*
     * 删除api
     * */
    public function read(){
        /*
         * 判断查看的问题question_id或answer_id是否存在
         * */
        if(!rq('question_id')&&!rq('answer_id'))
            return ['status'=>0,'msg'=>'question_id or answer_id all need'];

        /*
         *对数据处理
         * */
        if(rq('question_id')){
            /*查看问题是否存在
             * */
            $question=question_ins()->find(rq('question_id'));
            if(!$question)
                return ['status'=>0,'msg'=>'question not exists'];
            /*找出问题的所有评论
             * */
           $data=$this
               ->with('user')
               ->where('question_id',rq('question_id'))
               ->get();
        }else{
            $answer=answer_ins()->find(rq('answer_id'));
            if(!$answer)
                return ['status'=>0,'msg'=>'answer is not extists'];
            $data=$this
                ->with('user')
                ->where('answer_id',rq('answer_id'))
                ->get();
        }

        return ['status'=>1,'data'=>$data];
    }
   /*
    * 删除评论
    * */
    public function remove(){
        if(!user_ins()->login_or_out())
            return ['status'=>0,'msg'=>'login required'];
        if(!rq('id'))
            return ['status'=>0,'msg'=>'id required'];
        /*查该数据是否存在
         * */
        $comment=$this->find(rq('id'));
        if(!$comment)
            return ['status'=>0,'mag'=>'comment not exitsts'];
        if($comment->user_id!=session('user_id'))
            return ['status'=>0,'msg'=>'you no promission'];
        /*删除
         * */
        $this->where('reply_to',rq('id'))->delete();
        return $comment->delete()?
            ['status'=>1,'msg'=>'delete success']:
            ['status'=>0,'mag'=>'delete failed'];

    }
    public function user(){
        return $this->belongsTo('App\User');
    }

}