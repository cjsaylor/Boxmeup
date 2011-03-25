<?php
App::import('lib', array('Sanitize'));
class ContainersController extends AppController {

	public $name = 'Containers';

	public $layout = 'app';

	public $_secure = true;

	public $_container_page_limit = 20;

	public $uses = array('Container', 'ContainerItem');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->set('active', 'containers.index');
	}

	public function dashboard() {
		$this->helpers[] = 'GChart';
		$total_containers = $this->Container->getTotalContainersPerUser($this->Auth->user('id'));
		$total_container_items = $this->Container->getTotalContainerItemsPerUser($this->Auth->user('id'));

		$container_stats = Set::combine($this->Container->find('all', array(
			'fields' => array('COUNT(id) AS containers', 'DATE(modified) AS timestamp'),
			'conditions' => array(
				'user_id' => $this->Auth->user('id'),
				'modified > ' => date('Y-m-d 23:59:59', strtotime('-1 week'))
			),
			'group' => 'timestamp',
			'order' => 'timestamp',
			'contain' => array()
		)), '{n}.0.timestamp', '{n}.0');
		$container_item_stats = Set::combine($this->ContainerItem->find('all', array(
			'fields' => array('COUNT(ContainerItem.id) as items', 'DATE(ContainerItem.modified) AS timestamp'),
			'conditions' => array(
				'Container.user_id' => $this->Auth->user('id'),
				'ContainerItem.modified > ' => date('Y-m-d 23:59:59', strtotime('-1 week'))
			),
			'group' => 'timestamp',
			'order' => 'timestamp',
			'contain' => array('Container')
		)), '{n}.0.timestamp', '{n}.0');
		for($current = date('Y-m-d', strtotime('-1 week')); $current <= date('Y-m-d'); $current = date('Y-m-d', strtotime("$current +1 day")))
			$graph_data[$current] = array();

		$container_data = Set::merge($graph_data, $container_stats, $container_item_stats);
		ksort($container_data);
		$graph_data = array();
		foreach($container_data as $date => $data)
			$graph_data[] = array($date, isset($data['containers'])? $data['containers'] : 0, isset($data['items']) ? $data['items'] : 0);

		$container_graph = array(
			'labels' => array(
				array('string' => 'Date'),
				array('number' => 'Containers'),
				array('number' => 'Items')
			),
			'data' => $graph_data,
			'title' => 'Containers Stats over past 7 days',
			'type' => 'line',
			'width' => 650,
		);

		$this->set(compact('total_containers', 'total_container_items', 'container_graph'));
		$this->set('active', 'containers.dashboard');
	}

	public function index() {
		$this->paginate = array(
			'conditions' => array(
				'Container.user_id' => $this->Session->read('Auth.User.id')
			),
			'contain' => array(),
			'limit' => $this->_container_page_limit
		);
		$this->set('containers', $this->paginate('Container'));
		$this->set('control', 'containers.index');
	}

	public function view($slug=null) {
		$container = $this->Container->find('first', array(
			'conditions' => array(
				'Container.slug' => $slug, 'Container.user_id' => $this->Auth->user('id')
			),
			'contain' => array(
				'ContainerItem'
			)
		));
		if(!empty($container)) {
			$this->verifyUser($container['Container']['id']);
			$this->set('container_slug', $container['Container']['slug']);
		}
		$this->paginate = array(
			'conditions' => array(
				'ContainerItem.container_id' => $container['Container']['id'],
			),
			'contain' => array(),
			'order' => 'ContainerItem.modified DESC',
			'limit' => $this->_container_page_limit
		);
		$container_items = $this->paginate('ContainerItem');
		$this->set(compact('container', 'container_items'));
		$this->set('control', 'containers.view');
	}

	public function add() {
		if(!empty($this->data)) {
			$this->data['Container']['user_id'] = $this->Session->read('Auth.User.id');
			$results = $this->Container->save($this->data);
			if($results) {
				$this->Session->setFlash('Successfully added new container', 'notification/success');
				$page = (int) ceil($this->Container->getTotalContainersPerUser($this->Session->read('Auth.User.id')) / $this->_container_page_limit);
				$this->redirect(array('controller' => 'containers', 'action' => 'view', $results['Container']['slug']));
			} else {
				$this->Session->setFlash('There was a problem saving your container.', 'notification/error');
			}
		}
	}

	public function delete($container_uuid) {
		if(empty($container_uuid)) {
			$this->Session->setFlash(__('Invalid container ID.', true), 'notification/error');
			$this->redirect(array('controller' => 'containers', 'action' => 'index'));
		} else {
			$this->verifyUser($container_uuid);
			if($this->Container->delete($this->Container->getIdByUUID($container_uuid)))
				$this->Session->setFlash(__('Successfully deleted container and all contained items.', true), 'notification/success');
			else
				$this->Session->setFlash(__('Error deleting container.', true), 'notification/error');
			$this->redirect(array('controller' => 'containers', 'action' => 'index'));
		}
	}

	public function print_label($container_uuid) {
		$this->layout = 'print';
		$this->verifyUser($container_uuid);
		$this->set('container', $this->Container->find('first', array(
			'fields' => array('id', 'uuid', 'slug', 'name'),
			'conditions' => array('Container.uuid' => $container_uuid),
			'contain' => array()
		)));
	}

}
?>