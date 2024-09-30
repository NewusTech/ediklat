<?php if ($data != NULL) : ?>
    <?php foreach ($data as $k => $v) : ?>
        <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
            <div class="card card-blog card-plain">
                <div class="position-relative">
                    <a class="d-block shadow-xl border-radius-xl">
                        <img src="<?php echo base_url('assets/img/activity/' . $v['image']) ?>" alt="img-blur-shadow" class="img-fluid shadow border-radius-xl">
                    </a>
                </div>
                <div class="card-body px-1 pb-0">
                    <p class="my-0 py-0 text-bold text-sm text-center">
                        <?php echo strtoupper($v['name']) ?>
                    </p>
                    <div class="m-1 mb-1">
                        <div class="d-flex justify-content-between align-items-center my-0 py-0">
                            <p class="text-gradient text-dark my-0 py-0 text-sm"><?php echo strtoupper($v['category']) . '/' . ($v['type'] == 'general' ? 'TERBUKA' : 'TERTUTUP') ?></p>
                            <p class="text-end text-xs fw-bold my-0 py-0 text-primary bg-white rounded lh-sm <?php echo ($v['status'] == 'open' ? 'text-success' : 'text-danger') ?>"><?php echo (($v['status'] == 'open') ? 'Buka' : 'Tutup') ?></p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="text-sm my-0 py-0 text-start text-bold"><?php echo character_limiter($v['organizer'], 15) ?></p>
                            <p class="text-xs my-0 py-0 text-end text-bold"><?php echo ucfirst(character_limiter($v['media'], 15)) ?></p>
                        </div>
                        <p class="text-xs my-0 py-0 text-start"><span class="text-bold">Waktu: </span><?php echo tanggal_indo($v['startDate']) ?></p>
                        <p class="text-xs my-0 py-0 text-start"><span class="text-bold">Kuota: </span><?php echo $v['kuota'] ?> Peserta</p>
                        <p class="text-xs my-0 py-0 text-start"><span class="text-bold">Pendaftar: </span><?php echo $v['jumlahPeserta'] ?> Peserta</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-center gap-1">
                        <?php if ($v['self'] != NULL) : ?>
                            <?php if ($v['status'] == 'open' && $v['self']['accept'] == '2') : ?>
                                <button type="button" class="btn btn-outline-danger btn-sm mb-0" onclick="tolak(<?php echo $v['self']['participantCode'] ?>)">Tolak</button>
                                <button type="button" class="btn btn-outline-success btn-sm mb-0" onclick="terima(<?php echo $v['self']['participantCode'] ?>)">Terima</button>
                            <?php endif; ?>
                            <?php if ($v['self']['accept'] == '1') : ?>
                                <div>
                                    <p class="text-sm my-0 py-0 text-bold text-success text-center">Undangan Telah Diterima</p>
                                    <p class="text-xs my-0 py-0 text-center">Silahkan Cek Di <span class="text-bold">Kegiatan Sedang Diikuti</span> </p>
                                </div>
                            <?php endif; ?>
                            <?php if ($v['self']['accept'] == '0') : ?>
                                <p class="text-sm my-auto py-auto text-bold text-danger">Undangan Telah Ditolak</p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php if ($data == NULL) : ?>
    <div class="col-12">
        <div class="card card-body p-2 m-2 shadow-lg bg-body d-flex justify-content-center align-items-center">
            <p class="text-md text-bold text-center">Tidak Ada Kegiatan</p>
        </div>
    </div>
<?php endif; ?>
<?php
$page = ceil($total / 3);
if ($page > $pageActive) :
?>
    <?php if ($total > 3) : ?>
        <div class="col-12 mb-3 selengkapnyaSedang-<?php echo $pageActive ?>">
            <div class="d-flex justify-content-center">
                <a href="javascript:void(0)" class="btn btn-sm btn-primary selengkapnya-<?php echo $pageActive ?>" onclick="getList('sedang', <?php echo ($pageActive + 1) ?>)">Selengkapnya</a>
            </div>
        </div>
        <script>
            $('.selengkapnya-<?php echo $pageActive ?>').click(() => {
                $('.selengkapnyaSedang-<?php echo $pageActive ?>').remove();
            });
        </script>
    <?php endif; ?>
<?php endif; ?>