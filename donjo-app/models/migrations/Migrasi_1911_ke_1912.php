<?php
class Migrasi_1911_ke_1912 extends CI_model {

  public function up()
  {
  	// Perbaiki form admin data keuangan
		$this->db->where('isi','keuangan.php')->update('widget',array('form_admin'=>'keuangan/impor_data'));
		// Buat kolom tweb_rtm.no_kk menjadi unique
		$fields = array();
		$fields['no_kk'] = array(
				'type' => 'VARCHAR',
				'constraint' => 30,
			  'null' => FALSE,
				'unique' => TRUE
		);
	  $this->dbforge->modify_column('tweb_rtm', $fields);
		// Buat tabel untuk mencatat riwayat ekspor data
		if (!$this->db->table_exists('log_ekspor') )
		{
			$query = "
			CREATE TABLE IF NOT EXISTS `log_ekspor` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`tgl_ekspor` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`kode_ekspor` varchar(100) NOT NULL,
				`semua` int(1) NOT NULL DEFAULT '1',
				`dari_tgl` date DEFAULT NULL,
				`total` int NOT NULL DEFAULT '0',
				PRIMARY KEY (`id`)
			)";
			$this->db->query($query);
		}
	  // Aktifkan submodul informasi publik
		$modul_nonmenu = array(
			'id' => '96',
			'modul' => 'Informasi Publik',
			'url' => 'informasi_publik',
			'aktif' => '1',
			'ikon' => '',
			'urut' => '0',
			'level' => '0',
			'parent' => '52',
			'hidden' => '2',
			'ikon_kecil' => ''
		);
		$sql = $this->db->insert_string('setting_modul', $modul_nonmenu) . " ON DUPLICATE KEY UPDATE modul = VALUES(modul), url = VALUES(url), parent = VALUES(parent)";
		$this->db->query($sql);
		// Perbaiki nilai default kolom untuk sql_mode STRICT_TRANS_TABLE
	  $this->dbforge->modify_column('inbox', 'ReceivingDateTime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('inventaris_asset', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('inventaris_gedung', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('inventaris_jalan', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('inventaris_kontruksi', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('inventaris_peralatan', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('inventaris_tanah', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('outbox', 'InsertIntoDB TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('outbox', 'SendingDateTime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('outbox', 'SendingTimeOut TIMESTAMP NULL DEFAULT NULL');
	  $this->dbforge->modify_column('sentitems', 'InsertIntoDB TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('sentitems', 'SendingDateTime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('teks_berjalan', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('mutasi_inventaris_asset', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('mutasi_inventaris_gedung', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('mutasi_inventaris_jalan', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('mutasi_inventaris_peralatan', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('mutasi_inventaris_tanah', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
	  $this->dbforge->modify_column('program_peserta', 'kartu_nik VARCHAR(30) NULL DEFAULT NULL');
	  $this->dbforge->modify_column('program_peserta', 'kartu_peserta VARCHAR(100) NULL DEFAULT NULL');
	  $this->dbforge->modify_column('lokasi', 'lat VARCHAR(30) NULL DEFAULT NULL');
	  $this->dbforge->modify_column('lokasi', 'lng VARCHAR(30) NULL DEFAULT NULL');
	  $this->dbforge->modify_column('lokasi', 'foto VARCHAR(100) NULL DEFAULT NULL');
	  $this->dbforge->modify_column('lokasi', 'id_cluster INT(11) NULL DEFAULT NULL');
	  $this->dbforge->modify_column('polygon', 'simbol VARCHAR(50) NULL DEFAULT NULL');
	  $this->dbforge->modify_column('garis', "path TEXT NULL");
	  $this->dbforge->modify_column('garis', "foto VARCHAR(100) NULL DEFAULT NULL");
	  $this->dbforge->modify_column('garis', "desk TEXT NULL");
	  $this->dbforge->modify_column('garis', "id_cluster INT(11) NULL DEFAULT NULL");

	  // Pindahkan submenu Informasi Publik ke menu Sekretariat
		$this->db->where('id', '52')->update('setting_modul', array('parent' => 15, 'urut' => 4));
		// Pindahkan kolom untuk kategori informasi publik
  	if (!$this->db->field_exists('kategori_info_publik','dokumen'))
		{
			$fields = array(
        'kategori_info_publik' => array(
          'type' => 'TINYINT',
          'constraint' => '4',
          'null' => TRUE,
          'default' => NULL
        )
			);
			$this->dbforge->add_column('dokumen',$fields);
			// Pindahkan isi kolom sebelumnya
			$dokumen = $this->db->select('id, attr')->get('dokumen')->result_array();
			foreach ($dokumen as $dok)
			{
				$attr = json_decode($dok['attr'], true);
				$kat = $attr['kategori_publik'];
				unset($attr['kategori_publik']);
				$this->db->where('id', $dok['id'])
					->update('dokumen', array('kategori_info_publik' => $kat, 'attr' => json_encode($attr)));
			}
		}
		// Isi kategori_info_publik untuk semua dokumen SK Kades dan Perdes sebagai 'Informasi Setiap Saat'
		$this->db->where('kategori_info_publik IS NULL')
			->where("kategori IN (2,3)")
			->update('dokumen', array('kategori_info_publik' => '3'));
	  // Perbesar nilai klasifikasi melebihi 999.99
	  $this->dbforge->modify_column('analisis_klasifikasi', 'minval double(7,2) NOT NULL');
	  $this->dbforge->modify_column('analisis_klasifikasi', 'maxval double(7,2) NOT NULL');
	  // Catat perubahan pada dokumen dan terapkan soft-delete
  	if (!$this->db->field_exists('updated_at','dokumen'))
		{
		  $this->dbforge->add_column('dokumen', 'updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
			$fields = array(
         'deleted' => array(
        	'type' => 'TINYINT',
        	'constraint' => 1,
        	'null' => FALSE,
        	'default' => 0
        )
			);
			$this->dbforge->add_column('dokumen', $fields);
		}
		if (!$this->db->table_exists('dokumen_hidup'))
			$this->db->query("CREATE VIEW dokumen_hidup AS SELECT * FROM dokumen WHERE deleted <> 1");
		// Sesuaikan tabel config dengan sql_mode STRICT_TRANS_TABLES
	  $this->dbforge->modify_column('config', 'logo varchar(100) NULL DEFAULT NULL');
	  $this->dbforge->modify_column('config', 'lat varchar(20) NULL DEFAULT NULL');
	  $this->dbforge->modify_column('config', 'lng varchar(20) NULL DEFAULT NULL');
	  $this->dbforge->modify_column('config', 'zoom tinyint(4) NULL DEFAULT NULL');
	  $this->dbforge->modify_column('config', 'map_tipe varchar(20) NULL DEFAULT NULL');
	  $this->dbforge->modify_column('config', 'path text NULL');
  	if ($this->db->field_exists('g_analytic','config'))
		{
		  $this->dbforge->drop_column('config', 'g_analytic');
		}
		// Sesuaikan impor analisis dengan sql_mode STRICT_TRANS_TABLES
	  $this->dbforge->modify_column('analisis_master', 'id_kelompok int(11) NULL DEFAULT NULL');
	  $this->dbforge->modify_column('analisis_master', 'id_child smallint(4) NULL DEFAULT NULL');
	  $this->dbforge->modify_column('analisis_master', 'format_impor tinyint(2) NULL DEFAULT NULL');
	  $this->dbforge->modify_column('analisis_kategori_indikator', 'kategori_kode varchar(3) NULL DEFAULT NULL');
	  $this->dbforge->modify_column('analisis_kategori_indikator', 'id int(11) NOT NULL AUTO_INCREMENT');
	  $this->dbforge->modify_column('analisis_indikator', 'id_kategori int(4) NOT NULL');

    // Table ref_surat_format tempat nama dokumen sbg syarat Permohonan surat
    $this->dbforge->add_field(array(
			'ref_surat_id' => array(
				'type' => 'INT',
				'constraint' => 1,
				'unsigned' => TRUE,
				'null' => FALSE,
				'auto_increment' => TRUE
			),
			'ref_surat_nama' => array(
				'type' => 'VARCHAR',
				'constraint' => 255,
				'null' => FALSE,

			),
		));
		$this->dbforge->add_key("ref_surat_id",true);
		$this->dbforge->create_table("ref_surat_format", TRUE);

    // Menambahkan Data Table ref_surat_format
    $query = "
    INSERT INTO `ref_surat_format` (`ref_surat_id`, `ref_surat_nama`) VALUES
    (1, 'Surat Pengantar RT/RW'),
    (2, 'Fotokopi KK'),
    (3, 'Fotokopi KTP'),
    (4, 'Fotokopi Surat Nikah/Akta Nikah/Kutipan Akta Perkawinan'),
    (5, 'Fotokopi Akta Kelahiran/Surat Kelahiran bagi keluarga yang mempunyai anak'),
    (6, 'Surat Pindah Datang dari tempat asal'),
    (7, 'Surat Keterangan Kematian dari Rumah Sakit, Rumah Bersalin Puskesmas, atau visum Dokter'),
    (8, 'Surat Keterangan Cerai'),
    (9, 'Fotokopi Ijasah Terakhir'),
    (10, 'SK. PNS/KARIP/SK. TNI – POLRI'),
    (11, 'Surat Keterangan Kematian dari Kepala Desa/Kelurahan'),
    (12, 'Surat imigrasi / STMD (Surat Tanda Melapor Diri)');
    ";

    $this->db->query($query);

    // Table surat_format_ref sbg link antara surat yg dimohon dan dokumen yg diperlukan
    $this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'null' => FALSE,
				'auto_increment' => TRUE
			),
			'surat_format_id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'null' => FALSE,

			),
			'ref_surat_id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'null' => FALSE,

			),
		));
		$this->dbforge->add_key("id",true);
		$this->dbforge->create_table("surat_format_ref", TRUE);

    // Menambahkan Data Table surat_format_ref (contoh saja)
    $query = "
    INSERT INTO `surat_format_ref` (`id`, `surat_format_id`, `ref_surat_id`) VALUES
    (1, 1, 1),
    (2, 1, 2),
    (3, 1, 3),
    (4, 1, 4),
    (5, 2, 1),
    (6, 2, 2),
    (7, 2, 3),
    (8, 3, 1),
    (9, 3, 2),
    (10, 3, 4),
    (11, 3, 5),
    (12, 3, 6),
    (13, 5, 1),
    (14, 5, 2),
    (15, 5, 3),
    (16, 5, 4),
    (17, 5, 6),
    (18, 5, 7),
    (19, 5, 8);
    ";

    $this->db->query($query);

    // Menambahkan menu 'Group / Hak Akses' ke table 'setting_modul'
    $data = array();
    $data[] = array(
      'id'=>'97',
      'modul'=>'List Dokumen Permohonan',
      'url'=>'surat_mohon',
      'aktif'=>'1',
      'ikon'=>'fa fa-book',
      'urut'=>'5',
      'level'=>'2',
      'hidden'=>'0',
      'ikon_kecil'=>'',
      'parent'=>4);

      foreach ($data as $modul)
      {
        $sql = $this->db->insert_string('setting_modul', $modul);
        $sql .= " ON DUPLICATE KEY UPDATE
        id = VALUES(id),
        modul = VALUES(modul),
        url = VALUES(url),
        aktif = VALUES(aktif),
        ikon = VALUES(ikon),
        urut = VALUES(urut),
        level = VALUES(level),
        hidden = VALUES(hidden),
        ikon_kecil = VALUES(ikon_kecil),
        parent = VALUES(parent)";
        $this->db->query($sql);
      }

	}
}
