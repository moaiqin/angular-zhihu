define(['main'],function (model) {
    model.service('answerService',[
        '$http',
        '$state',
        function ($http,$state) {
        var me=this;
        me.data=[];//对数据重新读取，跟从新替换之前旧数据的选项
        me.up_down_data={};//获取回答的数据，便判断取消赞
        me.answer_form={};
        window.user={
            'id':parseInt($('body').attr('user_id'))
        };
        /*总和点赞和踩的数据
        * */
        me.getVote=function (answers) {
            for (var i=0; i<answers.length; i++){
                var votes, answer=answers[i];
                if(!answers[i].question_id)
                    continue;
                me.up_down_data[answer.id]=answer;
                answer.upvote_count=0;
                answer.downvote_count=0;
                votes=answer['users'];
                for (var j=0; j<votes.length; j++){
                    if(votes[j].pivot.vote==1){
                        answer.upvote_count++;
                    }else if(votes[j].pivot.vote==2){
                        answer.downvote_count++;
                    }
                }
            }
            return answers;
        };
        /*
        * 点赞功能，看点赞是否成功
        * conf={id:}id表示对那条回答进行点赞
        * */
        me.vote=function (conf) {
            if(!conf.id||!conf.vote)
                return;
            /*
            * 判断vote和数据库的vote是否一样，一样的花就取消d点赞
            * */
            var answers=me.up_down_data[conf.id],
            users=answers.users;
            for(var i=0; i<users.length; i++){
                if(user.id == users[i].id&&conf.vote == users[i].pivot.vote)
                    conf.vote=3;
            }
            return $http.post('api/answer/vote',conf).then(function (success) {
                if(success.data.status&&success.status)
                    return true;
                else if(success.data.login==2)
                    $state.go('login');
                else
                    return false;

            },function (error) {
                console.log('error');
                return false;
            });
        };

        /*
        *查看用户回答过的问题
        * param {user_id:}
        * */

        me.read=function (param) {
             return $http({
                method:'post',
                url:'api/answer/read',
                data:$.param(param),
                headers:{'content-type':'application/x-www-form-urlencoded'}
            }).then(function (success) {
                if (success.status&&success.data.status)
                    return success.data.data;
                return false;
            },function (error) {
                console.log('service error')
            })
        }

        /*
        * 点赞成功后对数据从新读取，为以后的点赞完直接改变前端数据
        * */
        me.answer_update=function(id){
            $http.post('api/answer/read',{id:id}).then(function (success) {
                me.data[id]=success.data.data;
            },function (err) {
                console.log(err);
            });
        }

        //问题回答的提交和更新
        me.answer_add_update=function (question_id) {
            me.answer_form.question_id=question_id;
            if(me.answer_form.id){
                $http.post('api/answer/update',me.answer_form).then(function (success) {
                    if (success.status&&
                        success.data.status){
                        me.answer_form={};
                        $state.reload();
                    }
                },function () {
                    console.error('service error')
                })
            }else {
                $http.post('api/answer/add',me.answer_form).then(function (success) {
                    console.log(success)
                    if (success.status&&
                        success.data.status===0 &&
                        success.data.msg=='you have answerd'){
                        alert('你已经回答过了');
                    }else if(success.status&&success.data.status){
                        me.answer_form={};
                        $state.reload();
                    }
                },function () {
                    console.error('service error')
                })
            }
        }
        me.delete=function (conf) {
            $http.post('api/answer/remove',conf).then(function (success) {
                if(success.data.status){
                    $state.reload();
                }
            },function () {
                console.log('service error')
            })
        }
        
        me.comment_text={};
        me.comment_submit=function () {
            return $http.post('api/comment/add',me.comment_text).then(function (success) {
                if(!success.data.status&&success.data.msg=='login require')
                    $state.go('login');
                if(success.data.status)
                    return true;
                return false;
            },function () {
                console.log('service error')
            })
        }
    }])
    
})
