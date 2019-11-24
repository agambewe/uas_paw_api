<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PekerjaModel extends CI_Model
{
    private $table = 'pekerja';

    public $id;
    public $nama;
    public $posisi;
    public $status;
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
        $this->db->get('pekerja')->result(); 
    } 
    public function getPemotong() { return 
        $this->db->where('posisi','pemotong')->get('pekerja')->result(); 
    } 
    // public function getNamaPemotong() { return 
    //     $this->db->select('nama')->where(['posisi' => 'pemotong'])->get('pekerja')->result_array(); 
    // } 
    public function getAvailPemotong() { return 
        $this->db->where(['posisi' => 'pemotong','status' => 1])->get('pekerja')->result(); 
    } 
    public function getPemakeup() { return 
        $this->db->where('posisi','pemakeup')->get('pekerja')->result(); 
    } 
    public function store($request) {
        $this->nama = $request->nama; 
        $this->posisi = $request->posisi;
        $this->status = 1;
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