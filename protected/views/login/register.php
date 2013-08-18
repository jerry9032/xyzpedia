<div class="container">
<div class="registerwrap">

		<form class="form-horizontal registerform" method="post" action="/login/regsuccess">
		<fieldset>
			<legend><h4>在“小宇宙百科”注册</h4></legend>
			<div class="control-group">
				<label class="control-label" for="login">用户名</label>
				<div class="controls">
					<span rel="popover" data-original-title="用户名" data-content="您独一无二的登录用户名，支持中文注册，不过还是推荐英文喔">
					<input type="text" class="input-medium" id="login" name="login" check-type="nospace" nospace-message="只能包含数字、字母和汉字喔~">
					</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="nick_name">投稿署名</label>
				<div class="controls">
					<span rel="popover" data-original-title="小百科投稿署名" data-content="如果您之前投过稿，请填写您之前使用过的投稿署名，系统能自动将稿件关联到您的账号~<br>如果没投过稿，请填写将来要投稿的署名，建议和真实姓名相同，注册后不能再改了喔~">
					<input type="text" class="input-medium" id="nick_name" name="nick_name" check-type="nospace" nospace-message="只能包含数字、字母和汉字喔~">
					</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="mail">邮箱</label>
				<div class="controls">
					<span rel="popover" data-original-title="邮箱" data-content="请填写您的常用邮箱，用于账号激活、取回密码等，很重要喔~">
					<input type="text" class="input" id="mail" name="mail" check-type="mail" mail-message="邮箱格式不正确喔~">
					</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="pass1">密码</label>
				<div class="controls">
					<span rel="popover" data-original-title="密码" data-content="6位以上喔~">
					<input type="password" class="input-medium" name="pass1" id="pass1" check-type="required" required-message="一定要设置一个密码喔~">
					</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="pass2">重复密码</label>
				<div class="controls">
					<span rel="popover" data-original-title="再次输入密码" data-content="以防第一次输错，两次要一致才能通过呢~">
					<input type="password" class="input-medium" id="pass2" name="pass2" check-type="required" required-message="一定要设置一个密码喔~">
					</span>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="mobile">手机号</label>
				<div class="controls">
					<span rel="popover" data-original-title="您的手机号" data-content="我们不会将您的手机号公开或用作其他用途，只用来统计和构建转发关系~请放心填写。">
					<input type="text" class="input-medium" id="mobile" name="mobile" check-type="mobile">
					</span>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="upline">上线的手机号</label>
				<div class="controls">
					<span rel="popover" data-original-title="您的上线" data-content="是谁将小宇宙百科介绍给你的呢？是谁给你发飞信版的小百科呢？填写Ta的号码吧~如果没有，请填18267197908。">
					<input type="text" class="input-medium" id="upline" name="upline" check-type="mobile">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="job">您的工作</label>
				<div class="controls">
					<select name="job" id="job" style="width: 180px;">
						<option value=""> --请选择-- </option>
						<option value="student">学生</option>
						<option value="work">工作</option>
						<option value="freejob">自由职业者</option>
						<option value="retired">退休</option>
					</select>
				</div>
			</div>
			<div class="control-group" style="display:none;" id="school-wrap">
				<label class="control-label" for="school">学校</label>
				<div class="controls">
					<input type="text" class="input-medium" id="school" name="school">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="ht-province">家乡</label>
				<div class="controls">
					<select name="ht-province"></select>
					<select name="ht-city"></select>
					<select name="ht-area"></select>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="rd-province">常驻地区</label>
				<div class="controls">
					<select name="rd-province"></select>
					<select name="rd-city"></select>
					<select name="rd-area"></select>
				</div>
			</div>

			<div class="form-actions">
				<button type="submit" class="btn btn-success"><i class="icon-file icon-white"></i> 注册</button>
				<a href="/login" class="btn btn-primary"><i class="icon-user icon-white"></i> 返回登录</a>
			</div>
		</fieldset>
		</form>



</div>
</div>
<script type="text/javascript" src="/js/pcas.js"></script>
<script type="text/javascript" src="/js/bootstrap-popover.js"></script>
<script type="text/javascript" src="/js/bootstrap-validation.js"></script>
<script type="text/javascript" src="/js/register.js"></script>