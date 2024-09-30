<div class="card mb-4">
    <div class="card-body px-5 pt-2 pb-2">
        <div class="row">
            <div class="col-md-12">
                <p class="text-sm text-bold">Filter</p>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <?php echo input('text', 'name', 'Nama', ['form-control-sm']); ?>
                    </div>
                    <div class="col-md-4">
                        <?php echo input('text', 'nik', 'NIK', ['form-control-sm']); ?>
                    </div>
                    <div class="col-md-4">
                        <?php echo input('text', 'npsn', 'NPSN', ['form-control-sm']); ?>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-6">
                        <?php echo select('state', $state, '', ['form-control-sm'], ["type" => "text"], 'Semua Kabupaten/Kota') ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo select('education_service', [
                            "PAUD" => "PAUD",
                            "SD" => "SD",
                            "SMP" => "SMP",
                            "SMA" => "SMA",
                            "SLB" => "SLB",
                        ], '', ['form-control-sm'], ["type" => "text"], 'Semua Layanan Pendidikan') ?>
                    </div>
                </div>
            </div>
            <div class="text-end mt-1">
                <button type="button" onclick="resetFilter()" class="mr-0 btn btn-light btn-sm">Reset</button>
                <button type="button" onclick="cariData()" class="btn btn-primary btn-sm">Cari</button>
            </div>
        </div>

    </div>
</div>
<div class="dataList"></div>

<script>

    $(document).ready(function() {
        dataList();
    });
</script>