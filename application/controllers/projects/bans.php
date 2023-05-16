<?php
class bansController extends Controller {
	private $limit = 10;
	public function index($page = 1) {
		$this->document->setActiveSection('projects');
		$this->document->setActiveItem('bans');
		
		if(!$this->user->isLogged()) {
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 0) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->library('pagination');
		$this->load->model('bans');
		
		$partnerid = $this->user->getId();
		
		$options = array(
			'start' => ($page - 1) * $this->limit,
			'limit' => $this->limit
		);
		
		$total = $this->bansModel->getTotalBans(array('partner_id' => (int)$partnerid));
		$bans = $this->bansModel->getBans(array(), array(), array(), $options);
		
		$paginationLib = new paginationLibrary();
		
		$paginationLib->total = $total;
		$paginationLib->page = $page;
		$paginationLib->limit = $this->limit;
		$paginationLib->url = $this->config->url . 'projects/bans/index/{page}';
		
		$pagination = $paginationLib->render();
		
		$this->data['bans'] = $bans;
		$this->data['pagination'] = $pagination;
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('projects/bans', $this->data);
	}
}
?>
