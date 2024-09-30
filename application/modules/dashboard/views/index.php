<div class="card mb-4">
    <div class="card-body d-flex">
        <p class="text-lg text-bold text-primary align-center">Selamat Datang Kembali</p>
    </div>
</div>
<div class="row">
    <div class="col-xl-4 col-sm-12 col-12 col-md-12 mb-xl-0 mb-4">
        <div class="card" role="button" onclick="sedang()">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Kegiatan Sedang Diikuti</p>
                            <h5 class="font-weight-bolder mb-0">
                                <?php echo $sedang ?>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end d-flex justify-content-end align-content-center">
                        <div class="d-flex align-items-center justify-content-center icon icon-shape bg-primary shadow text-center border-radius-md">
                            <i class="fa fa-clock text-lg text-white opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-12 col-12 col-md-12 mb-xl-0 mb-4">
        <div class="card" role="button" onclick="telah()">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Kegiatan Telah Diikuti</p>
                            <h5 class="font-weight-bolder mb-0">
                                <?php echo $telah ?>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end d-flex justify-content-end align-content-center">
                        <div class="d-flex align-items-center justify-content-center icon icon-shape bg-primary shadow text-center border-radius-md">
                            <i class="fa fa-check text-lg text-white opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-12 col-12 col-md-12 mb-xl-0 mb-4">
        <div class="card" role="button" onclick="undang()">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Diundang</p>
                            <h5 class="font-weight-bolder mb-0">
                                <?php echo $undang ?>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 d-flex justify-content-end align-content-center">
                        <div class="d-flex align-items-center justify-content-center icon icon-shape bg-primary shadow text-center border-radius-md">
                            <i class="fa fa-certificate text-lg text-white opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>