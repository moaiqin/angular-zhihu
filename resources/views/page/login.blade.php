<div class="container login" id="login" ng-controller="loginController" >
    <h4>登录</h4>
    <form ng-submit="userLogin.login()" name="loginForm">
        <div class="form-group" ng-class="{'has-success':loginForm.username.$valid}">
            <label class="control-label">用户名</label>
            <input type="text" name="username"
                   class="form-control"
                   ng-model="userLogin.login_data.username"
                   required
                   placehodler="用户名/手机/邮箱"/>
            <p class="error" ng-if="loginForm.username.$touched&&
            loginForm.username.$error.required">用户名不能为空</p>
        </div>
        <div class="form-group" ng-class="{'has-success':loginForm.password.$valid}">
            <label class="control-label">密　码</label>
            <input type="password"
                   class="form-control"
                   ng-model="userLogin.login_data.password"
                   required
                   name="password"/>
            <p class="error" ng-if="loginForm.password.$touched&&
            loginForm.password.$error.required">密码不能为空</p>
        </div>
        <div class="form-group">
            <input type="submit"
                   class="btn btn-default"
                   ng-disabled="!loginForm.$valid"
                   value="登录"/>
        </div>
    </form>
</div>