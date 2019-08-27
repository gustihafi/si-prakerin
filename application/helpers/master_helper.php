<?php
if ( ! defined( 'BASEPATH' ) ) {
	exit( 'No direct script access allowed' );
}
//change name to snake case
if ( ! function_exists( 'masterdata' ) ) {
	function masterdata( $table, $where = null, $select = null, $resultArray = false,$order = null ) {
		$ci =& get_instance();
		if ( $select != null ) {
			$ci->db->select( $select );
		}

		if($order){
			$ci->db->order_by($order);
		}

		if ( $where != null ) {
			if ( $resultArray ) {
				return $ci->db->get_where( $table, $where )->result();
			}

			return $ci->db->get_where( $table, $where )->row();
		}

		return $ci->db->get( $table )->result();
	}
}
if ( ! function_exists( 'getCurrentTahun' ) ) {
	function getCurrentTahun() {
		$ci =& get_instance();
		$ci->db->select( 'tb_waktu.id_waktu as id,tahun_akademik.tahun_akademik as now,tahun_akademik.id_tahun_akademik as tahun_id' )->from( 'tb_waktu' )->join( 'tahun_akademik', 'tb_waktu.id_tahun_akademik = tahun_akademik.id_tahun_akademik', 'INNER' );

		return $ci->db->get()->row();
	}
}

if ( ! function_exists( 'dynamic_insert' ) ) {
	function dynamic_insert($table,$data){
		$ci =& get_instance();
		$ci->db->insert($table,$data);
	}
}
if ( ! function_exists( 'custom_query' ) ) {
	function custom_query($query,$resultArray = false){
		$ci =& get_instance();
		if($resultArray){
			return $ci->db->query($query)->result();
		}
		return $ci->db->query($query)->row();
	}
}
if ( ! function_exists( 'datajoin' ) ) {
	function datajoin( $table, $where = null, $select = null, $join = null, $orwhere = null ,$order = null) {
		$ci =& get_instance();
		if ( $select != null ) {
			$ci->db->select( $select );
		}
		if ( $where != null ) {
			$ci->db->where( $where );
		}
		if ( $orwhere != null ) {
			$ci->db->or_where( $orwhere );
		}
		if ( $join != null ) {
			if ( is_array( $join[0] ) ) {
				foreach ( $join as $jo ) {
					$ci->db->join( $jo[0], $jo[1], $jo[2] );
				}
			} else {
				$ci->db->join( $join[0], $join[1], $join[2] );
			}
		}
		if($order != null){
			$ci->db->order_by($order);
		}
		return $ci->db->get( $table )->result();

	}
}
/* End of file ModelName.php */
?>
