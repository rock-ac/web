<?php
class indexController extends Controller {
	public function index() {
		$userData = array(
			'logged' => $this->user->isLogged() > 0 ? true : false,
		);
		
		$this->data['user'] = $userData;

		return $this->load->view('main/index', $this->data);
	}
}
?>