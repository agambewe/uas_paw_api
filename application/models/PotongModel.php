<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PotongModel extends CI_Model
{
    private $table = 'potong';

    public $id;
    public $nama;
    public $noTelepon;
    public $modelRambut;
    public $warna;
    public $tanggal;
    public $jam;
    public $pemotong;
    public $paket;
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
        $this->db->get('potong')->result(); 
    } 
    public function store($request) {
        $this->nama = $request->nama; 
        $this->noTelepon = $request->noTelepon; 
        $this->modelRambut = $request->modelRambut;
        $this->warna = $request->warna;
        $this->tanggal = $request->tanggal;
        $this->jam = $request->jam;
        $this->pemotong = $request->pemotong;
        $this->paket = $request->paket;
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