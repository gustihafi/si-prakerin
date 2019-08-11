<?php

use Tools\Excel;

require APPPATH . 'libraries/Excel.php';
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

// Use upload Library and Excel library

class Dosen extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->helper( array( 'upload', 'master', 'notification' ) );
		$this->load->model( array( 'pembimbing_model', 'akun_model','pilihperusahaan_model','dosen_prodi_model' ));
		$this->load->library( 'form_validation' );
		//middleware
		! $this->session->userdata( 'level' ) ? redirect( site_url( 'main' ) ) : null;
	}

	public function index() {
		$join          = array( 'tahun_akademik', 'tb_waktu.id_tahun_akademik = tahun_akademik.id_tahun_akademik', 'inner' );
		$tahunAkademik = datajoin( 'tb_waktu', null, 'tahun_akademik.tahun_akademik', $join, null );
		$level         = $this->session->userdata( 'level' );
		switch ( $level ) {
			case 'admin':
				$data['menus'] = array(
					array(
						'name' => 'Kelola Dosen Program Studi',
						'href' => site_url( 'dosen?m=dosen_prodi' ),
						'icon' => 'fas fa-users',
						'desc' => 'Manajemen dosen pembimbing mahasiswa  ' . $tahunAkademik[0]->tahun_akademik
					),
					array(
						'name' => 'Kelola Pembimbing ' . $tahunAkademik[0]->tahun_akademik,
						'href' => site_url( 'dosen?m=pembimbing' ),
						'icon' => 'fas fa-users',
						'desc' => 'Manajemen dosen pembimbing mahasiswa  ' . $tahunAkademik[0]->tahun_akademik
					),
				);
				break;
			case 'koordinator prodi':
				$data['menus'] = array(
					array(
						'name' => 'Kelola Pembimbing ' . $tahunAkademik[0]->tahun_akademik,
						'href' => site_url( 'dosen?m=pembimbing' ),
						'icon' => 'fas fa-users',
						'desc' => 'Manajemen dosen pembimbing mahasiswa  ' . $tahunAkademik[0]->tahun_akademik
					)
				);
				break;
			//if there are not level except in case, it will throw to error with code 403
			default:
				show_error( "Access Denied. You Do Not Have The Permission To Access This Page On This
            Server", 403, "Forbidden, you don't have pemission" );
		}
		//get variable
		//sub menu, with crud
		$get = $this->input->get();
		if ( isset( $get['m'] ) ) {
			switch ( $get['m'] ) {
				case 'pembimbing':
					if ( isset( $get['q'] ) && $get['q'] == 'i' ) {
						return $this->create_pembimbing();
					}
					if ( isset( $get['q'] ) && $get['q'] == 'bulk' ) {
						return $this->bulk_pembimbing();
					}
					if ( isset( $get['q'] ) && $get['q'] == 'u' ) {
						return $this->edit_pembimbing();
					}
					if ( isset( $get['q'] ) && $get['q'] == 'd' ) {
						return $this->remove_pembimbing();
					}

					return $this->index_pembimbing();
					break;
				case 'dosen_prodi':
					if ( isset( $get['q'] ) && $get['q'] == 'u' ) {
						return $this->dosen_prodi_management();
					}
					return $this->index_dosen_prodi();
					break;
				default:
					redirect( site_url( 'dosen' ) );
			}
		}

		$this->load->view( 'admin/dosen', $data );


	}

	//kelola dosen prodi
	public function index_dosen_prodi(){
		$dosen_prodi = $this->dosen_prodi_model;
		$data['dosens'] = $dosen_prodi->get();
		$this->load->view('admin/dosen_prodi',$data);
	}
	public function dosen_prodi_management(){
		$dosen_prodi = $this->dosen_prodi_model;
		if(isset($_POST['nip']) and isset($_POST['prodi'])){
			if($dosen_prodi->replace()){
				echo json_encode(array('status'=>'success'));
				return;
			}
			echo json_encode(array('status'=>'failed'));
			return;
		}
		$this->load->view('admin/dosen_prodi_kelola');
	}





	//pembimbing
	public function index_pembimbing() {
		$data['mahasiswa'] = array();
		//null, still consider how data goes
		$this->load->view( 'admin/dosen_pembimbing2', $data );
	}

	public function bulk_pembimbing() {
		if($this->pembimbing_model->insert_batch()){
			$this->session->set_flashdata( 'status', array('message'=>'Data perusahaan berhasil dirubah','type'=>'success'));
		}
	}

	public function create_pembimbing() {

	}

	public function edit_pembimbing() {

	}

	public function remove_pembimbing() {

	}


} ?>
