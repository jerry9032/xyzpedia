<?
$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<h2>Internal Error</h2>

<div class="error">
<?php echo CHtml::encode($message); ?>
</div>