<?php
class indexController extends Controller {
	private $limit = 20;
	public function index($page = 1) {		
		$this->document->setActiveSection('admin');
		$this->document->setActiveItem('projects');
		
		if(!$this->user->isLogged()) {
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 1) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->library('pagination');
		$this->load->model('projects');
		
		$getOptions = array(
			'start' => ($page - 1) * $this->limit,
			'limit' => $this->limit
		);
		
		$total = $this->projectsModel->getTotalProjects($getData);
		$projects = $this->projectsModel->getProjects(array(), array(), array(), $getOptions);
		
		$paginationLib = new paginationLibrary();
		
		$paginationLib->total = $total;
		$paginationLib->page = $page;
		$paginationLib->limit = $this->limit;
		$paginationLib->url = $this->config->url . 'admin/projects/index/index/{page}';
		
		$pagination = $paginationLib->render();
		
		$this->data['projects'] = $projects;
		$this->data['pagination'] = $pagination;
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('admin/projects/index', $this->data);
	}
}
?>
