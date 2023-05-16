<?php
class controlController extends Controller {
	public function index($projectid = null) {	
		$this->document->setActiveSection('admin');
		$this->document->setActiveItem('projects');
		
		if(!$this->user->isLogged()) {
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 1) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('projects');
		$this->load->model('partners');
		
		$error = $this->validate($projectid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'projects/index');
		}
		
		$project = $this->projectsModel->getProjectById($projectid, array());
		$partner = $this->partnersModel->getPartnerById($project['partner_id'], array());
		$this->data['project'] = $project;
		$this->data['partner'] = $partner;

		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('admin/projects/control', $this->data);
	}
	
	public function action($projectid = null, $action = null) {
		if(!$this->user->isLogged()) {
			$this->data['status'] = "error";
			$this->data['error'] = "Вы не авторизированы!";
			return json_encode($this->data);
		}
		if($this->user->getAccessLevel() < 1) {
			$this->data['status'] = "error";
			$this->data['error'] = "У вас нет доступа к данному разделу!";
			return json_encode($this->data);
		}
		
		$this->load->model('projects');
		
		$error = $this->validate($projectid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}
		
		$project = $this->projectsModel->getProjectById($projectid);
		
		switch($action) {
			case 'block': {
				if($project['status'] != 0) {
					$this->projectsModel->updateProject($projectid, array('status' => 0));
					$this->data['status'] = "success";
					$this->data['success'] = "Вы успешно заблокировали проект!";
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "Проект уже заблокирован!";
				}
				break;
			}
			case 'unblock': {
				if($project['status'] == 0) {
					$this->projectsModel->updateProject($projectid, array('status' => 1));
					$this->data['status'] = "success";
					$this->data['success'] = "Вы успешно разблокировали проект!";
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "Проект должен быть заблокированн!";
				}
				break;
			}
			default: {
				$this->data['status'] = "error";
				$this->data['error'] = "Вы выбрали несуществующее действие!";
				break;
			}
		}
		
		return json_encode($this->data);
	}
	
	public function ajax($projectid = null) {
		if(!$this->user->isLogged()) {  
	  		$this->data['status'] = "error";
			$this->data['error'] = "Вы не авторизированы!";
			return json_encode($this->data);
		}
		if($this->user->getAccessLevel() < 1) {
			$this->data['status'] = "error";
			$this->data['error'] = "У вас нет доступа к данному разделу!";
			return json_encode($this->data);
		}
		
		$this->load->model('projects');
		
		$error = $this->validate($projectid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$gen_new_aes = @$this->request->post['gen_new_aes'];
				
			if($gen_new_aes) {
				$this->serversModel->updateServer($serverid, $serverData);
			}
				
			$this->data['status'] = "success";
			$this->data['success'] = "Вы успешно сгенерировали новый AES-ключ!";
		}

		return json_encode($this->data);
	}
	
	private function validate($projectid) {
		$result = null;
		
		if(!$this->projectsModel->getTotalProjects(array('id' => (int)$projectid))) {
			$result = "Запрашиваемый проект не существует!";
		}
		return $result;
	}
	
	private function block($projectid) {
		$this->projectsModel->updateProject($projectid, array('status' => 0));
	}
}
?>
