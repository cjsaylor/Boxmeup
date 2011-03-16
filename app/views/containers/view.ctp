<h2><?php echo $container['Container']['name']; ?></h2>
<br/>
<?php
	echo $form->create('ContainerItem', array('url' => array('controller' => 'container_items', 'action' => 'add', $container['Container']['uuid'])));
	echo $form->input('body', array('type' => 'text', 'label' => false, 'style' => 'float: left'));
	echo $form->submit('Add Item', array('div' => false, 'class' => 'medium green button', 'style' => 'float: left'));
	echo $form->end();
	echo $html->tag('div', '', array('style' => 'clear: both'));
	if(empty($container['ContainerItem']))
		echo $html->tag('p', __('No items yet.'));
	else {
		foreach($container['ContainerItem'] as $key => $item) {
?>
			<div class="container-item-list <?php echo $key % 2 != 0 ? 'alternate' : '' ?>" >
				<?php echo Sanitize::html($item['body'], array('remove' => true)); ?>
			</div>
<?php
		}
	}