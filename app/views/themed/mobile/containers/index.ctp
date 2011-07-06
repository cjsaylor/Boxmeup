<ul data-role="listview" data-theme="c">
	<div data-role="controlgroup" data-type="horizontal" style="width: 100%; text-align: center">
	<?php
		echo $html->link('Add Container', array('action' => 'add'), array('data-icon' => 'plus', 'data-rel' => 'dialog', 'data-role' => 'button', 'data-transition' => 'slidedown'));
		echo $html->link('Search', array('controller' => 'searches', 'action' => 'find'), array('data-icon' => 'search', 'data-role' => 'button'));
	?>
	</div>
	<?php
	foreach($containers as $container) {
		echo $html->tag('li',
			$html->link($container['Container']['name'],
				array('controller' => 'containers', 'action' => 'view', $container['Container']['slug'])) .
			$html->tag('span', $container['Container']['container_item_count'], array('class' => 'ui-li-count ui-btn-up-c ui-btn-corner-all'))
		);
	}
?>
</ul>