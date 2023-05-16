<?php
class viewController extends Controller {
	public function index($logid = null) {
		$this->document->setActiveSection('admin');
		$this->document->setActiveItem('memorylogs');
		
		if(!$this->user->isLogged()) {
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 0) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('logs');
		
		$error = $this->validate($logid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'admin/logs/memory/index');
		}
		
		$log = $this->logsModel->getLogById("detected_memory", $logid);
		$this->data['log'] = $log;
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('admin/logs/memory/view', $this->data);
	}
	
	private function validate($logid) {
		$result = null;
		
		$this->load->model('logs');
		if(!$this->logsModel->getTotalLogs("detected_memory", array('id' => (int)$logid))) {
			$result = "Запрашиваемый лог не существует!";
		}
        
		return $result;
	}
}
?>
