<div class="d-flex flex-column flex-sm-row flex-md-row flex-lg-row justify-content-between my-3">
    <button type="button" onclick="verifyAll(<?php echo $activityCode; ?>)" class="btn btn-sm btn-primary">
        Luluskan Semua
        <i class="fa fa-check" role="button"></i>
    </button>
    <?php if ($activity->type == 'special') : ?>
        <button type="button" onclick="inviteParticipant(<?php echo $activityCode; ?>)" class="btn btn-sm btn-primary">
            Undang Peserta
            <i class="fa fa-plus" role="button"></i>
        </button>
    <?php endif; ?>
    <a href="<?php echo base_url('ediklat/kegiatan/download_absen/' . $activityCode) ?>" class="btn btn-sm btn-primary">
        Download Presensi
        <i class="fa fa-download" role="button"></i>
    </a>
</div>
<div class="table-responsive">
    <?php
    if ($activity->type == 'special') :
        $header = ['Nama/Instansi', 'Kehadiran', 'Lulus', 'Tugas', 'Status', 'Aksi'];
    endif;
    if ($activity->type == 'general') :
        $header = ['Nama/Instansi', 'Kehadiran', 'Lulus', 'Tugas', 'Aksi'];
    endif;
    ?>
    <?php echo table('peserta', $header, ['table-hover py-1 px-0 mx-0']); ?>
</div>
<div class="forModal"></div>

<script>
    function verifyAll(activityCode) {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/verifyAll/' + activityCode,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    peserta();
                    handleToast("success", data.message);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
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

    <?php if ($activity->type == 'special') : ?>

        function inviteParticipant(activityCode) {
            $.ajax({
                url: base_url + 'ediklat/ajax/kegiatan/inviteParticipant/' + activityCode,
                type: "GET",
                success: function(data) {
                    if (data.status) {
                        $(".forModal").html(data.data);
                        $("#inviteParticipantModal").modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                        $("#inviteParticipantModal").modal('show');
                    } else {
                        handleError(data);
                    }
                },

                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error get data from ajax");
                },
            });
        }
    <?php endif; ?>

    function detailTugasPeserta(activityCode, participantCode) {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/taskModal/' + activityCode + '/' + participantCode,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $(".forModal").html(data.data);
                    $("#taskModal").modal('show');
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
        $("#taskModal").modal('hide');
        peserta();
    }
</script>