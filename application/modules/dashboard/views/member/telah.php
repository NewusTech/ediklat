<div class="row">
    <div class="col-12">
        <button class="btn btn-sm btn-outline-danger" type="button" onclick="semua()">Kembali</button>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="d-flex justify-content-between mt-2 align-items-center">
                        <p class="pl-4 my-auto fw-bolder">Telah diikuti</p>
                        <i class="ri-filter-3-line ri-xl text-secondary " role="button" title="Filter" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAfterActivity" aria-expanded="false" aria-controls="collapseAfterActivity"></i>
                    </div>
                    <div class="collapse" id="collapseAfterActivity">
                        <div class="col-md-12 mt-2 card card-body p-2">
                            <div class="row">
                                <p class="text-sm text-bold">Filter</p>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <?php echo select('statustelah', [
                                        'open' => 'Buka',
                                        'close' => 'Tutup'
                                    ], '', ['form-select-sm'], [], 'Status') ?>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <?php echo select('mediatelah', [
                                        'daring' => 'Daring',
                                        'luring' => 'Luring',
                                        'blended' => 'Blended Learning'
                                    ], '', ['form-select-sm'], [], 'Pelaksanaan'); ?>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <?php echo select('categorytelah', [
                                        'E-Diklat' => 'E-Diklat',
                                        'Seminar' => 'Seminar',
                                        'Webinar' => 'Webinar'
                                    ], '', ['form-select-sm'], [], 'Kategori'); ?>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="button" onclick="resetFilterAfterActivity()" class="mr-0 btn btn-light btn-sm">Reset</button>
                                <button type="button" onclick="cariFilterAfterActivity()" class="btn btn-primary btn-sm">Cari</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3" id="afterActivity">
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function absen(activityCode) {
        Swal.fire({
            title: "Mencatat kehadiran",
            text: "Apakah anda ingin absen?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#084594",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya,Absen",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: base_url + 'dashboard/index/absen/' + activityCode,
                    type: "POST",
                    success: function(data) {
                        if (data.status) {
                            handleToast("success", data.message);
                            $("#onActivity").html('');

                            getData('sedang');
                        } else {
                            handleError(data);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert("Error get data from ajax");
                    },
                });
            }
        });
    }
</script>