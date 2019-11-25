<?php class Lapor_model extends CI_Model {

	/**
	 * Gunakan model ini untuk memindahkan semua method terkait laporan layanan mandiri.
	 * Saat ini laporan layanan mandiri masih bercampur dengan komentar artikel, dan
	 * seharusnya dipisah.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Simpan laporan yang dikirim oleh pengguna layanan mandiri
	 */
	public function insert()
	{
		//$data['komentar'] = strip_tags($_POST["komentar"]);
		$data['komentar'] = strip_tags(" Permohonan Surat ".$_POST["nama_surat"]." - ".$_POST["hp"]." - ".$_POST["komentar"]);
		/** ambil dari data session saja */
		$data['owner'] = $_SESSION['nama'];
		$data['email'] = $_SESSION['nik'];

		// load library form_validation
		$this->load->library('form_validation');
		//$this->form_validation->set_rules('komentar', 'Laporan', 'required');
		$this->form_validation->set_rules('komentar', 'Laporan');
		$this->form_validation->set_rules('owner', 'Nama', 'required');
		$this->form_validation->set_rules('email', 'NIK', 'required');

		if ($this->form_validation->run() == TRUE)
		{
			unset($_SESSION['validation_error']);
			$data['enabled'] = 2;
			$data['id_artikel'] = 775; //id_artikel untuk laporan layanan mandiri
			$outp = $this->db->insert('komentar',$data);
		}
		else
		{
			$_SESSION['validation_error'] = 'Form tidak terisi dengan benar';
			$_SESSION['success'] = -1;
		}
		if (!$outp)
			$_SESSION['success'] = -1;
		return ($_SESSION['success'] == 1);
	}

	public function get_dokumen_mandiri($nama_surat)
	{
		$this->db->select('nama_surat')
		         ->from('ref_dokumen_mandiri')
		         ->join('dokumen_mandiri','ref_dokumen_mandiri.id = dokumen_mandiri.ref_dokumen_mandiri_id')
		         ->join('tweb_surat_format','tweb_surat_format.id = dokumen_mandiri.tweb_surat_format_id')
		         ->where('tweb_surat_format.nama',$nama_surat);
		$query = $this->db->get();
		$data = $query->result_array();
		for ($i=0; $i<count($data); $i++)
		{
			$data[$i]['nama_surat']=($i+1).") ".$data[$i]['nama_surat'];
		}
		return $data;
	}

}
?>
