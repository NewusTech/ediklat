<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card mb-4">
            <div class="card-body px-5 pt-2 pb-2">
                <div class="row mb-3">
                    <div class="d-flex justify-content-between mt-2 py-auto">
                        <i title="back" role="button" class="ri-arrow-left-circle-line ri-lg my-auto text-danger" onclick="backDetail(<?php echo $userCode ?>)"></i>
                        <p class="pl-4 my-auto fw-bolder"> <?php echo $title ?></p>
                    </div>
                </div>
                <?php echo form_open('', ["id" => "form"]); ?>
                <?php echo input('hidden', 'userCode', '', [], ["value" => $userCode]); ?>
                <?php echo selectWithFormGroup('roleCode', 'Role', 'roleCode', $roles, $roleCode, [], []); ?>
                <div class="d-flex justify-content-end">
                    <?php echo button('Save', ["btn-primary"], ["id" => "btnSaveRole", "onclick" => "saveRole(" . $userCode . ")"]); ?>
                </div>
                <?php echo form_close(); ?>

            </div>
        </div>
    </div>
</div>