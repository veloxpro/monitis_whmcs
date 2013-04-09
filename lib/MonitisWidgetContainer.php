<?php
class MonitisWidgetContainer {
	var $columns = array(array(), array()); // has two columns
	
	function addWidget($widget) {
		if (count($this->columns[0]) > count($this->columns[1]))
			$this->columns[1][] = $widget;
		else
			$this->columns[0][] = $widget;
	}
	
	function render() {
		foreach ($this->columns as $key => $column) {
			echo '<div class="monitis homecolumn" id="homecol' . ($key + 1) . '">';
			foreach ($column as $widget) {
				echo '<div class="monitis homewidget" id="' . $widget->id . '">';
				echo '<div class="monitis widget-header">' . $widget->title . '</div>';
				echo '<div class="monitis widget-content">' . $widget->content . '</div>';
				echo '</div>';
			}
			echo '</div>';
		}
		echo '<div style="clear:both;"></div>';
	}
}

class MonitisWidget {
	var $title = '';
	var $content = '';
	var $id = '';
	
	function __construct($title, $content, $id = '') {
		$this->title = $title;
		$this->content = $content;
		$this->id = $id;
	}
}