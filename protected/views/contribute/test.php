<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $userData,
	'pager' => array(
		'prevPageLabel' => '上一页',
		'firstPageLabel' => '首页',
		'nextPageLabel' => '下一页',
		'lastPageLabel' => '末页',
		'header' => '',
	),
	'ajaxUpdate' => true, 
	'columns' => array(
		'ID',
		'title',
		'created',
		'changed',
		'author',
		//array('class' => 'CButtonColumn', 'header' => '操作')
		array(
		    'class'=>'CButtonColumn',
		    'template'=>'{detail}{edit}{delete}',
		    'buttons'=>array
		    (
		        'detail' => array
		        (
		            //'label'=>'Send an e-mail to this user',
		            'label'=>'[+]',
		            //'imageUrl'=>Yii::app()->request->baseUrl.'/images/email.png',
		            'url'=>'Yii::app()->createUrl("contribute/view", array("id"=>$data->ID))',
		        ),
		        'edit' => array
		        (
		            'label'=>'[-]',
		            'url'=>'Yii::app()->createUrl("contribute/edit", array("id"=>$data->ID))',
		            //'visible'=>'$data->score > 0',
		            //'click'=>'function(){alert("Going down!");}',
		        ),
		    ),
		),
	),
));?>