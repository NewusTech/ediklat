<div class="data">
</div>
<script>
    var base_url = '<?php echo base_url() ?>';
    var save_label = "add";

    $(document).ready(function() {
        getData();
    });

    function back() {
        getData();
        toTop()
    }

    function backDetail(id) {
        infoData(id);
    }

    function getData() {
        $.ajax({
            url: base_url + 'management_users/ajax/users/data',
            type: "GET",
            beforeSend: function() {
                 
            },
            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
                    breadcrumb(data.breadcrumb);
                    let list = $("#users").DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: base_url + 'management_users/ajax/users/list',
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
            complete: function() {
                 
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function addData() {
        save_label = "add";
        $.ajax({
            url: base_url + 'management_users/ajax/users/addHTML',
            type: "POST",

            beforeSend: function() {
                 
            },
            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
                    breadcrumb(data.breadcrumb);
                } else {
                    handleError(data);
                }
            },
            complete: function() {
                 
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function editData(id) {
        save_label = "update";
        $.ajax({
            url: base_url + 'management_users/ajax/users/editHTML/' + id,
            type: "POST",

            beforeSend: function() {
                 
            },
            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
                    breadcrumb(data.breadcrumb);
                } else {
                    handleError(data);
                }
            },
            complete: function() {
                 
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function save() {
        $("#btnSave").text("saving...");
        $("#btnSave").attr("disabled", true);
        var url, method;

        if (save_label == "add") {
            url = base_url + 'management_users/ajax/users/add';
            method = "saved";
        } else {
            url = base_url + 'management_users/ajax/users/update';
            method = "updated";
        }

        $.ajax({
            url: url,
            type: "POST",
            data: $("#form").serialize(),
            dataType: "json",
            beforeSend: function() {
                 
            },
            success: function(data) {
                if (data.status) {
                    back();
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

    function deleteData(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#084594",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: base_url + 'management_users/ajax/users/delete/' + id,
                    type: "POST",

                    beforeSend: function() {
                         
                    },
                    success: function(data) {
                        if (data.status) {
                            back();
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

    function infoData(id) {
        $.ajax({
            url: base_url + 'management_users/ajax/users/detailHTML/' + id,
            type: "GET",
            dataType: "JSON",
            beforeSend: function() {
                 
            },
            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
                    breadcrumb(data.breadcrumb);
                } else {
                    handleError(data);
                }
            },
            complete: function() {
                 
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function addRole(id) {
        $.ajax({
            url: base_url + 'management_users/ajax/users/addRoleHTML/' + id,
            type: "POST",

            beforeSend: function() {
                 
            },
            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
                    breadcrumb(data.breadcrumb);
                    $("#roleCode").select2({
                        theme: "bootstrap-5",
                        containerCssClass: "select2--small", // For Select2 v4.0
                        selectionCssClass: "select2--small", // For Select2 v4.1
                        dropdownCssClass: "select2--small",
                    });
                } else {
                    handleError(data);
                }
            },
            complete: function() {
                 
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function saveRole(id) {
        $("#btnSaveRole").text("saving...");
        $("#btnSaveRole").attr("disabled", true);
        var url = base_url + 'management_users/ajax/users/addRole/' + id;

        $.ajax({
            url: url,
            type: "POST",
            data: $("#form").serialize(),
            dataType: "json",
            beforeSend: function() {
                 
            },
            success: function(data) {
                if (data.status) {
                    backDetail(data.userCode);
                    handleToast("success", data.message);
                } else {
                    handleError(data);
                }
                $("#btnSaveRole").text("save");
                $("#btnSaveRole").attr("disabled", false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error adding  data");
                $("#btnSaveRole").text("save");
                $("#btnSaveRole").attr("disabled", false);
            },
        });

        $("#form input, #form textarea").on("keyup", function() {
            $(this).removeClass("is-valid is-invalid");
        });
        $("#form select").on("change", function() {
            $(this).removeClass("is-valid is-invalid");
        });
    }

    function deleteRole(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#084594",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: base_url + 'management_users/ajax/users/deleteRole/' + id,
                    type: "POST",

                    beforeSend: function() {
                         
                    },
                    success: function(data) {
                        if (data.status) {
                            backDetail(data.userCode);
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

    function addPermission(id) {
        $.ajax({
            url: base_url + 'management_users/ajax/users/addPermissionHTML/' + id,
            type: "POST",

            beforeSend: function() {
                 
            },
            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
                    breadcrumb(data.breadcrumb);
                    $("#permissionCode").select2({
                        theme: "bootstrap-5",
                        containerCssClass: "select2--small", // For Select2 v4.0
                        selectionCssClass: "select2--small", // For Select2 v4.1
                        dropdownCssClass: "select2--small",
                    });
                } else {
                    handleError(data);
                }
            },
            complete: function() {
                 
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function savePermission(id) {
        $("#btnSavePermission").text("saving...");
        $("#btnSavePermission").attr("disabled", true);
        var url = base_url + 'management_users/ajax/users/addPermission/' + id;

        $.ajax({
            url: url,
            type: "POST",
            data: $("#form").serialize(),
            dataType: "json",
            beforeSend: function() {
                 
            },
            success: function(data) {
                if (data.status) {
                    backDetail(data.userCode);
                    handleToast("success", data.message);
                } else {
                    handleError(data);
                }
                $("#btnSavePermission").text("save");
                $("#btnSavePermission").attr("disabled", false);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error adding  data");
                $("#btnSavePermission").text("save");
                $("#btnSavePermission").attr("disabled", false);
            },
        });

        $("#form input, #form textarea").on("keyup", function() {
            $(this).removeClass("is-valid is-invalid");
        });
        $("#form select").on("change", function() {
            $(this).removeClass("is-valid is-invalid");
        });
    }

    function deletePermission(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#084594",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: base_url + 'management_users/ajax/users/deletePermission/' + id,
                    type: "POST",

                    beforeSend: function() {
                         
                    },
                    success: function(data) {
                        if (data.status) {
                            backDetail(data.userCode);
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