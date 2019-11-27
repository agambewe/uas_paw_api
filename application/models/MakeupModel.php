<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MakeupModel extends CI_Model
{
    private $table = 'makeup';

    public $id;
    public $email;
    public $nama;
    public $kategori;
    public $tanggal;
    public $noTelepon;
    public $pemakeup;
    public $rule = [ 
        [
            // 'field' => 'name',
            // 'label' => 'name',
            // 'rules' => 'required|regex_match[/^[a-zA-Z ]+$/]'
        ],
        [
            // 'field' => 'phoneNumber',
            // 'label' => 'phoneNumber',
            // 'rules' => 'required|integer'
        ],
    ];
    public function Rules() { return $this->rule; }

    public function getAll() { return 
        $this->db->get('makeup')->result(); 
    } 
    public function getTerlayaniMakeup() { return 
        $this->db->order_by("tanggal", "desc")->where(['status' => 1])->get('makeup')->result();
    }
    public function find($email) {
        return $this->db->order_by("tanggal", "desc")->where(['status' => 0, 'email' => $email])->get($this->table)->result();
    }
    public function store($request) {
        $this->email = $request->email; 
        $this->nama = $request->nama; 
        $this->kategori = $request->kategori; 
        $this->tanggal = $request->tanggal;
        $this->noTelepon = $request->noTelepon;
        $this->pemakeup = $request->pemakeup;
        $this->status = $request->status;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    public function update($id) { 
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
        // return ['msg'=>'gakepake ini','error'=>true];
    }
    public function destroy($id){
        if (empty($this->db->select('*')->where(array('id' => $id))->get($this->table)->row())) return ['msg'=>'Id tidak ditemukan','error'=>true];
        
        if($this->db->delete($this->table, array('id' => $id))){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
}
?>