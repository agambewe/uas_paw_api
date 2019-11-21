<?php
	use Restserver\Libraries\REST_Controller;
	defined('BASEPATH') OR exit('No direct script access allowed');

	require APPPATH . 'libraries/REST_Controller.php';
	require APPPATH . 'libraries/Format.php';	

    class User extends REST_Controller {
        public function __construct() {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE');
            header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, Authorization');

            parent::__construct();
            $this->load->model('UserModel');
            $this->load->library("mailer");
			$this->load->library('form_validation');
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
        
		public function login_post(){
        // Have dummy user details to check user credentials
        // send via postman
        // Extract user data from POST request
        $email = $this->post('email');
		$password = $this->post('password');
        $data = $this->db->get_where('users',['email' => $email])->result_array();
        // Check if valid user
        if ($email === $data[0]['email'] && password_verify($password,  $data[0]['password'])) {
            if($data[0]['status'] == 0){
                $this->response(['msg' => 'Akun belum diaktivasi!'], parent::HTTP_NOT_FOUND);
            }else{
                // Create a token from the user data and send it as reponse
                $token = AUTHORIZATION::generateToken(['email' => $data[0]['email']]);
                // Prepare the response
                $status = parent::HTTP_OK;
                $response = ['status' => $status, 'token' => $token];
                $this->response($response, $status);
            }
        }
        else {
                $this->response(['msg' => 'Invalid email or password!'], parent::HTTP_NOT_FOUND);
            }
        }

        public function verif_get(){
            $email = $this->get('email');
            $hash = $this->get('hash');
            $status = false;

            $response = $this->UserModel->verif($email, $hash);

            return $this->returnData($response['msg'], $response['error']);
        }
        
        public function index_get() {
			$data = $this->verify_request($this->db->get('users')->result());

            // Send the return data as reponse
            if(parent::HTTP_OK){
                $status = false;
            }

			$response = ['error' => $status, 'message' => $data];

            $this->response($response, $status);
            // return $this->returnData($this->db->get('users')->result(), false);
        }

        public function index_post($id = null) {
            $validation = $this->form_validation;
            $rule = $this->UserModel->rules();

            if ($id == null) {
                array_push($rule, [
                    'field' => 'password',
                    'label' => 'password',
                    'rules' => 'required'
                ],
                [
                    'field' => 'email',
                    'label' => 'email',
                    'rules' => 'required|valid_email|is_unique[users.email]'    
                ]);
            } else {
                array_push($rule, [
                    'field' => 'email',
                    'label' => 'email',
                    'rules' => 'required|valid_email'
                ]);
            }

            $validation->set_rules($rule);

            if (!$validation->run()) 
                return $this->returnData($this->form_validation->error_array(), true);
            
            $user = new UserData();
            $user->name = $this->post('name');
            $user->password = $this->post('password');
            $user->email = $this->post('email');
            $user->hash = md5(rand(0,1000));
            $user->status = 0;
            $user->image = "default.png";

            $mail = new PHPMailer\PHPMailer\PHPMailer();

            if ($id == null){
                $base_url = "http://iconic-shoes-care.com/";
                $url = "localhost/TUBES/";
                $noD = "style='text-decoration: none!important; color: inherit;'";
                $mail_body = "
                <p>Hi ".$user->name.",</p>
                <p>Thanks for Registration. Your account will work only after your email verification.</p>
                <button><a ".$noD." href=".$url."user/verif?email=".$user->email."&hash=".$user->hash."><b>Please Click this button to verified your email address</b></a></button>
                <p>Best Regards,<br />IconicShoesCare</p>
                ";

                try {
                    $mail->SMTPDebug = 0;  
                    $mail->IsSMTP();        //Sets Mailer to send message using SMTP
                    $mail->Host = 'smtp.gmail.com';  //Sets the SMTP hosts of your Email hosting, this for Godaddy
                    $mail->Port = 587;        //Sets the default SMTP server port
                    $mail->SMTPAuth = true;       //Sets SMTP authentication. Utilizes the Username and Password variables
                    $mail->Username = 'iconicshoescare@gmail.com';     //Sets SMTP username
                    $mail->Password = 'IconicShoesCare!';     //Sets SMTP password
                    $mail->SMTPSecure = 'tls';       //Sets connection prefix. Options are "", "ssl" or "tls"
                    $mail->From = 'info@iconic-shoes-care.com';   //Sets the From email address for the message
                    $mail->FromName = 'IconicShoesCare';     //Sets the From name of the message
                    $mail->AddAddress($user->email, $user->name);  //Adds a "To" address   
                    $mail->WordWrap = 50;       //Sets word wrapping on the body of the message to a given number of characters
                    $mail->IsHTML(true);       //Sets message type to HTML    
                    $mail->Subject = 'Email Verification';   //Sets the Subject of the message
                    $mail->Body = $mail_body;       //An HTML or plain text message body
                    if($mail->Send())        //Send an Email. Return true on success or false on error
                    {
                        $response = $this->UserModel->store($user);
                    }
                } catch (Exception $e) {
                    return $this->returnData("Message could not be sent. Mailer Error:", $mail->ErrorInfo);
                }
            }
            else 
                $response = $this->UserModel->update($user, $id);
            return $this->returnData($response['msg'], $response['error']);
		}
		
        public function index_delete($id = null) {
            if ($id == null)
                return $this->returnData('Parameter ID Tidak Ditemukan', true);

            $response = $this->UserModel->destroy($id);
            return $this->returnData($response['msg'], $response['error']);
        }

        public function returnData($msg, $error) {
            $response['error'] = $error;
            $response['message'] = $msg;

            return $this->response($response);
        }
    }

    class UserData {
        public $name;
        public $password;
        public $email;
        public $hash;
        public $status;
        public $image;
    }
