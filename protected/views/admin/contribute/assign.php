<div class="row">
	<div class="span2">
		<form method="post" action="/admin/contribute/doassign/<?=$id?>">
		<h5>
			按用户标签匹配
			<span rel="popover" data-original-title="数字代表什么？" data-content="是匹配程度，也就是此用户将此标签贴身上的顺序，越小说明匹配程度越高。"><i class="icon-question-sign icon-blue"></i></span>
		</h5>
		<p style="font-size:12px;color:#666">当前标签：<?=$currentTag?></p>
		<ul class="user-list">
			<? foreach($usersByTag as $tag) { ?>
			<li><label class="radio">
				<input type="radio" class="radio" name="by-tag" value="<?=$tag->user->id?>" />
				<?=$tag->user->nick_name?> (<?=$tag->tag_order?>)</label>
			</li>
			<? } ?>
		</ul>
		<input type="submit" class="btn btn-primary" value="提交">
		</form>
	</div><!-- end of span4 -->


	<div class="span3">
		<form method="post" action="/admin/contribute/doassign/<?=$id?>">
		<h5>按最后登录排序</h5>
		<ul class="user-list">
			<? foreach($usersByLogin as $user) { ?>
			<li><label class="radio">
				<input type="radio" class="radio" name="by-last-login" value="<?=$user->id?>" />
				<?=$user->user->nick_name?> (<?=timeFormat($user->last_login, 'apart')?>)</label>
			</li>
			<? } ?>
		</ul>
		<input type="submit" class="btn btn-primary" value="提交">
		</form>
	</div><!-- end of span4 -->

	<div class="span2">
		<form method="post" action="/admin/contribute/doassign/<?=$id?>">
		<h5>按活跃度排序</h5>
		<ul class="user-list">
			<? foreach($usersByScore as $user) { ?>
			<li><label class="radio">
				<input type="radio" class="radio" name="by-score" value="<?=$user->id?>" />
				<?=$user->user->nick_name?> (<?=$user->score?>)</label>
			</li>
			<? } ?>
		</ul>
		<input type="submit" class="btn btn-primary" value="提交">
		</form>
	</div><!-- end of span4 -->


	<div class="span2">
		<form method="post" action="/admin/contribute/doassign/<?=$id?>">
		<h5>按投稿署名搜索</h5>
		<input id="by-nick-name" name="by-nick-name"
			type="text" class="input span2" placeholder="投稿署名" autocomplete="off"
			data-provide="typeahead" data-items="10"
			value='' data-source='<?=$usersByName?>'>
		<input type="submit" class="btn btn-primary" value="提交">
		</form>
	</div>
</div>
