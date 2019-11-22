<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReviewModel extends CI_Model
{
    private $table = 'review';

    public $id;
    public $email;
    public $comment;
    public $rating;
    public $user;
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
        $this->db->get('review')->result(); 
    }
    public function store($request) {
        $this->email = $request->email; 
        $this->comment = $request->comment; 
        $this->rating = $request->rating; 
        $this->user = $request->user;
        if($this->db->insert($this->table, $this)){
            return ['msg'=>'Berhasil','error'=>false];
        }
        return ['msg'=>'Gagal','error'=>true];
    }
    public function update($request,$id) { 
        $updateData = ['comment' => $request->comment, 'rating' =>$request->rating];
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