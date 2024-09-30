<div class="row">
    <div class="col-md-12 col-sm-12">
        <?php echo selectWithFormGroup('certificateCode', 'Sertifikat', 'certificateCode', $certificate, $certificateCode) ?>
    </div>
    <div class="col-md-12 col-sm-12 text-end">
        <button type="button" class="btn btn-sm btn-primary" onclick="updateCertificate(<?php echo $essayCode ?>)">Update</button>
    </div>
    <div class="col-sm-12 col-md-12 mt-2" id="viewPDF">

    </div>
</div>

<script>
    $(document).ready(function() {
        <?php if ($certificateCode != '') : ?>
            getPDF();
        <?php endif; ?>
    });

    function getPDF() {
        $.ajax({
            url: base_url + 'ediklat/ajax/sertifikat/getPDF',
            type: "POST",
            success: function(data) {
                if (data.status) {
                    var html = `
                    <embed src="` + data.url + `" style="width: 100%;height:300px">
                    `;
                    $("#viewPDF").html(html);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function updateCertificate(id) {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/updateCertificate/' + id,
            type: "POST",
            data: {
                certificateCode: $("#certificateCode").val()
            },
            success: function(data) {
                if (data.status) {
                    handleToast("success", data.message);
                    getPDF();
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