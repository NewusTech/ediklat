<div class="data">
</div>
<div class="forModal"></div>
<script>
    var base_url = '<?php echo base_url() ?>';
    var save_label = "add";

    $(document).ready(function() {
        getData();
    });

    function back() {
        getData();
    }

    function getData() {
        $.ajax({
            url: base_url + 'ediklat/ajax/sertifikat/data_member',
            type: "GET",

            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
                    breadcrumb(data.breadcrumb);
                    let list = $("#sertifikat").DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: base_url + 'ediklat/ajax/sertifikat/list_member',
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
                    let essay = $("#essay").DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: base_url + 'ediklat/ajax/sertifikat/member_listEssay',
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