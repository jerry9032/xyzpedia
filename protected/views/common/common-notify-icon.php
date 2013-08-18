<?
$uid = user()->id;
$sum = 0;
$type = "";
// 被艾特的数目
$c1 = sql("select count(*) from xyz_notify where target_id=".$uid)
		->queryScalar();
$sum += $c1;
if ($c1>0) $type .= "at ";

// 待修改
$c5 = sql("select count(*) from xyz_contrib where status='modify' and author_id=".$uid)->queryScalar();
$sum += $c5;
if ($c5>0) $type .="modify ";

// 待初审
$c2 = sql("select count(*) from xyz_contrib where status='pending' and editor_id=".$uid)->queryScalar();
$sum += $c2;
if ($c2>0) $type .= "edit ";

// 待分配
if (can("contribute", "assign")) {
	$c3 = sql("select count(*) from xyz_contrib where status='neglected'")
			->queryScalar();
	$sum += $c3;
	if($c3>0) $type .= "assign ";
}

// 待终审
if (can("contribute", "final")) {
	$c4 = sql("select count(*) from xyz_contrib where status='pass'")
			->queryScalar();
	$sum += $c4;
	if($c4>0) $type .= "final ";
}

// 9以下显示图标
if ($sum > 0 && $sum <= 9) { ?>
<img id="navbar-notify-icon" src="/images/unread/<?=$sum?>.png" /><?

// 9以上显示数字
} elseif ($sum > 9) { ?>
<span class="label label-important"><?=$sum?></span><?

// 0 就不显示了
} ?>