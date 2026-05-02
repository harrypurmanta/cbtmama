<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\Usersmodel;
use App\Models\Soalmodel;
use App\Models\Tokenmodel;
use TCPDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Token extends BaseController
{
    protected $usermodel;
    protected $soalmodel;
    protected $tokenmodel;
    protected $session;

    public function __construct()
	{
		$this->session = \Config\Services::session();
        $this->usermodel = new Usersmodel();
        $this->soalmodel = new Soalmodel();
        $this->tokenmodel = new Tokenmodel();
	}


    public function index()
    {
        if ($this->session->get("user_nm") == "") {
			return redirect('/');
		} else {
           
            $user_id = $this->request->getUri()->getSegment(3);
            $data = [           
                "token" => $this->tokenmodel->getAll()->getResult(),
                'materi' => $this->soalmodel->getjawAllJMateri()->getResult(),
                'user' => $this->usermodel->getBySiswa()->getResult()
            ];

            return view('admin/token',$data);
        }
    }

    public function getUserAjax()
    {
        $search = $this->request->getPost('search');

        $data = $this->usermodel->getBySiswa()->getResult();

        $result = [];

        foreach ($data as $row) {
            $result[] = [
                "id" => $row->user_id,
                "text" => $row->person_nm // sesuaikan field nama
            ];
        }

        return $this->response->setJSON($result);
    }

    public function loadDataToken() {
        $start_date = $this->request->getPost('start_date');
        $end_date = $this->request->getPost('end_date');
        $group_id = $this->request->getPost('group_id');
        
        $retToken = $this->tokenmodel->getTokeByDate($start_date,$end_date,$group_id)->getResult();

        echo json_encode($retToken);
    }

    public function simpantoken() {
        if ($this->session->get("user_nm") == "") {
			return redirect('/');
		} else {
            $token = $this->request->getPost('token');
            $token_user = $this->request->getPost('token_user');
            $user_tambah = $this->request->getPost('user_tambah');
            $start_date = $this->request->getPost('start_date');
            $end_date = $this->request->getPost('end_date');
            $group_id = $this->request->getPost('group_id');

            $data = [           
                "token" => $token,
                "tokenuser" => $token_user,
                "user_id" => $user_tambah,
                "start_date" => $start_date,
                "end_date" => $end_date,
                "group_id" => $group_id
            ];

            $simpantoken = $this->tokenmodel->simpantoken($data);
            if ($simpantoken) {
                $ret = "sukses";
            } else {
                $ret = "gagal";
            }
            echo json_encode($ret);
        } 
    }

    public function updatetoken() {
        if ($this->session->get("user_nm") == "") {
			return redirect('/');
		} else {
            $token_id = $this->request->getPost('token_id');
            $token = $this->request->getPost('token');
            $start_date = $this->request->getPost('start_date');
            $end_date = $this->request->getPost('end_date');
            $group_id = $this->request->getPost('group_id');

            $data = [           
                "token" => $token,
                "start_date" => $start_date,
                "end_date" => $end_date,
                "group_id" => $group_id,
            ];

            $update = $this->tokenmodel->updatetoken($token_id,$data);
            if ($update) {
                $ret = "sukses";
            } else {
                $ret = "gagal";
            }
            echo json_encode($ret);
        } 
    }

    public function hapustoken() {
        if ($this->session->get("user_nm") == "") {
			return redirect('/');
		} else {
            $token_id = $this->request->getPost('token_id');
            $token = $this->request->getPost('token');
            $start_date = $this->request->getPost('start_date');
            $end_date = $this->request->getPost('end_date');

            $data = [           
                "token" => $token,
                "start_date" => $start_date,
                "end_date" => $end_date,
            ];

            $update = $this->tokenmodel->updatetoken($token_id,$data);
            if ($update) {
                $ret = "sukses";
            } else {
                $ret = "gagal";
            }
            echo json_encode($ret);
        } 
    }
}
