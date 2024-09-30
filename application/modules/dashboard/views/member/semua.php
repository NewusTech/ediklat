<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row my-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="pl-4 my-auto fw-bolder">Semua Kegiatan</p>
                        <i class="ri-filter-3-line ri-xl text-secondary " role="button" title="Filter" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAllActivity" aria-expanded="false" aria-controls="collapseAllActivity"></i>
                    </div>
                    <div class="collapse" id="collapseAllActivity">
                        <div class="col-md-12 mt-2 card card-body p-2">
                            <div class="row">
                                <p class="text-sm text-bold">Filter</p>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <?php echo select('statussemua', [
                                        'open' => 'Buka',
                                        'close' => 'Tutup'
                                    ], '', ['form-select-sm'], [], 'Status') ?>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <?php echo select('mediasemua', [
                                        'daring' => 'Daring',
                                        'luring' => 'Luring',
                                        'blended' => 'Blended Learning'
                                    ], '', ['form-select-sm'], [], 'Pelaksanaan'); ?>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <?php echo select('categorysemua', [
                                        'E-Diklat' => 'E-Diklat',
                                        'Seminar' => 'Seminar',
                                        'Webinar' => 'Webinar'
                                    ], '', ['form-select-sm'], [], 'Kategori'); ?>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="button" onclick="resetFilterAllActivity()" class="mr-0 btn btn-light btn-sm">Reset</button>
                                <button type="button" onclick="cariFilterAllActivity()" class="btn btn-primary btn-sm">Cari</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3" id="allActivity">
                </div>
            </div>
        </div>
    </div>
</div>