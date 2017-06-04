<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public $timestamps=true;
    protected function getDateFormat(){
        return time();
    }

    public function add(){

        if(!user_ins()->login_or_out())
            return ['status'=>2,'msg'=>'login required'];
        if(!rq('title'))
            return ['status'=>1,'meg'=>'title required'];
        $this->title=rq('title');
        if(rq('desc'))
            $this->desc=rq('desc');
        $this->user_id=session('user_id');

        $save_ok=$this->save();
        if(!$save_ok)
            return ['status'=>0,'id'=>$this->id];
        return ['status'=>1,'msg'=>'save ok'];

    }

    /*更新问题api*/
    public function change(){
        /*检查是否登陆*/
        if(!user_ins()->login_or_out())
            return ['status'=>0,'msg'=>'you have no login'];
        $id=rq('id');
        $question=$this->find($id);

        if(!$question)
            return ['status'=>0,'msg'=>'date not find'];

        if($question->user_id!=session('user_id'))
            return ['status'=>0,'msg'=>'you have no promiss to change'];
        if(rq('title'))
            $question->title=rq('title');
        if(rq('desc'))
            $question->desc=rq('desc');
        /*保存数据*/
        return $question->save()?
            ['status'=>1,'msg'=>'update data success']:
            ['status'=>0,'msg'=>'update date failed'];

    }
    /*查看数据*/
    public function read(){
        /*
         * 查询单个问题详情
         * */
        if(rq('id')) {
            $oneData = $this
                ->with('user')
                ->with('get_question_user_answers')
                ->find(rq('id'));
            if(!$oneData)
                return ['status' => 0, 'msg' =>'no found question'];
            return ['status' => 1, 'data' => $oneData];
        }

        //查看用户提出的问题
        if(rq('user_id')) {
            $user_id = rq('user_id') == 'self' ? session('user_id') : rq('user_id');
            $question_user = $this->where('user_id','=',$user_id)->get()->keyBy('id');
            if (!$question_user) {
                return ['stat'=>0,'msg'=>'null answers'];
            }else{
                return ['status'=>1,'data'=>$question_user->toArray()];
            }
        }
        /*
         * 读取所有的问题
         * */
        list($article_limit,$skip)=paginate(rq('page'),rq('limit'));
        $data=$this
            ->orderBy('created_at','desc')
            ->limit($article_limit)
            ->skip($skip)
            ->select('title','id','created_at','desc','user_id')//这个也可以用get（['title','id','created_at','desc','user_id']）；代替
            ->get()
            ->keyBy('id');
        if(!$data)
            return ['status'=>0,'have no data'];
        return  array('status' => '1', 'data' => $data);

    }

    /*
     * 查看用户提出的问题
     * */
    /*public function read_question_by_user_id(){
        if(!rq('user_id'))
            return ['status'=>0,'msg'=>'user_id required'];
        if(rq('user_id')) {
            $user_id = rq('user_id') == 'self' ? session('user_id') : rq('user_id');
            $question_user = $this->where('user_id','=',$user_id)->get()->keyBy('id');
            if (!$question_user) {
                return ['stat'=>0,'msg'=>'null answers'];
            }else{
                return ['status'=>1,'data'=>$question_user];
            }
        }
    }*/
    /*
     * 删除问题api*/
    public function remove(){
        if(!user_ins()->login_or_out())
            return ['status'=>0,'msg'=>'you not logined'];
        if(!rq('id'))
            return ['status'=>0,'msg'=>'not id'];
        $question=$this->find(rq('id'));
        if(!$question)
            return ['status'=>0,'msg'=>'no question'];
        if(session('user_id')!=$question->user_id)
            return ['status'=>0,'msg'=>'not prossion'];

        return $question->delete()?
            ['status'=>1,'msg'=>'delete data success']:
            ['status'=>0,'msg'=>'delete failed'];

    }
    public  function user(){
        return $this->belongsTo('App\User');
    }
    public function answers(){
        return $this->hasMany('App\Answer');
    }
    public function get_question_user_answers(){
        return $this
            ->answers()
            ->with('users')//会从App\answer下面找到users
            ->with('user');
    }

}
