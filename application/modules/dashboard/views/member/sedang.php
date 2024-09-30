<div class="row">
    <div class="col-12">
        <button class="btn btn-sm btn-outline-danger" type="button" onclick="semua()">Kembali</button>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="d-flex justify-content-between mt-2 align-items-center">
                        <p class="pl-4 my-auto fw-bolder">Sedang diikuti</p>
                        <i class="ri-filter-3-line ri-xl text-secondary " role="button" title="Filter" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOnActivity" aria-expanded="false" aria-controls="collapseOnActivity"></i>
                    </div>
                    <div class="collapse" id="collapseOnActivity">
                        <div class="col-md-12 mt-2 card card-body p-2">
                            <div class="row">
                                <p class="text-sm text-bold">Filter</p>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <?php echo select('statussedang', [
                                        'open' => 'Buka',
                                        'close' => 'Tutup'
                                    ], '', ['form-select-sm'], [], 'Status') ?>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <?php echo select('mediasedang', [
                                        'daring' => 'Daring',
                                        'luring' => 'Luring',
                                        'blended' => 'Blended Learning'
                                    ], '', ['form-select-sm'], [], 'Pelaksanaan'); ?>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <?php echo select('categorysedang', [
                                        'E-Diklat' => 'E-Diklat',
                                        'Seminar' => 'Seminar',
                                        'Webinar' => 'Webinar'
                                    ], '', ['form-select-sm'], [], 'Kategori'); ?>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="button" onclick="resetFilterOnActivity()" class="mr-0 btn btn-light btn-sm">Reset</button>
                                <button type="button" onclick="cariFilterOnActivity()" class="btn btn-primary btn-sm">Cari</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3" id="onActivity">
                </div>
            </div>
        </div>
    </div>
</div>