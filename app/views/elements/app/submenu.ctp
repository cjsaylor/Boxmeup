<div id="submenu-content">
<?php
	$element = 'app/submenus/'.$this->name.'.'.$this->action;
	if ($this->theme)
		$element_path = APP . 'views' . DS . 'themed' . DS . $this->theme . 'elements' . DS . $element . DS . $this->ext;
	else
		$element_path = APP . 'views' . DS . 'elements' . DS . $element . $this->ext;
	if (file_exists($element_path))
		echo $this->element($element);
	else {
?>
		<div style="padding-top: 5px;">&nbsp;</div>
<?php
	}
?>
</div>