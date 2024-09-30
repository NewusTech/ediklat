<div class="row">
    <div class="col-12">
        <a href="javascript:void(0)" onclick="back()" class="btn btn-sm btn-danger" title="Kembali" style="font-family: 'Nunito', sans-serif;"><i class="fa fa-arrow-left"></i> Kembali</a>
    </div>
</div>
<div class="row">
    <div class="col-12 col-sm-12 col-md-5 col-lg-5">
        <div class="card card-body p-0 mb-2 shadow-lg bg-body rounded d-flex flex-column">
            <div class="d-flex flex-column rounded-top" style="background: rgb(2,0,36);background: linear-gradient(0deg, rgba(2,0,36,1) 0%, rgba(8,69,148,1) 0%, rgba(0,109,194,1) 100%); height:70px;">
                <div class="d-flex align-items-center justify-content-between">
                    <p class="text-start text-xxs fw-bold m-1 p-1 text-primary bg-white rounded lh-sm"><?php echo strtoupper($activity['category']) ?></p>
                    <p class="text-start text-xxs fw-bold m-1 p-1 text-primary bg-white rounded lh-sm <?php echo ($activity['status'] == 'open' ? 'text-success' : 'text-danger') ?>"><?php echo (($activity['status'] == 'open') ? 'Buka' : 'Tutup') ?></p>
                </div>
                <div class="d-flex align-items-center justify-content-center">
                    <p class="text-center text-xxs fw-bold my-0 py-0 text-white lh-sm"><?php echo strtoupper($activity['name']) ?></p>
                </div>
            </div>
            <img src="<?php echo base_url('assets/img/activity/' . $activity['image']) ?>" class="img-fluid rounded-bottom shadow bg-body" style="width:100%; object-fit:cover;" />
            <div class="m-1 mb-1">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-sm my-0 py-0 text-start text-bold"><?php echo character_limiter($activity['organizer'], 15) ?></p>
                    <p class="text-xs my-0 py-0 text-end text-bold"><?php echo ucfirst(character_limiter($activity['media'], 15)) ?></p>
                </div>
                <p class="text-xs my-0 py-0 text-start"><span class="text-bold">Kuota: </span><?php echo $activity['kuota'] ?> Peserta</p>
                <p class="text-xs my-0 py-0 text-start"><span class="text-bold">Jumlah Perserta: </span><?php echo $activity['jumlahPeserta'] ?> Peserta</p>
                <p class="text-xs my-0 py-0 text-start"><span class="text-bold">Sertifikat Terbit: </span><?php echo $activity['jumlahSertifikat'] ?> Sertifikat</p>
                <p class="text-xs my-0 py-0 text-start"><span class="text-bold">Deskripsi: </span></p>
                <textarea class="text-xs form-control" rows="6" disabled><?php echo $activity['description'] ?></textarea>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-7 col-lg-7">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link text-sm forActive active" aria-current="page" href="javascript:void(0)" onclick="peserta()">Peserta</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-sm forActive" aria-current="page" href="javascript:void(0)" onclick="materi()">Materi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-sm forActive" aria-current="page" href="javascript:void(0)" onclick="tugas()">Tugas</a>
            </li>
        </ul>
        <div class="card mb-4 border border-top-0" style="border-top-left-radius: 0px; border-top-right-radius: 0px;">
            <div class="card-body px-5 pt-2 pb-2">
                <div class="dataDetail">
                </div>
            </div>
        </div>
    </div>
</div>