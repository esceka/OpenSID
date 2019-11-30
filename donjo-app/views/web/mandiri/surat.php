<?php  if(!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<style type="text/css">
  table.table th {
    text-align: left;
  }
</style>

<form class='contact_form' id="validasi" action="<?= site_url()?>lapor_web/insert" method="POST" enctype="multipart/form-data">

  <div class="box-header with-border">
    <span style="font-size: x-large"><strong>LAYANAN PERMOHONAN SURAT</strong></span>
    <button type="button" class="btn btn-primary pull-right" value="Kirim" id="kirim"><i class="fa fa-sign-in"></i>Kirim</button>
  </div>
  <div class="artikel layanan">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="form" >
      <input class="form-group" type="hidden" name="owner" value="<?= $_SESSION['nama']?>"/>
      <input class="form-group" type="hidden" readonly="readonly" name="email" value="<?= $_SESSION['nik']?>"/>
      <tr>
        <td>Jenis Surat Yang Dimohon</td>
        <td>
          <select class="form-group" name="nama_surat" id="nama_surat">
            <option> -- Pilih Jenis Surat -- </option>
            <?php foreach ($menu_surat2 AS $data): ?>
              <option value="<?= $data['nama']?>"><?= $data['nama']?></option>
            <?php endforeach;?>
          </select>
        </td>
      </tr>
    </tr>
    <tr>
      <td>Keterangan Tambahan</td>
      <td>
        <textarea name="komentar" rows="1" cols="46" placeholder="Ketik di sini untuk memberikan keterangan tambahan."></textarea>
      </td>
    </tr>
    <tr>
      <td>Nomor HP Aktif</td>
      <td>
        <input class="form-group" type="text" name="hp" placeholder="ketik no. HP" size="14"/>
      </td>
    </tr>
  </table>

  <div class="box box-info" style="margin-top: 10px;">
    <div class="box-header with-border">
      <h4 class="box-title">DOKUMEN / KELENGKAPAN PENDUDUK YANG DIBUTUHKAN</h4>
      <div class="box-tools">
        <button type="button" class="btn btn-box-tool" data-toggle="collapse" data-target="#surat"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <div class="box-body" id="surat">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-striped">
        <thead>
          <tr>
            <th width="800">Nama Dokumen</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody id="tbody-dokumen">
        </tbody>
      </table>
    </div>
  </div>

  <div class="box box-info" style="margin-top: 10px;">
    <div class="box-header with-border">
      <h4 class="box-title">DOKUMEN / KELENGKAPAN PENDUDUK YANG TERSEDIA</h4>
      <div class="box-tools">
        <button type="button" class="btn btn-box-tool" data-toggle="collapse" data-target="#dokumen"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <div class="box-body" id="dokumen">
      <table class="table table-striped table-bordered" id="surat-table">
        <thead>
          <tr>
            <th width="2">No</th>
            <th width="220">Nama Dokumen</th>
            <th width="360">Berkas</th>
            <th width="200">Tanggal Upload</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($list_dokumen as $data): ?>
            <tr>
              <td align="center" width="2"><?= $data['no']?></td>
              <td><?= $data['nama']?></td>
              <td><a href="<?= base_url().LOKASI_DOKUMEN?><?= urlencode($data['satuan'])?>" ><?= $data['satuan']?></a></td>
              <td><?= tgl_indo2($data['tgl_upload'])?></td>
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="box box-info" style="margin-top: 10px;">
    <div class="box-header with-border">
      <h4 class="box-title">DOKUMEN / KELENGKAPAN PENDUDUK YANG PERLU DIUNGGAH</h4>
      <div class="box-tools">
        <button type="button" class="btn btn-box-tool" data-toggle="collapse" data-target="#unggah"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data">
      <div class="box-body" id="unggah">
        <div class="form-group">
          <label for="nama">Nama / Jenis Dokumen</label>
          <input id="nama" name="nama" class="form-control input-sm required" type="text" placeholder="Nama Dokumen" value="<?= $dokumen['nama']?>"></input>	<input type="hidden" name="id_pend" value="<?= $penduduk['id']?>"/>
        </div>
        <div class="form-group">
          <label for="file" >Pilih File:</label>
          <div class="input-group input-group-sm">
            <input type="text" class="form-control" id="file_path" name="satuan">
            <input type="file" class="hidden" id="file" name="satuan">
            <input type="hidden" name="old_file" value="<?= $dokumen['satuan']?>">
            <span class="input-group-btn">
              <button type="button" class="btn btn-info btn-flat"  id="file_browser"><i class="fa fa-search"></i> Browse</button>
            </span>
          </div>
          <p class="help-block">Kosongkan jika tidak ingin mengubah dokumen.</p>
        </div>
        <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan Dokumen</button>
      </div>
    </form>
  </div>

  </form>
  </div>

<script type='text/javascript'>
  $(document).ready(function(){
    $('#surat-table').DataTable({
    	"dom": 'rt<"bottom"p><"clear">',
    	"destroy": true,
      "paging": false,
      "ordering": false
    });

    $('#nama_surat').change(function(){
      var nama_surat = $(this).val();
      var url = "<?= site_url('first/ajax_table_surat_permohonan')?>";

      $.ajax({
        type: "POST",
        url: url,
        data: {
          nama_surat: nama_surat
        },
        dataType: "JSON",
        success: function(data)
        {
          var html;
          if (data.length == 0)
          {
            html = "<tr><td colspan='3' align='center'>No Data Available</td></tr>";
          }
          for (var i = 0; i < data.length; i++)
          {
            html += "<tr>"+"<td>"+data[i].ref_surat_nama+"</td>";
          }
          $('#tbody-dokumen').html(html);
        },
        error: function(err, jqxhr, errThrown)
        {
          console.log(err);
        }
      })
   });

 });
 </script>
 <script src="<?= base_url()?>assets/js/validasi.js"></script>
 <script src="<?= base_url()?>assets/js/jquery.validate.min.js"></script>
