<style>
    .rounded {
        border-radius: 0.625rem !important;
    }
</style>
<div class="accordion border-bottom border-start border-end rounded-bottom" id="accordionExample">
    <div class="accordion-item border-bottom">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button collapseIU" type="button" data-bs-toggle="collapse" data-bs-target="#collapseIU" aria-expanded="true" aria-controls="collapseIU">
                Informasi Umum
            </button>
        </h2>
        <div id="collapseIU" class="accordion-collapse collapse <?php echo ($active == 'collapseIU' ? 'show' : '') ?>" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <div class="card card-body bg-gray-200 p-2 shadow">
                    <div id="dataIU">
                    </div>
                    <div id="formIU" class="d-none">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item border-bottom">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePMP" aria-expanded="false" aria-controls="collapsePMP">
                Pengalaman Mengikuti Pelatihan
            </button>
        </h2>
        <div id="collapsePMP" class="accordion-collapse collapse <?php echo ($active == 'collapsePMP' ? 'show' : '') ?>" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <div class="accordion-body">
                    <div class="card card-body bg-gray-200 p-2 shadow">
                        <div id="dataPMP">
                        </div>
                        <div id="formPMP" class="d-none">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item border-bottom">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePBP" aria-expanded="false" aria-controls="collapsePBP">
                Pengalaman Berorganisasi Pendidikan
            </button>
        </h2>
        <div id="collapsePBP" class="accordion-collapse collapse <?php echo ($active == 'collapsePBP' ? 'show' : '') ?>" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <div class="accordion-body">
                    <div class="card card-body bg-gray-200 p-2 shadow">
                        <div id="dataPBP">
                        </div>
                        <div id="formPBP" class="d-none">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item border-bottom">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePMS" aria-expanded="false" aria-controls="collapsePMS">
                Pengalaman Menjadi Sukarelawan
            </button>
        </h2>
        <div id="collapsePMS" class="accordion-collapse collapse <?php echo ($active == 'collapsePMS' ? 'show' : '') ?>" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <div class="accordion-body">
                    <div class="card card-body bg-gray-200 p-2 shadow">
                        <div id="dataPMS">
                        </div>
                        <div id="formPMS" class="d-none">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item border-bottom">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePMO" aria-expanded="false" aria-controls="collapsePMO">
                Pengalaman Melatih/Mengembangkan Orang lain baik secara individu maupun kelompok
            </button>
        </h2>
        <div id="collapsePMO" class="accordion-collapse collapse <?php echo ($active == 'collapsePMO' ? 'show' : '') ?>" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <div class="accordion-body">
                    <div class="card card-body bg-gray-200 p-2 shadow">
                        <div id="dataPMO">
                        </div>
                        <div id="formPMO" class="d-none">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        getIU();
        getPMP();
        getPBP();
        getPMS();
        getPMO();
    });

    // informasi umum
    function getDataIU() {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/member_dataIUHTML',
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $("#dataIU").html(data.data);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function formDataIU() {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/member_formIUHTML',
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $("#formIU").html(data.data);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function getIU() {
        $("#formIU").addClass('d-none');
        $("#dataIU").removeClass('d-none');
        getDataIU();
    }

    function editIU() {
        $("#dataIU").addClass('d-none');
        $("#formIU").removeClass('d-none');
        formDataIU();
    }

    function saveIU() {
        $("#btnSave").text("saving...");
        $("#btnSave").attr("disabled", true);

        url = base_url + 'ediklat/ajax/lms/member_saveIU';

        $.ajax({
            url: url,
            type: "POST",
            data: $("#form").serialize(),
            dataType: "json",
            success: function(data) {
                if (data.status) {
                    getIU();
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

    // pengalaman mengikuti pelatihan
    var actionPMP = 'add'

    function getDataPMP() {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/member_dataPMPHTML',
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $("#dataPMP").html(data.data);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function formDataPMP(dppCode = '0') {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/member_formPMPHTML/' + dppCode,
            type: "POST",
            data: {
                actionPMP: actionPMP
            },
            success: function(data) {
                if (data.status) {
                    $("#formPMP").html(data.data);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function getPMP() {
        $("#formPMP").addClass('d-none');
        $("#dataPMP").removeClass('d-none');
        getDataPMP();
    }

    function addPMP() {
        $("#dataPMP").addClass('d-none');
        $("#formPMP").removeClass('d-none');
        actionPMP = 'add';
        formDataPMP();
    }

    function editPMP(dppCode) {
        $("#dataPMP").addClass('d-none');
        $("#formPMP").removeClass('d-none');
        actionPMP = 'edit';
        formDataPMP(dppCode);
    }

    function savePMP(dppCode = '0') {
        $("#btnSave").text("saving...");
        $("#btnSave").attr("disabled", true);
        var url, method;

        if (actionPMP == "add") {
            url = base_url + 'ediklat/ajax/lms/member_addPMP';
            method = "saved";
        } else {
            url = base_url + 'ediklat/ajax/lms/member_editPMP/' + dppCode;
            method = "updated";
        }

        $.ajax({
            url: url,
            type: "POST",
            data: $("#form").serialize(),
            dataType: "json",
            success: function(data) {
                if (data.status) {
                    getPMP();
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

    function deletePMP(dppCode) {
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
                    url: base_url + 'ediklat/ajax/lms/member_deletePMP/' + dppCode,
                    type: "POST",
                    success: function(data) {
                        if (data.status) {
                            getPMP();
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

    // pengalaman berorganisasi pendidikan
    var actionPBP = 'add'

    function getDataPBP() {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/member_dataPBPHTML',
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $("#dataPBP").html(data.data);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function formDataPBP(dpoCode = '0') {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/member_formPBPHTML/' + dpoCode,
            type: "POST",
            data: {
                actionPBP: actionPBP
            },
            success: function(data) {
                if (data.status) {
                    $("#formPBP").html(data.data);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function getPBP() {
        $("#formPBP").addClass('d-none');
        $("#dataPBP").removeClass('d-none');
        getDataPBP();
    }

    function addPBP() {
        $("#dataPBP").addClass('d-none');
        $("#formPBP").removeClass('d-none');
        actionPBP = 'add';
        formDataPBP();
    }

    function editPBP(dpoCode) {
        $("#dataPBP").addClass('d-none');
        $("#formPBP").removeClass('d-none');
        actionPBP = 'edit';
        formDataPBP(dpoCode);
    }

    function savePBP(dpoCode = '0') {
        $("#btnSave").text("saving...");
        $("#btnSave").attr("disabled", true);
        var url, method;

        if (actionPBP == "add") {
            url = base_url + 'ediklat/ajax/lms/member_addPBP';
            method = "saved";
        } else {
            url = base_url + 'ediklat/ajax/lms/member_editPBP/' + dpoCode;
            method = "updated";
        }

        $.ajax({
            url: url,
            type: "POST",
            data: $("#form").serialize(),
            dataType: "json",
            success: function(data) {
                if (data.status) {
                    getPBP();
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

    function deletePBP(dpoCode) {
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
                    url: base_url + 'ediklat/ajax/lms/member_deletePBP/' + dpoCode,
                    type: "POST",
                    success: function(data) {
                        if (data.status) {
                            getPBP();
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

    // pengalaman menjadi sukarelawan
    var actionPMS = 'add'

    function getDataPMS() {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/member_dataPMSHTML',
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $("#dataPMS").html(data.data);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function formDataPMS(dpsCode = '0') {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/member_formPMSHTML/' + dpsCode,
            type: "POST",
            data: {
                actionPMS: actionPMS
            },
            success: function(data) {
                if (data.status) {
                    $("#formPMS").html(data.data);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function getPMS() {
        $("#formPMS").addClass('d-none');
        $("#dataPMS").removeClass('d-none');
        getDataPMS();
    }

    function addPMS() {
        $("#dataPMS").addClass('d-none');
        $("#formPMS").removeClass('d-none');
        actionPMS = 'add';
        formDataPMS();
    }

    function editPMS(dpsCode) {
        $("#dataPMS").addClass('d-none');
        $("#formPMS").removeClass('d-none');
        actionPMS = 'edit';
        formDataPMS(dpsCode);
    }

    function savePMS(dpsCode = '0') {
        $("#btnSave").text("saving...");
        $("#btnSave").attr("disabled", true);
        var url, method;

        if (actionPMS == "add") {
            url = base_url + 'ediklat/ajax/lms/member_addPMS';
            method = "saved";
        } else {
            url = base_url + 'ediklat/ajax/lms/member_editPMS/' + dpsCode;
            method = "updated";
        }

        $.ajax({
            url: url,
            type: "POST",
            data: $("#form").serialize(),
            dataType: "json",
            success: function(data) {
                if (data.status) {
                    getPMS();
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

    function deletePMS(dpsCode) {
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
                    url: base_url + 'ediklat/ajax/lms/member_deletePMS/' + dpsCode,
                    type: "POST",
                    success: function(data) {
                        if (data.status) {
                            getPMS();
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


    // pengalaman melatih
    var actionPMO = 'add'

    function getDataPMO() {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/member_dataPMOHTML',
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $("#dataPMO").html(data.data);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function formDataPMO(dpmCode = '0') {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/member_formPMOHTML/' + dpmCode,
            type: "POST",
            data: {
                actionPMO: actionPMO
            },
            success: function(data) {
                if (data.status) {
                    $("#formPMO").html(data.data);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function getPMO() {
        $("#formPMO").addClass('d-none');
        $("#dataPMO").removeClass('d-none');
        getDataPMO();
    }

    function addPMO() {
        $("#dataPMO").addClass('d-none');
        $("#formPMO").removeClass('d-none');
        actionPMO = 'add';
        formDataPMO();
    }

    function editPMO(dpmCode) {
        $("#dataPMO").addClass('d-none');
        $("#formPMO").removeClass('d-none');
        actionPMO = 'edit';
        formDataPMO(dpmCode);
    }

    function savePMO(dpmCode = '0') {
        $("#btnSave").text("saving...");
        $("#btnSave").attr("disabled", true);
        var url, method;

        if (actionPMO == "add") {
            url = base_url + 'ediklat/ajax/lms/member_addPMO';
            method = "saved";
        } else {
            url = base_url + 'ediklat/ajax/lms/member_editPMO/' + dpmCode;
            method = "updated";
        }

        $.ajax({
            url: url,
            type: "POST",
            data: $("#form").serialize(),
            dataType: "json",
            success: function(data) {
                if (data.status) {
                    getPMO();
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

    function deletePMO(dpmCode) {
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
                    url: base_url + 'ediklat/ajax/lms/member_deletePMO/' + dpmCode,
                    type: "POST",
                    success: function(data) {
                        if (data.status) {
                            getPMO();
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
</script>