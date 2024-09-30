<div class="card card-body border-0 shadow">
    <ul class="nav nav-tabs nav-justified">
        <li class="nav-item">
            <a class="nav-link cv" href="javascript:void(0)" onclick="cv()">CV</a>
        </li>
        <li class="nav-item">
            <a class="nav-link essay" href="javascript:void(0)" onclick="essay()">Essay</a>
        </li>
    </ul>
    <div class="data"></div>
</div>
<script>
    var activeTab = 'cv';
    var active = 'collapseIU';
    var base_url = '<?php echo base_url(); ?>';

    $(document).ready(function() {
        cv();
    });

    function backcv() {
        cv();
    }

    function backessay() {
        essay();
    }

    function cv() {
        activeTab = 'cv';
        $(".cv").addClass('active');
        $(".cv").addClass('text-primary');
        $(".essay").removeClass('active');
        $(".essay").removeClass('text-primary');
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/su_memberHTML',
            type: "POST",
            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
                    let list = $("#member").DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: base_url + 'ediklat/ajax/lms/su_listHTML',
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

    function essay() {
        activeTab = 'essay';
        $(".essay").addClass('active');
        $(".essay").addClass('text-primary');
        $(".cv").removeClass('active');
        $(".cv").removeClass('text-primary');
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/su_essayHTML',
            type: "POST",
            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
                    let list = $("#essay").DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: base_url + 'ediklat/ajax/lms/su_listEssayHTML',
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

    var actionEssay = 'add';

    function addEssay() {
        acctionEssay = 'add';
        formEssay();
    }

    function editEssay(essayCode) {
        actionEssay = 'edit';
        formEssay(essayCode);
    }

    function formEssay(essayCode = '0') {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/su_essayFormHTML/' + essayCode,
            type: "POST",
            data: {
                actionEssay: actionEssay
            },
            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function saveEssay(essayCode = '0') {
        $("#btnSave").text("saving...");
        $("#btnSave").attr("disabled", true);
        var url, method;

        if (actionEssay == "add") {
            url = base_url + 'ediklat/ajax/lms/su_addEssay';
            method = "saved";
        } else {
            url = base_url + 'ediklat/ajax/lms/su_editEssay/' + essayCode;
            method = "updated";
        }

        $.ajax({
            url: url,
            type: "POST",
            data: $("#form").serialize(),
            dataType: "json",
            success: function(data) {
                if (data.status) {
                    backessay();
                    handleToast("success", data.message);
                } else {
                    handleError(data);
                }
                $("#btnSave").text("save");
                $("#btnSave").attr("disabled", false);

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error adding / update data");
                $("#btnSave").text("save");
                $("#btnSave").attr("disabled", false);

            },
        });

        $("#form input, #form textarea").on("keyup", function() {
            $(this).removeClass("is-valid is-invalid");
        });
        $("#form select").on("change", function() {
            $(this).removeClass("is-valid is-invalid");
        });
    }

    function deleteEssay(essayCode) {
        Swal.fire({
            title: "Apakah kamu yakin?",
            text: "Anda tidak akan dapat mengembalikan ini!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#084594",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus ini!",
            cancelButtonText: "Kembali"
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: base_url + 'ediklat/ajax/lms/su_deleteEssay/' + essayCode,
                    type: "POST",
                    success: function(data) {
                        if (data.status) {
                            backessay();
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
        });
    }

    function detailEssay(essayCode) {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/su_essayDetailHTML/' + essayCode,
            type: "POST",
            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
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