<div class="home container" id="signup">
    <div>
        <h3>注册</h3>
        <form name="signupForm" class="form-horizontal has-feedback" ng-submit="userSignup.signup()" ng-controller="signupController">
            <div class="form-group" ng-class="{'has-success':signupForm.username.$valid&&!userSignup.usernameexists}">
                <label class="col-sm-3 control-label">
                    用户名：
                </label>
                <div class="col-sm-9">
                    <input name="username"
                           placeholder="手机/邮箱/用户名"
                           ng-minlength="2"
                           ng-maxlength="26"
                           required="required"
                           ng-model="userSignup.signup_data.username"
                           ng-model-options="{updateOn:'default blur',debounce:{default:400,blur:0}}"
                           class="form-control"
                    />
                    <p class="error" ng-if="signupForm.username.$touched&&
                    (signupForm.username.$error.minlength||
                    signupForm.username.$error.maxlength)">用户名应在3-30之间</p>
                    <p class="error" ng-if="signupForm.username.$touched&&
                    signupForm.username.$error.required">用户名不能为空</p>
                    <p class="error" ng-if="signupForm.username.$valid&&
                    userSignup.usernameexists">用户名已存在</p>
                </div>
            </div>
            <div class="form-group" ng-class="{'has-success':signupForm.email.$valid&&!userSignup.emailexists}">
                <label class="col-sm-3 control-label">
                    邮箱：
                </label>
                <div class="col-sm-9">
                    <input type="email"
                           name="email"
                           placeholder="输入邮箱"
                           required="required"
                           ng-model-options="{debounce:300}"
                           ng-model="userSignup.signup_data.email"
                           class="form-control"/>
                    <p class="error" ng-if="signupForm.email.$touched&&
                    signupForm.email.$error.email">邮箱格式错误</p>
                    <p class="error" ng-if="signupForm.email.$touched&&
                    signupForm.email.$error.required">邮箱不能为空</p>
                    <p class="error" ng-if="signupForm.email.$valid&&userSignup.emailexists">邮箱码已存在</p>
                </div>
            </div>
            <div class="form-group" ng-class="{'has-success':signupForm.phone.$valid&&!userSignup.phoneexists}">
                <label class="col-sm-3 control-label">
                    电话：
                </label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <span class="input-group-addon">186+</span>
                        <input name="phone"
                               placeholder="输入电话"
                               ng-model="userSignup.signup_data.phone"
                               ng-model-options="{debounce:300}"
                               ng-pattern="/^[1]{1}[3,5,7,8]{1}[0-9]{9}$/"
                               required="required"
                               class="form-control"/>
                    </div>
                    <p class="error" ng-if="signupForm.phone.$touched&&
                    signupForm.phone.$error.required">电话不能为空</p>
                    <p class="error" ng-if="signupForm.phone.$touched&&
                    signupForm.phone.$error.pattern">电话格式不对</p>
                    <p class="error" ng-if="signupForm.phone.$valid&&userSignup.phoneexists">电话号码已存在</p>
                </div>
            </div>
            <div class="form-group" ng-class="{'has-success':signupForm.password.$valid}">
                <label class="col-sm-3 control-label">
                    密码：
                </label>
                <div class="col-sm-9">
                    <input name="password"
                           type="password"
                           ng-minlength="6"
                           ng-maxlength="60"
                           required="required"
                           ng-model="userSignup.signup_data.password"
                           class="form-control"/>
                    <p class="error" ng-if="signupForm.password.$touched&&
                    (signupForm.password.$error.minlength||
                    signupForm.password.$error.maxlength)">密码应在3-30之间</p>
                    <p class="error" ng-if="signupForm.password.$touched&&
                    signupForm.password.$error.required">用户名不能为空</p>
                </div>

            </div>
            <div class="form-group" ng-class="{'has-success':signupForm.password2.$valid}">
                <label class="col-sm-3 control-label">
                    确认密码：
                </label>
                <div class="col-sm-9">
                    <input name="password2"
                           type="password"
                           required="required"
                           ng-model="userSignup.signup_data.password2"
                           compare="userSignup.signup_data.password"
                           class="form-control"/>
                    <p class="error" ng-if="signupForm.password2.$touched&&
                    signupForm.password2.$error.compare">两次密码不一致</p>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary center-block" ng-disabled="!signupForm.$valid||
    userSignup.usernameexists||userSignup.phoneexists||userSignup.emailexists">注册</button>
            </div>
        </form>
    </div>
</div>