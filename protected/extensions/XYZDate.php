<?

class XYZDate {
	
	private $now = 0;
	private $create = 0;
	private $appoint = 0;

	private $one_day = 86400;
	private $one_month = 2592000;
	private $two_month = 5184000;

	private $last_month = 0;
	private $next_monday = 0;
	private $nnext_monday = 0;

	function __construct($now, $create, $appoint) {
		$this->now = $now;
		$this->create = $create;
		$this->appoint = $appoint;

		$this->last_month = strtotime("-4 week Monday");
		$this->next_monday = strtotime("+0 week Monday");
		$this->nnext_monday = strtotime("+1 week Monday");
	}


	function gt( $time ) {
		return ($this->now - $this->create >= $time);
	}
	function gt_one_month() {
		return $this->gt( $this->one_month );
	}
	function gt_two_month() {
		return $this->gt( $this->two_month );
	}


	function ap_btw($min, $max) {
		return ($this->appoint >= $min) && ($this->appoint < $max);
	}
	function appoint_this_week() {
		return $this->ap_btw( $this->next_monday, $this->nnext_monday );
	}
	function appoint_last_week() {
		return $this->ap_btw( $this->last_month, $this->next_monday );
	}
	function appoint_later() {
		/* 3999-12-31 00:00:00 */
		return $this->ap_btw( $this->nnext_monday, 64060473600 );
	}

}



?>