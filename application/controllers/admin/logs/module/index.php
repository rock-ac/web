<?php
class indexController extends Controller {

	private $limit = 10;
	public function index($page = 1) {
		$this->document->setActiveSection('admin');
		$this->document->setActiveItem('modulelogs');
		
		if(!$this->user->isLogged()) {
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 1) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->library('pagination');
		$this->load->model('logs');
		
		$options = array(
			'start' => ($page - 1) * $this->limit,
			'limit' => $this->limit
		);
		
		$total = $this->logsModel->getTotalLogs("detected_module");
		$logs = $this->logsModel->getLogs("detected_module", array(), array("id" => "DESC"), $options);
		
		$paginationLib = new paginationLibrary();
		
		$paginationLib->total = $total;
		$paginationLib->page = $page;
		$paginationLib->limit = $this->limit;
		$paginationLib->url = $this->config->url . 'admin/logs/module/index/index/{page}';
		
		$pagination = $paginationLib->render();
		
		$this->data['logs'] = $logs;
		$this->data['pagination'] = $pagination;
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('admin/logs/module/index', $this->data);
	}
}
?>
