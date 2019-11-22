<?php
use Restserver\Libraries\REST_Controller;
	defined('BASEPATH') OR exit('No direct script access allowed');

	require APPPATH . 'libraries/REST_Controller.php';
    require APPPATH . 'libraries/Format.php';	
    
Class Potong extends REST_Controller{
    public function __construct(){
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization");
        parent::__construct();
        $this->load->model('PotongModel');
        $this->load->library('form_validation');

        // Load these helper to create JWT tokens
        $this->load->helper(['jwt', 'authorization']);
    }

    private function verify_request($data_active)
		{
			// Get all the headers
			$headers = $this->input->request_headers();

			// Extract the token
			$token = $headers['Authorization'];

			// Use try-catch
			// JWT library throws exception if the token is not valid
			try {
				// Validate the token
				// Successfull validation will return the decoded user data else returns false
                $data = AUTHORIZATION::validateToken(str_replace("Bearer ","",$token));
				if ($data === false) {
					$status = parent::HTTP_UNAUTHORIZED;
					$response = ['status' => $status, 'msg' => 'Unauthorized Access!'];
					$this->response($response, $status);

					exit();
				} else {
					return $data_active;
				}
			} catch (Exception $e) {
				// Token is invalid
				// Send the unathorized access message
				$status = parent::HTTP_UNAUTHORIZED;
				$response = ['status' => $status, 'msg' => 'Unauthorized Access! '];
				$this->response($response, $status);
			}
        }

    public function index_get(){
        $data = $this->verify_request($this->db->get('potong')->result());

            // Send the return data as reponse
            if(parent::HTTP_OK){
                $status = false;
            }

			$response = ['error' => $status, 'message' => $data];

            $this->response($response, $status);
        // return $this->returnData($this->db->get('potong')->result(), false);
    }
    public function index_post($id = null){
        $validation = $this->form_validation;
        $rule = $this->PotongModel->rules();
        if($id == null){
            array_push($rule,[
                    'field' => 'nama',
                    'label' => 'nama',
                    'rules' => 'required'
                    // |regex_match[/^[a-zA-Z ]+$/]'
                ],
                [
                    // 'field' => 'phoneNumber',
                    // 'label' => 'phoneNumber',
                    // 'rules' => 'required|integer|is_unique[Potong.phoneNumber]'
                ]
            );
            $validation->set_rules($rule);
            if (!$validation->run()) {
                return $this->returnData($this->form_validation->error_array(), true);
            }
        }
        else{
            array_push($rule,
                [
                    'field' => 'name',
                    'label' => 'name',
                    'rules' => 'required'
                    // |regex_match[/^[a-zA-Z ]+$/]'
                ]
            );
        }
        $user = new UserData();
        $user->nama = $this->post('nama');
        $user->email = $this->post('email');
        $user->noTelepon = $this->post('noTelepon');
        $user->modelRambut = $this->post('modelRambut');
        $user->warna = $this->post('warna');
        $user->tanggal = $this->post('tanggal');
        $user->jam = $this->post('jam');
        $user->pemotong = $this->post('pemotong');
        $user->paket = $this->post('paket');
        $user->status = 0;
        if($id == null){
            $response = $this->PotongModel->store($user);
        }else{
            $response = $this->PotongModel->update($id);
        }
        return $this->returnData($response['msg'], $response['error']);
    }
    public function index_delete($id = null){
        if($id == null){
			return $this->returnData('Parameter Id Tidak Ditemukan', true);
        }
        $response = $this->PotongModel->destroy($id);
        return $this->returnData($response['msg'], $response['error']);
    }
    public function returnData($msg,$error){
        $response['error']=$error;
        $response['message']=$msg;
        return $this->response($response);
    }
}
Class UserData{

    public $nama;
    public $email;
    public $noTelepon;
    public $modelRambut;
    public $warna;
    public $tanggal;
    public $jam;
    public $pemotong;
    public $paket;
    public $status;
}