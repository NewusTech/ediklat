<div class="card mb-4">
    <div class="d-flex justify-content-start">
        <a href="<?php echo base_url($link)?>" class="btn btn-sm btn-primary">Download</a>
    </div>
    <div class="card-body px-5 pt-2 pb-2">
        <div class="table-responsive">
            <?php
            $header = ['Nama Peserta', 'Tempat/Tanggal Lahir', 'NIK', 'NPSN','Kegiatan','Aksi'];
            ?>
            <?php echo table('report', $header, ['table-hover py-1 px-0 mx-0']);
            ?>
        </div>
    </div>
</div>