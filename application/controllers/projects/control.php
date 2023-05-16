<?php
class controlController extends Controller {
	public function index($projectid = null) {
		$this->document->setActiveSection('projects');
		$this->document->setActiveItem('index');
		
		if(!$this->user->isLogged()) {
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 0) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('projects');
		
		$error = $this->validate($projectid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'projects/index');
		}
		
		$project = $this->projectsModel->getProjectById($projectid);
		$this->data['project'] = $project;
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('projects/control', $this->data);
	}
	
	private function validate($projectid) {
		$result = null;
		
		$userid = $this->user->getId();
		
		$this->load->model('projects');
		if(!$this->projectsModel->getTotalProjects(array('id' => (int)$projectid))) {
			$result = "Запрашиваемый проект не существует!";
		}
		return $result;
	}
}
?>
