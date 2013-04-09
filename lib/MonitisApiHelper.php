<?php
class MonitisApiHelper {
	static function getExternalLocationsGroupedByCountry() {
		$locations = MonitisApi::getExternalLocations();
		$americasIDs = array(1, 3, 5, 9, 10, 14, 15, 17, 26, 27);
		$europeIDs = array(2, 4, 7, 11, 12, 18, 19, 22, 23, 24, 25, 28, 29);
		$asiaIDs = array(8, 13, 16, 21);
		
		$loc = array('Americas' => array(), 'Europe' => array(), 'Asia' => array(), 'Other' => array());
		foreach ($locations as $l) {
			if (in_array($l['id'], $americasIDs))
				$loc['Americas'][$l['id']] = $l;
			elseif (in_array($l['id'], $europeIDs))
			$loc['Europe'][$l['id']] = $l;
			elseif (in_array($l['id'], $asiaIDs))
			$loc['Asia'][$l['id']] = $l;
			else
				$loc['Other'][$l['id']] = $l;
		}
		return $loc;
	}
}