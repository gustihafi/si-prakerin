<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Bimbingan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('perusahaan_model','pengajuan_model','konsultasi_model','pembimbing_model'));
		$this->load->helper(array('notification','master'));
		!$this->session->userdata('level')?redirect(site_url('main')):null;
		$id = $this->session->userdata('id');
		$mahasiswa = masterdata( 'tb_mahasiswa',array('nim'=>$id),array('alamat_mhs','email_mhs','jenis_kelamin_mhs'),false);
		if($mahasiswa){
			($mahasiswa->alamat_mhs == null || $mahasiswa->email_mhs == null || $mahasiswa->jenis_kelamin_mhs == null)?redirect(site_url('user/profile')):null;
		}
		//Do your magic here
	}
    public function index()
    {
    	//level
        $level = $this->session->userdata('level');
        switch($level){
            case 'mahasiswa':
            $data['menus'] = array(
                array('name'=>'Konsultasi Bimbingan',
                    'href'=>site_url('bimbingan?m=konsultasi'),
                    'icon'=>'fas fa-id-badge',
                    'desc'=>'Konsultasi bimbingan kepada dosen pembimbing masing-masing yang diwajibkan tiap minggunya'),
                array('name'=>'Pengajuan Sidang',
                    'href'=>site_url('bimbingan?m=pengajuan'),
                    'icon'=>'fas fa-building',
                    'desc'=>'Pengajuan sidang wajib di konsultasikan kepada dosen pembimbing'),
            );
            break;
            case 'dosen':
            $data['menus'] = array(
                array('name'=>'Mahasiswa Bimbingan',
                    'href'=>site_url('bimbingan?m=bimbinganmhs'),
					'icon'=>'fas fa-id-badge',
					'desc'=>'Bimbingan Prakerin masing-masing dosen'),
                array('name'=>'Approval Sidang',
                    'href'=>site_url('bimbingan?m=approvesidang'),
					'icon'=>'fas fa-id-badge',
					'desc'=>'Persetujuan permintaan sidang mahasiswa oleh dosen pembimbing')
            );
            break;
            default:$data['menus']= array();
        }
        //Route
		if(isset($_GET['m'])){
			switch ($_GET['m']){
				case 'bimbinganmhs':
					if(isset($_GET['a']) and $_GET['a'] == 'accept'){
						return $this->acc_bimbingan_mhs();
					}
					if(isset($_GET['a']) and $_GET['a'] == 'decline'){
						return $this->dec_bimbingan_mhs();
					}
					return $this->index_bimbingan_mhs();
					break;
				case 'approvesidang':
					return $this->index_approve_sidang();
					break;
				case 'konsultasi':
					if(isset($_GET['q']) and $_GET['q'] == 'i'){
						return $this->insert_konsultasi();
					}
					elseif(isset($_GET['q']) and $_GET['q'] == 'u'){
						return $this->update_konsultasi();
					}
					elseif(isset($_GET['q']) and $_GET['q'] == 'd'){
						return $this->delete_konsultasi();
					}
					return $this->index_konsultasi();
					break;
				case 'pengajuan':
					return $this->index_pengajuan();
					break;
				default:null;
			}
		}
		//default route
		$this->load->view('user/bimbingan',$data);
    }

	// Dosen
	function index_bimbingan_mhs(){
		$konsultasi = $this->konsultasi_model;
		$pembimbing = $this->pembimbing_model;
		$nip_nik = $this->session->userdata('nip_nik');
		$data['mahasiswas'] = array();
		if(isset($nip_nik)){
			$data['mahasiswas'] = $pembimbing->get_all_with_mhs($nip_nik);
		}
		return $this->load->view('user/bimbingan_mahasiswa',$data);
	}
	function acc_bimbingan_mhs(){
		$konsultasi = $this->konsultasi_model;
		if($konsultasi->accept()){
			$this->session->set_flashdata('status',(object)array('status'=>'Success','message'=>'Konsultasi berhasil dikonfirmasi','alert'=>'success'));
		}
		else{
			$this->session->set_flashdata('status',(object)array('status'=>'Error','message'=>'Konsultasi gagal dikonfirmasi','alert'=>'danger'));
		}
		redirect(site_url('bimbingan?m=bimbinganmhs'));

	}
	function dec_bimbingan_mhs(){
		$konsultasi = $this->konsultasi_model;
		if($konsultasi->accept()){
			$this->session->set_flashdata('status',(object)array('status'=>'Success','message'=>'Konsultasi berhasil dikonfirmasi','alert'=>'success'));
		}
		else{
			$this->session->set_flashdata('status',(object)array('status'=>'Error','message'=>'Konsultasi gagal dikonfirmasi','alert'=>'danger'));
		}
		redirect(site_url('bimbingan?m=bimbinganmhs'));

	}

	function index_approve_sidang(){
//		return
	}

	// Mahasiswa
	function index_konsultasi(){
		$konsultasi = $this->konsultasi_model;
		if(isset($_POST['events'])){
			echo json_encode($konsultasi->get());
			return null;
		}
		return $this->load->view('user/bimbingan_konsultasi');
	}
	function insert_konsultasi(){
		$konsultasi = $this->konsultasi_model;
		if(isset($_GET['q'])){
			$konsultasi->insert();
		}
	}
	function update_konsultasi(){
		$konsultasi = $this->konsultasi_model;
		if(isset($_GET['q'])){
			$konsultasi->update();
		}
	}
	function delete_konsultasi(){
		$konsultasi = $this->konsultasi_model;
		if(isset($_GET['q'])){
			$konsultasi->delete();
		}
	}


	function index_pengajuan(){

	}

}

/* End of file Bimbingan.php */
 ?>
