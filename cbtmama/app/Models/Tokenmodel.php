<?php namespace App\Models;

use CodeIgniter\Model;

class Tokenmodel extends Model
{
    protected $table      = 'users';
    // protected $primaryKey = 'user_id';
    protected $allowedFields = ['token', 'start_date','end_date','status_cd'];

    public function getAll() {
        return $this->db->table('token')
                        ->select('*')
                        ->get();
    }

    public function getTokeByDate($start_date,$end_date,$group_id) {
        if ($group_id == "all") {
            return $this->db->table('token a')
                        ->select('*')
                        ->join('materi b','b.materi_id = a.group_id','LEFT')
                        ->join('users c','c.user_id = a.user_id','LEFT')
                        ->join('person d','d.person_id = c.person_id','LEFT')
                        ->where('a.start_date >=', $start_date)
                        ->where('a.end_date >=', $start_date)
                        ->where('a.start_date <=', $end_date)
                        ->where('a.end_date <=', $end_date)
                        ->get();
        } else {
            return $this->db->table('token a')
                        ->select('*')
                        ->join('materi b','b.materi_id = a.group_id')
                        ->join('users c','c.user_id = a.user_id','LEFT')
                        ->join('person d','d.person_id = c.person_id','LEFT')
                        ->where('a.start_date >=', $start_date)
                        ->where('a.end_date >=', $start_date)
                        ->where('a.start_date <=', $end_date)
                        ->where('a.end_date <=', $end_date)
                        ->where('a.group_id', $group_id)
                        ->get();
        }
    }

    public function simpantoken($data) {
        return $this->db->table('token')
                        ->insert($data);
    }

    public function updatetoken($token_id,$data) {
        return $this->db->table('token')
                        ->set($data)
                        ->where("token_id",$token_id)
                        ->update();
    }

    public function checktoken($token, $group_id, $materi_id, $tokenuser, $user_id) {
        $date = date("Y-m-d");
        return $this->db->table('token')
                        ->select('*')
                        ->where('token', $token)
                        ->where('tokenuser', $tokenuser)
                        ->where('CURDATE() BETWEEN start_date AND end_date', null, false)
                        ->where('group_id', $materi_id)
                        // ->where('materi_id',$materi_id)
                        ->where('tokenuser', $tokenuser)
                        ->where('user_id', $user_id)
                        ->get();
    }
}