<?php

    defined('BASEPATH') OR exit('No direct script access allowed');


    class UserModel extends CI_Model {
        private $table = 'users';
        public $id;
        public $name;
        public $email;
        public $password;
        public $hash;
        public $status;
        public $image;
        public $rule = [
            [
                'field' => 'name',
                'label' => 'name',
                'rules' => 'required'
            ],
        ];


        public function Rules() {
            return $this->rule;
        }

        public function getAll() {
            return $this->db->order_by("id", "desc")->get('users')->result();
        }

        public function find($email) {
            return $this->db->select('*')->where(array('email' => $email))->get($this->table)->row();
        }

        public function verif($email, $hash) {
            $where = ['email ' => $email , 'hash ' => $hash];
            $updateData = ['status' => 1];
            if ($this->db->where($where)->update($this->table, $updateData)) {
                return [
                    'msg' => 'Berhasil',
                    'error' => FALSE,
                ];
            }

            return [
                'msg' => 'Gagal',
                'error' => TRUE,
            ];
        }

        public function store($request) {
            $this->name = $request->name;
            $this->email = $request->email;
            $this->password = password_hash($request->password, PASSWORD_BCRYPT);
            $this->hash = $request->hash;
            $this->status = $request->status;
            $this->image = $request->image;

            if ($this->db->insert($this->table, $this)) {
                return [
                    'msg' => 'Berhasil',
                    'error' => FALSE,
                ];
            }

            return [
                'msg' => 'Gagal',
                'error' => TRUE,
            ];
        }


        public function update($request, $id) {
            if(strlen($request->email)<1){
                $dataUpdt = $this->db->select('*')->where(array('id' => $id))->get($this->table)->row();
                if($dataUpdt->status==0){
                    $statusUpdt = 1;
                }else{
                    $statusUpdt = 0;
                }
                $updateData = ['status' => $statusUpdt];
                if($this->db->where('id',$id)->update($this->table, $updateData)){
                    return ['msg'=>'Berhasil','error'=>false];
                }
                return ['msg'=>'Gagal','error'=>true];
            }else{
                $updateData = [
                    'email' => $request->email,
                    'name' => $request->name,
                    'image' => $request->image,
                    'password' => password_hash($request->password, PASSWORD_BCRYPT)
                ];

                if ($this->db->where('id', $id)->update($this->table, $updateData)) {
                    return [
                        'msg' => 'Berhasil',
                        'error' => FALSE,
                    ];
                }
            }

            return [
                'msg' => 'Gagal',
                'error' => TRUE,
            ];
        }


        public function destroy($id) {
            if (empty($this->db->select('*')->where(array('id' => $id))->get($this->table)->row())) {
                return [
                    'msg' => 'ID Tidak Ditemukan!',
                    'error' => TRUE,
                ];
            }
            
            if ($this->db->delete($this->table, array('id' => $id))) {
                return [
                    'msg' => 'Berhasil',
                    'error' => FALSE,
                ];
            }

            return [
                'msg' => 'Gagal',
                'error' => TRUE,
            ];
        }
    }
?>
