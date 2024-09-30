<div class="d-flex justify-content-between align-items-center">
    <p class="text-sm text-bold my-auto py-auto">Form Essay</p>
    <span class="badge bg-danger" role="button" onclick="backessay()"><i class="fa fa-arrow-left"></i> Kembali</span>
</div>
<?php echo form_open('', ["id" => "form"]); ?>
<div class="row">
    <div class="col-12 mt-2">
        <p class="text-xs text-secondary py-0 my-0">Judul</p>
        <?php echo input('text', 'judul', 'Judul', ['form-control-sm'], ['value' => $essay['judul']]) ?>
    </div>
    <div class="col-6 mt-2">
        <p class="text-xs text-secondary py-0 my-0">Tanggal Mulai</p>
        <?php echo input('date', 'waktuMulai', 'Tanggal Mulai', ['form-control-sm'], ['value' => $essay['waktuMulai']]) ?>
    </div>
    <div class="col-6 mt-2">
        <p class="text-xs text-secondary py-0 my-0">Tanggal Selesai</p>
        <?php echo input('date', 'waktuSelesai', 'Tanggal Selesai', ['form-control-sm'], ['value' => $essay['waktuSelesai']]) ?>
    </div>
    <div class="col-12 mt-2">
        <p class="text-xs text-secondary py-0 my-0">Deskripsi</p>
        <textarea name="deskripsi" class="form-control" cols="30"><?php echo $essay['deskripsi'] ?></textarea>
    </div>
</div>
<div class="d-flex justify-content-end mt-2">
    <span class="badge bg-success" id="btnSave" role="button" onclick="saveEssay(<?php echo $essay['essayCode'] ?>)">Simpan</span>
</div>
<?php echo form_close() ?>