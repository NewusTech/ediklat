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
            url: base_url + 'ediklat/ajax/report/data',
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
                    breadcrumb(data.breadcrumb);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function dataList() {
        $.ajax({
            url: base_url + 'ediklat/ajax/report/dataHTML',
            type: "POST",
            data: {
                state: $('select[name=state] option').filter(':selected').val(),
                education_service: $('select[name=education_service] option').filter(':selected').val(),
                name: $('input[name=name]').val(),
                nik: $('input[name=nik]').val(),
                npsn: $('input[name=npsn]').val(),
            },
            success: function(data) {
                if (data.status) {
                    $(".dataList").html(data.data);
                    breadcrumb(data.breadcrumb);
                    let list = $("#report").DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: base_url + 'ediklat/ajax/report/list',
                            type: "POST",
                            data: function(d) {
                                d.state = $('select[name=state] option').filter(':selected').val();
                                d.education_service = $('select[name=education_service] option').filter(':selected').val();
                                d.name = $('input[name=name]').val();
                                d.nik = $('input[name=nik]').val();
                                d.npsn = $('input[name=npsn]').val();
                            }
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

    function cariData() {
        dataList();
    }

    function resetFilter() {
        $("select[name=state] option").val('').trigger('change')
        $("select[name=education_service] option").val('').trigger('change')
        $('input[name=name]').val();
        $('input[name=nik]').val();
        $('input[name=npsn]').val();
        dataList();
    }
    
    function detailData(memberCode) {
        $.ajax({
            url: base_url + 'ediklat/ajax/report/detailData',
            type: "POST",
            data: {
                memberCode: memberCode  
            },
            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
                    breadcrumb(data.breadcrumb);
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