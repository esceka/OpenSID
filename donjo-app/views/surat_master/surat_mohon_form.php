
<div class="content-wrapper">
	<section class="content-header">
		<h1>Pengaturan Dokumen Permohonan Surat</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid')?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('surat_mohon')?>"> Dokumen Permohonan Surat</a></li>
			<li class="active">Pengaturan Dokumen Permohonan Surat</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?=site_url("surat_mohon")?>" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
							<i class="fa fa-arrow-circle-left "></i>Kembali ke Dokumen Permohonan Surat
           	</a>
					</div>
					<div class="box-body">
						<form id="validasi" action="<?= $form_action?>" method="POST" enctype="multipart/form-data"  class="form-horizontal">
							<div class="box-body">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<label class="col-sm-3 control-label" >Nama Dokumen</label>
											<div class="col-sm-8">
													<input type="text" class="form-control input-sm required" id="ref_surat_nama" name="ref_surat_nama" placeholder="Nama Dokumen" value="<?= $ref_surat_format['ref_surat_nama']?>"/>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="box-footer">
								<div class="col-xs-12">
									<button type="reset" class="btn btn-social btn-flat btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
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
