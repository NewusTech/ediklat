<?php if ($answer == NULL) : ?>
    <p class="text-bold text-sm text-center">Peserta belum mengisi jawaban</p>
<?php endif; ?>
<?php if ($answer != NULL) : ?>
    <?php foreach ($answer as $k => $v) : ?>
        <div class="row">
            <div class="col-12">
                <div class="d-flex gap-2 align-items-center justify-content-start">
                    <a href="javascript:void(0)" onclick="hapusJawaban(this,<?php echo $v['ptCode'] ?>)" class="text-danger" title="Hapus" style="font-family: 'Nunito', sans-serif;"><i class="fa fa-trash"></i></a>
                    <p class="text-sm text-bold my-0 py-0 d-flex align-items-center gap-2"><?php echo $v['answer'] ?>
                        <?php if ($v['file'] != NULL) : ?>
                            <i class="ri-eye-line ri-xl text-secondary " role="button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFileJawaban-<?php echo $v['ptCode'] ?>" aria-expanded="false" aria-controls="collapseFileJawaban-<?php echo $v['ptCode'] ?>"></i>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="row">
                    <div class="col-12">
                        <?php if ($v['file'] != NULL) : ?>
                            <div class="collapse" id="collapseFileJawaban-<?php echo $v['ptCode'] ?>">
                                <div class="col-md-12 mt-2 card card-body p-2">
                                    <embed type="<?php echo $v['type'] ?>" style="width:100%;height:500px;" src="<?php echo base_url('assets/img/answer/' . $v['file']) ?>">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>