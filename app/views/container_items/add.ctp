<h2>Add Container Item</h2>
<?php
echo $form->create('ContainerItem', array('url' => array('controller' => 'container_items', 'action' => 'add', $container['Container']['uuid'])));
echo $form->input('body', array('type' => 'text', 'label' => false, 'style' => 'float: left'));
echo $form->submit('Add Item', array('div' => false, 'class' => 'small green button', 'style' => 'float: left'));
echo $form->end();
echo $html->tag('div', '', array('style' => 'clear: both'));