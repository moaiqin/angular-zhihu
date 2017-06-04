<?php
/**
 * Created by PhpStorm.
 * User: moshaobu
 * Date: 2017/4/8
 * Time: 13:15
 */
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\Request;

class Answer extends Model{
    public $timestamps=true;
    protected function getDateFormat()
    {
        return time();
    }
    protected function  asDateTime($value)
    {
        return parent::asDateTime($value); // TODO: Change the autogenerated stub
    }

    public function add(){
        if(!user_ins()->login_or_out())
            return ['status'=>0,'msg'=>'login required'];
        if(!rq('question_id'))
            return ['status'=>0,'msg'=>'need question id'];
        if(!rq('content')){
            return ['status'=>0,'msg'=>'need content can do action'];
        }

        $answered=$this
            ->where(['user_id'=>session('user_id'),'question_id'=>rq('question_id')])
            ->count();
        if($answered)
            return ['status'=>0,'msg'=>'you have answerd'];
        $answer=$this;
        $answer->question_id=rq('question_id');
        $answer->user_id=session('user_id');
        $answer->content=rq('content');

        return $answer->save()?
            ['status'=>1,'id'=>$this->id]:
            ['status'=>0,'msg'=>'add answer failed'];

    }

    public  function change(){
        if(!user_ins()->login_or_out())
            return ['status'=>0,'msg'=>'login required'];
        if(!rq('id')||!rq('content'))
            return ['status'=>0,'msg'=>'id ang content require'];
        $answer=$this->find(rq('id'));
        if(!$answer)
            return ['status'=>0,'msg'=>'question no exists'];
        if($answer->user_id!=session('user_id'))
            return ['status'=>0,'msg'=>'have no prossiom'];
        $answer->content=rq('content');

        return $answer->save()?
            ['status'=>1,'msg'=>'update answer success']:
            ['status'=>0,'msg'=>'update answer failed'];

    }

    public  function remove(){
        if(!rq('id'))
            return ['status'=>0,'msg'=>'id require'];
        $answer=$this->find(rq('id'));
        if(!$answer)
            return ['status'=>0,'msg'=>'answer not found'];

        $answer->users()
            ->newPivotStatement()//就是引用了vote库
            ->whereRaw('answer_id=?',[rq('id')])
            ->delete();

        return $answer->delete()?
            ['status'=>1]:
            ['status'=>0,'msg'=>'service error'];
    }

    public  function read(){

        if(!rq('id')&&!rq('question_id')&&!rq('user_id'))
            return ['status'=>0,'msg'=>'id,user_id and question_d  require'];

        /*
         * 读取用户所有的回答
         * */
        if(rq('user_id')) {
            $user_id = rq('user_id') == 'self' ? session('user_id') : rq('user_id');
            $user_answers = $this
                ->with('question')
                ->with('users')
                ->where('user_id','=',$user_id)
                ->get()
                ->keyBy('id');
            //$time=$user_answers->toArray();
            foreach ($user_answers as $answer1){
                $time = $answer1->created_at;
                $answer1->time=$time;
            }
            if (!$user_answers) {
                return ['status'=>0,'msg'=>'null answers'];
            }else{
                return ['status'=>1,'data'=>$user_answers->toArray()];
            }
        }

        /*
         * 查找回答，，以及他的回答者的信息和点赞的用户
         * */
        if(rq('id')) {
            $answer=$this
                ->with('user')
                ->with('question')
                ->with('users')
                ->find(rq('id'));
            if(!$answer)
                return ['status'=>0,'msg'=>'data not fond'];
            //这是问题详情页，每次读取的时候刷新点赞数
            $upvote_count=0;
            $downvote_count=0;
            foreach($answer->users as $user){
                if($user->pivot->vote==1)
                    $upvote_count++;
                else if($user->pivot->vote==2)
                    $downvote_count++;
                $answer->upvote_count=$upvote_count;
                $answer->downvote_count=$downvote_count;
            }
            //当没有点赞数据的时候也记录为0
            if(sizeof($answer->users)==0){//或者用count
                $answer->upvote_count=0;
                $answer->downvote_count=0;
            }
            $time=$answer->created_at;
            $answer->time=$time;
            return ['status'=>1,'data'=>$answer->toArray()];
        }
        if(!question_ins()->find(rq('question_id')))
            return ['status'=>0,'msg'=>'question not found'];;
        $question=$this
            ->where('question_id',rq('question_id'))
            ->get()
            ->keyBy('id');
        if(!$question)
            return ['status'=>0,'msg'=>'question not found'];

        return ['status'=>1,'data'=>$question];

    }

    /*
     * 点赞
     * */

    public function vote(){
        if(!user_ins()->login_or_out())
            return ['status'=>0,'msg'=>'you login require','login'=>2];
        if(!rq('id')&&!rq('vote'))
            return ['status'=>0,'msg'=>'id and vote reqiure'];
        $answer=$this->find(rq('id'));
        if(!$answer)
            return ['status'=>0,'msg'=>'answer not find'];
        /*
         * 查看是否已经vote过
         * */
        $vote=rq('vote');
        if($vote!=1&&$vote!=2&&$vote!=3)
            return ['status'=>0,'msg'=>'invalid vote'];
        $answer->users()
            ->newPivotStatement()//就是引用了vote库
            ->whereRaw('user_id=? and answer_id=?',[session('user_id'),rq('id')])
            ->delete();
        if($vote==3)
            return ['status'=>1];
        $answer->users()
            ->attach(session('user_id'),['vote'=>$vote]);//就是用user_id把这张表连起来,第二各参数就存入数据
        return ['status'=>1];

    }
    public function user(){
        return $this->
            belongsTo('App\User');
    }
    public function users(){
        return $this
            ->belongsToMany('App\User')
            ->withPivot('vote')
            ->withTimestamps();
    }
    public function question(){//记住这里如果是一对多就用复苏，一对一就用单数
        return $this->belongsTo('App\Question');
    }
}