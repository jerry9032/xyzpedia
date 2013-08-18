<?

/**
* 
*/
class TestController extends CController
{
	
	function init() {
		
	}

	public function actionXYZDate() {

		Yii::import('ext.XYZDate');
		
		/*
		test create
			2013-01-31 00:00:00  =  1359561600
			2013-02-01 00:00:00  =  1359648000
			2013-03-01 00:00:00  =  1362067200
			2013-03-02 00:00:00  =  1362153600
			2013-03-28 00:00:00  =  1364400000
			2013-04-01 00:00:00  =  1364745600

		test appoint
			2013-04-06 00:00:00  =  1365177600

			XYZDate($now, $create, $appoint);
		*/
		$date = new XYZDate(1364745600, 1359561600, 1364400000);

		dump( $date->gt_one_month() );
		dump( $date->gt_two_month() );
		dump( $date->appoint_this_week() );
		dump( $date->appoint_last_week() );

	}


	public function actionLock() {
		sql('lock table {{user}} write')->query();
		dump(sql('select * from {{user}} where id>260')->queryAll());
		sleep(10);
		sql('unlock table');
	}

	public function actionShow() {
		dump(sql('select * from {{user}} where id>260')->queryAll());
	}

	public function actionUS() {
		$us = usetting();
		dump($us);
	}
}

?>