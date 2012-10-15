<?php
	echo $this->Html->scriptBlock("
		var container_view = '$container_view'
	");
	echo $this->Html->script('views/containers/index', array('inline' => false));
?>
<div style="float: right">
	<?php
		echo $this->Form->select('Location.uuid', $location_list, array('empty' => __('All Locations')));
	?>
</div>
<div style="clear: both">&nbsp;</div>
<?php
	if(empty($containers)) {
		echo '<p>' . __('No containers for this location.') . '</p>';
	}
	foreach($containers as $i => $container) {
		$link_contents = $this->Html->image('generic-box.png');
		$link_contents.= $this->Html->tag('br');
		$link_contents.= $this->Html->tag('div', $container['Container']['name'], array('class' => 'container-name'));
		$link_contents.= $this->Html->tag('br');
		$link_contents.= __('Items: ') . $container['Container']['container_item_count'];
		echo $this->Html->link(
			$link_contents,
			array('controller' => 'containers', 'action' => 'view', $container['Container']['slug']),
			array('class' => 'container-box', 'escape' => false)
		);
	}