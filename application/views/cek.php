<?php if ($status == FALSE) : ?>
    <p class="my-1 py-0 fs-5 fw-bold text-center text-danger"><?php echo $message ?></p>
<?php endif; ?>
<?php if ($status == TRUE) : ?>
    <?php if ($type == 'KEGIATAN') : ?>
        <p class="my-1 py-0 fs-5 fw-bold text-center text-success"><?php echo $message ?></p>
        <p class="my-0 py-0"><span class="fw-bold">Nama : </span><?php echo $participant['name'] ?></p>
        <p class="my-0 py-0"><span class="fw-bold">Lembaga Asal : </span><?php echo $participant['agency'] ?></p>
        <p class="my-0 py-0"><span class="fw-bold">Layanan Pendidikan : </span><?php echo $participant['education_service'] ?></p>
        <p class="my-0 py-0"><span class="fw-bold">Kegiatan : </span><?php echo $activity['name'] ?></p>
        <p class="my-0 py-0"><span class="fw-bold">Pelaksana : </span><?php echo $activity['organizer'] ?></p>
    <?php endif; ?>
    <?php if ($type == 'ESSAY') : ?>
        <p class="my-1 py-0 fs-5 fw-bold text-center text-success"><?php echo $message ?></p>
        <p class="my-0 py-0"><span class="fw-bold">Nama : </span><?php echo $peserta['name'] ?></p>
        <p class="my-0 py-0"><span class="fw-bold">Lembaga Asal : </span><?php echo $peserta['agency'] ?></p>
        <p class="my-0 py-0"><span class="fw-bold">Layanan Pendidikan : </span><?php echo $peserta['education_service'] ?></p>
        <p class="my-0 py-0"><span class="fw-bold">Essay : </span><?php echo $essay['judul'] ?></p>
    <?php endif; ?>
<?php endif; ?>