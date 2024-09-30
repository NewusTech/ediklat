<div class="d-flex flex-column flex-sm-row flex-md-row flex-lg-row justify-content-between">
    <button type="button" onclick="verifyAll(<?php echo $essay['essayCode']; ?>)" class="btn btn-sm btn-primary">
        Luluskan Semua
        <i class="fa fa-check" role="button"></i>
    </button>
    <button type="button" onclick="inviteMember(<?php echo $essay['essayCode']; ?>)" class="btn btn-sm btn-primary">
        Undang Member
        <i class="fa fa-plus" role="button"></i>
    </button>
</div>
<div class="table-responsive">
    <?php $header = ['Nama/Instansi', 'Status', 'Aksi']; ?>
    <?php echo table('peserta', $header, ['table-hover py-1 px-0 mx-0']); ?>
</div>
<div class="forModal"></div>

<script>
    function verifyAll(essayCode) {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/verifyAll/' + essayCode,
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


    function inviteMember(memberCode) {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/inviteMemberEssay/' + memberCode,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $(".forModal").html(data.data);
                    $("#inviteMemberEssayModal").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $("#inviteMemberEssayModal").modal('show');
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