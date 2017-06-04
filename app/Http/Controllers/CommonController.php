<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class CommonController extends Controller
{
    public  function timeline(){
        list($limit,$skip)=paginate(rq('page',rq('limit')));
        /*
         * 获取问题数据
         * */
        $question=question_ins()
            ->with('user')
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at','desc')
            ->get();

        /*
         * 获取回答数据
         * */
        $answer=answer_ins()
            ->with('user')
            ->with('question')
            ->with('users')
            ->limit($limit)
            ->skip($skip)
            ->orderBy('created_at','desc')
            ->get();
        //dd($question->toArray());
        //dd($answer->toArray());
        /*
         * 数据合并
         * */

        $data=$question->merge($answer);
        /*
         * 数据【排序
         * */
        $data=$data->sortByDesc(function($item){
            return $item->created_at;
        });

        /*
         * 讲数据全部都索引去掉
         * */
        $data=$data->values()->all();

        return ['status'=>1,'data'=>$data];
    }
}
