<main id="main">
    <section class="breadcrumbs">
        <div class="container">
            <ol>
                <li><a href="<?php echo base_url('#kegiatan') ?>">Kegiatan</a></li>
                <li><?php echo $activity['name'] ?></li>
            </ol>
            <h2><?php echo $activity['name'] ?></h2>
        </div>
    </section>
    <section id="blog" class="blog">
        <div class="container" data-aos="fade-up">
            <div class="row">
                <div class="col-lg-12 entries">
                    <article class="entry entry-single">
                        <div class="entry-img">
                            <img src="<?php echo base_url('assets/img/activity/' . $activity['image']) ?>" alt="" class="rounded" style="width: 100%;">
                        </div>
                        <h2 class="entry-title">
                            <a href="<?php echo base_url('kegiatan/detail/' . urlencode($activity['name'])) ?>"><?php echo $activity['name'] ?></a>
                        </h2>
                        <div class="entry-meta">
                            <ul>
                                <li class="d-flex align-items-center"><i class="bi bi-tags"></i> <?php echo $activity['category'] ?></li>
                                <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <?php echo tanggal_indo($activity['createAt']) ?></li>
                            </ul>
                        </div>

                        <div class="entry-content">
                            <div class="m-1 mb-1">
                                <p class="text-xs my-0 py-0 text-start">Penyelenggara: <?php echo $activity['organizer'] ?></p>
                                <p class="text-xs my-0 py-0 text-start">Waktu: <?php echo tanggal_indo($activity['startDate']) . ' Sampai ' . tanggal_indo($activity['endDate']) ?></p>
                                <p class="text-xs my-0 py-0 text-start">Jenis Pelaksanaan: <?php echo $activity['media'] ?></p>
                                <p class="text-xs my-0 py-0 text-start">Kuota: <?php echo ($activity['kuota'] == NULL ? '-' : $activity['kuota']) ?> Peserta</p>
                                <div class="d-flex justify-content-start">
                                    <p class="text-xs my-0 py-0 text-start">
                                        Pendaftaran:
                                    </p>
                                    <span class="text-xs <?php echo ($activity['status'] == 'open' ? 'text-success' : 'text-danger') ?>"><?php echo (($activity['status'] == 'open') ? 'Buka' : 'Tutup') ?>
                                    </span>
                                </div>
                                <div class="d-flex justify-content-start">
                                    <p class="text-xs my-0 py-0 text-start">
                                        Presensi:
                                    </p>
                                    <span class="text-xs <?php echo ($activity['attendance'] == 'open' ? 'text-success' : 'text-danger') ?>"><?php echo (($activity['attendance'] == 'open') ? 'Buka' : 'Tutup') ?>
                                    </span>
                                </div>

                                <p class="text-xs my-0 py-0 text-start"><span class="text-bold">Jumlah Perserta: </span><?php echo $activity['jumlahPeserta'] ?> Peserta</p>
                                <p class="text-xs my-0 py-0 text-start"><span class="text-bold">Sertifikat Terbit: </span><?php echo $activity['jumlahSertifikat'] ?> Sertifikat</p>
                            </div>
                            <?php if ($activity['status'] == 'open') : ?>
                                <!--<a type="button" class="btn btn-outline-success btn-sm mb-0" onclick="<?php echo base_url('dashboard/index') ?>">Daftar</a>-->
                            <?php endif; ?>
                        </div>
                        <h2 class="entry-title">
                            <p class="text-center">List Peserta</p>
                        </h2>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Lembaga Asal</th>
                                            <th>Kabupaten/Kota</th>
                                            <th>kehadiran</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        <?php foreach ($activity['peserta'] as $k => $v) : ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $v['name']; ?></td>
                                                <td><?php echo $v['agency']; ?></td>
                                                <td><?php echo getOneValue('state', [
                                                        'stateCode' => $v['stateCode']
                                                    ]); ?></td>
                                                <td><?php echo ($v['status'] == '1' ? 'Hadir' : 'Tidak Hadir'); ?></td>
                                                <td><?php echo ($v['verify'] == '1' ? 'Lulus' : ($v['verify'] == '2' ? 'Dicek' : 'Tidak Lulus')); ?></td>
                                            </tr>
                                            <?php $no++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>
</main>