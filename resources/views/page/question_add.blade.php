<div class="container"  id="questionadd">
    <form  name="questionForm" ng-controller="questionAddController" ng-submit="question.question_submit()">
        <div class="form-group">
            <h4 class="control-label">问题标题</h4>
            <input placehodler="2-160个字符"
                   name="title"
                   ng-model="question.question_data.title"
                   ng-minlength="2"
                   maxlength="160"
                   required
                   class="form-control"/>
            <p class="error" ng-if="questionForm.title.$touched&&
            questionForm.title.$error.required">标题不能为空</p>
            <p class="error" ng-if="questionForm.title.$touched&&
            (questionForm.title.$error.minlength||
            questionForm.title.$error.maxlength)">标标题应在2-160之间</p>
        </div>
        <div class="form-group">
            <h4 class="control-label">问题描述</h4>
            <textarea class="form-control" name="desc"
                      ng-model="question.question_data.desc"
                      placehodler="输入问题的描述">
            </textarea>
        </div>
        <div class="form-group">
            <button class="btn btn-primary"
                    type="submit"
                    ng-disabled="!questionForm.$valid">提问</button>
        </div>
    </form>
</div>