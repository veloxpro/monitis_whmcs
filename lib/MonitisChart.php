<?php
class MonitisChart {
	static $chartCount = 0;
	
	var $monitorID;
	var $hchart;
	var $UID;
	var $seriesCount = 0;
	
	function __construct($monitorID) {
		$this->monitorID = $monitorID;
		$this->UID = ++self::$chartCount;
		$this->hchart = new Highchart();
		
		$this->hchart->credits->enabled = false;
	}
	
	function setHeight($height) {
		$this->hchart->chart->height = $height;
	}
	
	function setWidth($width) {
		$this->hchart->chart->width = $width;
	}
	
	function addSeries($name, $data) {
		$this->hchart->series[$this->seriesCount]['name'] = $name;
		$this->hchart->series[$this->seriesCount]['data'] = $data;
		$this->hchart->series[$this->seriesCount]['marker'] = array('symbol' => 'circle', 'radius' => 1);
		$this->seriesCount++;
	}
	
	function renderHtml() {
		echo "<div class='monitisNoData' id='monitisNoDataChart" . $this->UID . "'>No data received yet.</div>";
		echo "<div class='monitisLoader' id='monitisLoaderChart" . $this->UID . "'></div><div id='hchartsContainer" . $this->UID . "'></div>";
	}
	
	function renderJS() {
		$this->hchart->chart->renderTo = "hchartsContainer" . $this->UID;
		$this->hchart->title->text = "";
		$this->hchart->subtitle->text = "";
		?>
		<script type="text/javascript">
			(function($){
				var chart;
				$(document).ready(function() {
					var options = <?php echo $this->hchart->renderOptions(); ?>;
					chart = new Highcharts.Chart(options);
					$.ajax({
						url: '<?php echo MONITIS_APP_URL ?>&monitis_page=monitorJson&monitor_id=<?php echo $this->monitorID ?>',
						datatype: "html",
						success: function(data) {
							data = $.parseJSON($(data).find('monitisData').html());
							

							//timezone offset and fail point colors
							$.each(data, function(location, d) {
								$.each(d, function(key, point) {
									d[key] = {
											x:point[0] - monitisTZOffset,
											y:point[1] };
									if (d[key].y == 0) {
										d[key].marker = {
												enabled: true,
												fillColor: 'red' };
									}
									
								});
							});
							
							$.each(data, function(location, d) {
								chart.addSeries({                        
									name: location,
									data: d
								});
							});
							$("#monitisLoaderChart<?php echo $this->UID ?>").hide();
							if (chart.series.length < 1) {
								$("#monitisNoDataChart<?php echo $this->UID ?>").css('display', 'block');
							}
						},
					});
					
				});
			})(jQuery);
		</script>
		<?php
	}
}