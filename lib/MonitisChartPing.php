<?php

class MonitisChartPing extends MonitisChart {
	static $externalLocations = array();
	
	function __construct($monitorID) {
		parent::__construct($monitorID);
		$this->hchart->chart->type = 'spline';
		$this->hchart->plotOptions->spline->marker->enabled = false;
		
		$this->hchart->tooltip->crosshairs = 1;
		
		$globalOptions = new HighchartOption();
		$globalOptions->global->useUTC = false;
		$this->hchart->setOptions($globalOptions);
		
		$this->hchart->legend = array('borderWidth' => 0);
		$this->hchart->plotOptions->spline->lineWidth = 1.5;
		$this->hchart->plotOptions->spline->states->hover->lineWidth = 2;
		
		//$this->hchart->plotOptions->spline->marker->states->hover->lineWidth = 1;
		
		$this->hchart->xAxis->type = "datetime";
		$this->hchart->xAxis->dateTimeLabelFormats->month = "%e. %b";
		$this->hchart->xAxis->dateTimeLabelFormats->year = "%b";
		
		$this->hchart->yAxis->title->text = "";
		$this->hchart->yAxis->min = 0;
		$this->hchart->yAxis->minorTickInterval = 'auto';
		$this->hchart->yAxis->gridLineWidth = 2;
		$this->hchart->xAxis->gridLineWidth = 1;
		$this->hchart->xAxis->gridLineColor = '#EEE';
		$this->hchart->yAxis->gridLineColor = '#EEE';
		$this->hchart->yAxis->minorGridLineColor = '#f7f7f7';
		
		
		$this->hchart->tooltip->formatter = new HighchartJsExpr("function() {
                                    return '<b>'+ this.series.name +'</b><br/>'+
                                    Highcharts.dateFormat('%e. %b %H:%M', this.x) +' - '+ parseInt(this.y) +'(ms)';}");
	}
	
	function render() {
		$this->loadData();
		parent::render();
	}
	
	function loadData() {
		$startDate = new DateTime();
		//$startDate->modify('-1 day');
		$data = MonitisApi::getExternalResults($this->monitorID, $startDate->format('d'), $startDate->format('m'), $startDate->format('Y'), NULL);
		//_dump($data[0]['data']);
		$data = $this->groupData($data);
		//_dump($data);
		
		foreach ($data as $location) {
			$d = array();
			
			//$pointGroup = array_slice($array, $offset, 100);
			foreach ($location['data'] as $point) {
				
				//$d[] = array(strtotime($point[0])*1000, $point[1]);
				$pointDate = preg_split("/[: -]+/", $point[0]);
				$d[] = array(new HighchartJsExpr("
						Date.UTC($pointDate[0],  $pointDate[1], $pointDate[2], $pointDate[3], $pointDate[4]) - monitisTZOffset
						"), $point[1]);
			}
			
			$allLocations = $this->getExternalLocations();
			
			$this->addSeries($allLocations[$location['id']]['fullName'], $d);
		}
	}
	
	function groupData($data) {
		$maxPointCount = 300;
		
		$chunkSize = floor(count($data[0]['data'])/$maxPointCount);
		if ($chunkSize < 2)
			return $data;
		$chunkSize = $chunkSize * 2;
		foreach ($data as $location_key => $location) {
			$grouped_data = array();
			$chunks = array_chunk($location['data'], $chunkSize);
			foreach ($chunks as $chunk) {
				$min = array($chunk[0][0], $chunk[0][1]);
				$max = array($chunk[0][0], $chunk[0][1]);
				foreach ($chunk as $point) {
					if ($point[1] < $min[1]) {
						$max[1] = $point[1];
						$max[0] = $point[0];
					}
				}
				if (strtotime($max[0]) == strtotime($min[0])) {
					$grouped_data[] = $min;
				} elseif (strtotime($max[0]) > strtotime($min[0])) {
					$grouped_data[] = $min;
					$grouped_data[] = $max;
				} else {
					$grouped_data[] = $max;
					$grouped_data[] = $min;
				}
			}
			//_dump($chunks);
			$data[$location_key]['data'] = $grouped_data; 
		}
		return $data;
	}
	
	function getExternalLocations() {
		if (empty(self::$externalLocations)) {
			$rawLocations = MonitisApi::getExternalLocations();
			foreach ($rawLocations as $loc) {
				self::$externalLocations[$loc['id']] = $loc;
			}
		}
		return self::$externalLocations;
	}
}