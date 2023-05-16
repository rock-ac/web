<?php
class indexController extends Controller {

	private $limit = 10;
	public function index($page = 1) {
		$this->document->setActiveSection('admin');
		$this->document->setActiveItem('processlogs');
		
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
		
		$total = $this->logsModel->getTotalLogs("detected_process");
		$logs = $this->logsModel->getLogs("detected_process", array(), array("id" => "DESC"), $options);
		
		$paginationLib = new paginationLibrary();
		
		$paginationLib->total = $total;
		$paginationLib->page = $page;
		$paginationLib->limit = $this->limit;
		$paginationLib->url = $this->config->url . 'admin/logs/process/index/index/{page}';
		
		$pagination = $paginationLib->render();
		
		$this->data['logs'] = $logs;
		$this->data['pagination'] = $pagination;
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('admin/logs/process/index', $this->data);
	}
}
?>
