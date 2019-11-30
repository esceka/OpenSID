<div class="content-wrapper">
	<section class="content-header">
		<h1>List Dokumen Permohonan Surat</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('surat_master')?>"> Format Surat Desa</a></li>
			<li class="active">List Dokumen Permohonan Surat</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?=site_url("surat_master")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
							<i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar Format Surat
           	</a>
						<a href="<?= site_url('surat_mohon')?>" title="Ubah List Dokumen" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-edit"></i> Ubah List Dokumen</a>
					</div>
					<div class="box-body">
							<div class="box-body">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<label class="col-sm-3 control-label" for="kode_surat">Kode/Klasifikasi Surat</label>
											<div class="col-sm-7">
												<select class="form-control input-sm select2-tags required" id="kode_surat" name="kode_surat">
													<option >
														<?php if (!empty($surat_master['kode_surat'])): ?>
															<?= $surat_master['kode_surat']?>
														<?php else: ?>
															-- Pilih Kode/Klasifikasi Surat --
														<?php endif; ?>
													</option>
													<?php foreach ($klasifikasi as $item): ?>
														<option value="<?= $item['kode'] ?>" <?php selected($item['kode'], $surat_master["kode_surat"])?>><?= $item['kode'].' - '.$item['nama']?></option>
													<?php endforeach;?>
												</select>
											</div>
										</div>
									</div>
									<div class="col-sm-12">
										<div class="form-group">
											<label class="col-sm-3 control-label" >Nama Layanan</label>
											<div class="col-sm-7">
												<div class="input-group">
													<span class="input-group-addon input-sm">Surat</span>
													<input type="text" class="form-control input-sm required" id="nama" name="nama" placeholder="Nama Layanan" value="<?= $surat_master['nama']?>"/>
												</div>
											</div>
										</div>
									</div>
									<?php if (strpos($form_action, 'insert') !== false): ?>
										<div class="col-sm-12">
											<div class="form-group">
												<label class="col-sm-3 control-label" for="nama">Pemohon Surat</label>
												<div class="col-sm-3">
													<select class="form-control input-sm" id="pemohon_surat" name="pemohon_surat">
														<option value="warga" selected>Warga</option>
														<option value="non_warga">Bukan Warga</option>
													</select>
												</div>
											</div>
										</div>
									<?php endif; ?>
								</div>
								<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data"  class="form-horizontal">
								<div class="box box-info" style="margin-top: 10px;">
						  		<div class="box-header with-border">
						  			<h4 class="box-title">Dokumen Yang Dibutuhkan untuk Layanan Permohonan Surat</h4>
						  			<div class="box-tools">
						  				<button type="button" class="btn btn-box-tool" data-toggle="collapse" data-target="#surat"><i class="fa fa-minus"></i></button>
						  			</div>
						  		</div>
						  		<div class="box-body" id="surat">
										<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-striped form">
											<tr>
												<th width="2">No</th>
												<th width="5"><center><input type="checkbox" id="checkall0[]" onclick="myFunction0()"/></center></th>
												<th>Nama Dokumen</th>
												<th> &nbsp;</th>
											</tr>
											<?php $no=1; foreach($privileges as $privilege){?>
												<?php
												$pID = $privilege['ref_surat_id'];
												$checked = null;
												$item = null;
												foreach($crtPrivilege as $pri)
												{
													if ($pID == $pri->ref_surat_id)
													{
														$checked= ' checked="checked"';
														break;
													}
												}
												?>
												<tr>
													<td align="center" width="2"><?php echo $no;?></td>
													<td><center><input type="checkbox" name="privlg[]" value="<?=$privilege['ref_surat_id']?>"<?php echo $checked;?>></center></td>
													<td><?php echo $privilege['ref_surat_nama']?></td>
													<td></td>
												</tr>
												<?php $no++;
											}?>
										</table>
						  		</div>
						  	</div>
							</div>
							<div class="box-footer">
								<div class="col-xs-12">
									<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm invisible"><i class="fa fa-times"></i> Batal</button>
									<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script type="text/javascript">

function myFunction0() {
	var checkBox = document.getElementById("checkall0[]");
	if (checkBox.checked == true){
		var items=document.getElementsByName('privlg[]');
		for(var i=0; i<items.length; i++){
			if(items[i].type=='checkbox')
			items[i].checked=true;
		}
	} else {
		var items=document.getElementsByName('privlg[]');
		for(var i=0; i<items.length; i++){
			if(items[i].type=='checkbox')
			items[i].checked=false;
		}
	}
}
</script>
