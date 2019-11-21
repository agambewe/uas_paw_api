<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MakeupModel extends CI_Model
{
    private $table = 'makeup';

    public $id;
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
    public function store($request) {
        $this->nama = $request->nama; 
        $this->kategori = $request->kategori; 
        $this->tanggal = $request->tanggal;
        $this->noTelepon = $request->noTelepon;
        $this->pemakeup = $request->pemakeup;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    public function update($request,$id) { 
        // $updateData = ['name' => $request->name, 'address' =>$request->address, 'phoneNumber' =>$request->phoneNumber];
        // if($this->db->where('id',$id)->update($this->table, $updateData)){
        //     return ['msg'=>'Berhasil','error'=>false];
        // }
        // return ['msg'=>'Gagal','error'=>true];
        return ['msg'=>'gakepake ini','error'=>true];
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