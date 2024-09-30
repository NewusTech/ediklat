<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body px-5 pt-2 pb-2">
                <div class="row mb-3">
                    <div class="d-flex justify-content-between mt-2 py-auto">
                        <a href="<?php echo base_url('ediklat/broadcast') ?>"><i title="back" role="button" class="ri-arrow-left-circle-line ri-lg my-auto text-danger"></i></a>
                        <p class="pl-4 my-auto fw-bolder"> <?php echo $title ?></p>
                    </div>
                </div>
                <?php echo form_open(base_url('ediklat/broadcast/add'), ["id" => "form"]); ?>
                <div class="col-12 col-md-12 col-sm-12">
                    <?php echo inputWithFormGroup('Text', 'text', 'text', 'Masukan pengumuman', [], []); ?>
                    <script>
                        <?php
                        if (form_error('text') != '') {
                            echo "handleToast('error','" . form_error('text') . "')";
                        }
                        ?>
                    </script>
                </div>
                <div class="col-12 col-md-12 col-sm-12">
                    <?php echo selectWithFormGroup('activityCode', 'Kegiatan', 'activityCode', $activity, '', [], [], 'Pilih kegiatan'); ?>
                    <script>
                        <?php
                        if (form_error('activityCode') != '') {
                            echo "handleToast('error','" . form_error('activityCode') . "')";
                        }
                        ?>
                    </script>
                </div>
                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>