<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <button class="btn btn-sm btn-outline-danger" type="button" onclick="back()">Kembali</button>
        </div>
    </div>
    <div role="button" onclick="viewImage(<?php echo $activity['activityCode'] ?>)" class="page-header border-radius-xl mt-1">
        <img src="<?php echo base_url('assets/img/activity/' . $activity['image']) ?>" alt="" style="width: 100%;">
    </div>
    <?php if ($participant != NULL) : ?>
        <div class="card card-body shadow mx-4 mt-n6 overflow-hidden">
            <div class="row my-1">
                <div class="d-flex flex-column flex-md-row justify-content-center justify-content-md-between align-items-center">
                    <div class="d-flex flex-column flex-md-row align-items-center gap-1">
                        <div class="col-auto">
                            <img src="<?php echo base_url('assets/img/participant/' . $participant['picture']) ?>" alt="profile_image" style="max-width: 75px;" class="w-100 border-radius-lg shadow-sm">
                        </div>
                        <div class="col-auto">
                            <div class="h-100">
                                <h5 class="mb-0 py-0 text-center text-md-start">
                                    <?php echo $participant['name'] ?>
                                </h5>
                                <p class="my-0 py-0 font-weight-bold text-sm text-center text-md-start">
                                    <?php echo $participant['agency'] ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <p class="nav-link mb-0 px-0 py-1 active text-center text-md-end">
                        <i class="fa <?php echo ($participant['verify'] == '1' ? 'fa-check text-success' : ($participant['verify'] == '2' ? 'fa fa-clock text-warning' : 'fa-close text-danger')) ?>"></i>
                        <span class="ms-1"><?php echo ($participant['verify'] == '1' ? 'Lulus' : ($participant['verify'] == '2' ? 'Dicek' : 'Tidak Lulus')) ?></span>
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="row mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-bold text-center"><?php echo $activity['name'] ?></h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12 col-sm-12 col-md-4 col-lg-4 mb-2">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="text-sm my-0 py-0 text-start text-bold"><?php echo character_limiter($activity['organizer'], 15) ?></p>
                        <p class="text-xs my-0 py-0 text-end text-bold"><?php echo ucfirst(character_limiter($activity['media'], 15)) ?></p>
                    </div>
                    <p class="text-xs my-0 py-0 text-start"><span class="text-bold">Kuota: </span><?php echo $activity['kuota'] ?> Peserta</p>
                    <p class="text-xs my-0 py-0 text-start"><span class="text-bold">Jumlah Perserta: </span><?php echo $activity['jumlahPeserta'] ?> Peserta</p>
                    <p class="text-xs my-0 py-0 text-start"><span class="text-bold">Sertifikat Terbit: </span><?php echo $activity['jumlahSertifikat'] ?> Sertifikat</p>
                    <?php if ($participant != NULL) : ?>
                        <p class="text-xs my-0 py-0 text-start"><span class="text-bold">Deskripsi: </span></p>
                        <textarea class="text-xs form-control" rows="6" disabled><?php echo $activity['description'] ?></textarea>
                    <?php endif; ?>
                </div>
            </div>
            <?php if (checkRole(3) && $participant != NULL):?>
                <div class="card mt-2">
                    <div class="card-body">
                        <p class="text-center text-sm text-bold">Riwayat Hadir</p>
                        <ul>
                            <?php foreach(json_decode($participant['statusDetail'],TRUE) as $k => $v):?>
                                <?php if($k <= date('Y-m-d')):?>
                                    <li class="text-sm"><?php echo tanggal_indo($k) ?> - <?php echo ($v == '1' ? 'Hadir' : 'Tidak Hadir')?></li>
                                <?php endif;?>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-12 col-sm-12 col-md-8 col-lg-8 mb-2">
            <div class="card">
                <div class="card-body">
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
                    <div class="card border border-top-0" style="border-top-left-radius: 0px; border-top-right-radius: 0px;">
                        <div class="card-body">
                            <div class="dataDetail">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="forModal"></div>
<script>
    var base_url = '<?php echo base_url() ?>';

    $(document).ready(function() {
        peserta();
    });

    $('.forActive').click((e) => {
        $('.forActive').removeClass('active');
        $(e.target).addClass('active');
    });

    function peserta() {
        $.ajax({
            url: base_url + 'dashboard/index/peserta',
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $(".dataDetail").html(data.data);
                    getDataTab("peserta");
                } else {
                    handleError(data);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function materi() {
        $.ajax({
            url: base_url + 'dashboard/index/materi',
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $(".dataDetail").html(data.data);
                    getDataTab("materi");
                } else {
                    handleError(data);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function tugas() {
        $.ajax({
            url: base_url + 'dashboard/index/tugas',
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $(".dataDetail").html(data.data);
                    getDataTab("tugas");
                } else {
                    handleError(data);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function getDataTab(tab) {
        let list = $('#' + tab).DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: base_url + 'dashboard/index/' + tab + 'List/' + <?php echo $activity['activityCode'] ?>,
                type: "POST",
            },
            columnDefs: [{
                targets: [-1],
                orderable: false,
            }, ],
            language: {
                paginate: {
                    previous: "<",
                    next: ">",
                },
            },
        });
    }

    function uploadModal(taskCode) {
        $.ajax({
            url: base_url + 'dashboard/index/uploadModal/' + taskCode,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $(".forModal").html(data.data);
                    $("#uploadModal").modal('show');
                } else {
                    handleError(data);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function closeModal() {
        $("#uploadModal").modal('hide');
        tugas();
    }

    function viewImageParticipant(id) {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/imageParticipantHTML/' + id,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $(".forModal").html(data.data);
                    $("#imageModal").modal('show');
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }
</script>