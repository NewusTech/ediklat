<div class="col-md-6 offset-md-3 ">
    <div class="card mb-4">
        <div class="card-body px-5 pt-3 pb-3">
            <div class="d-flex justify-content-between">
                <i title="back" role="button" class="ri-arrow-left-circle-line ri-lg my-auto text-danger" onclick="back()"></i>
                <h6 class="font-weight-bolder mb-0">Data Detail Role</h6>
            </div>
            <div class="row mt-4">

                <div class="table-responsive">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="p-1 fw-bold">Role</td>
                            <td class="p-1">:</td>
                            <td class="p-1"><?php echo $role ?></td>
                        </tr>
                        <tr>
                            <td class="p-1 fw-bold d-flex">Permission <?php echo ((in_array('CRP', $userPermission)) ? '<i class="ri-add-circle-line ri-lg text-success my-auto" role="button" title="Add Permission" onclick="addPermission(' . $roleCode . ')"></i>' : '') ?></td>
                            <td class="p-1">:</td>
                            <td class="p-1"><?php echo $permissionHTML ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>