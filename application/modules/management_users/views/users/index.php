<div class="card mb-4">
    <div class="card-body px-5 pt-2 pb-2">
        <div class="d-flex justify-content-end m-3">
            <?php echo ((in_array('CU', $userPermission)) ? '<i class="ri-add-circle-line ri-xl text-success " role="button" title="Create" onclick="addData()"></i>' : '') ?>
        </div>
        <div class="table-responsive">
            <?php $header = ((count(array_intersect($userPermission, ['DU', 'UU', 'RRU', 'RUP'])) > 0) ? ['Email', 'Status', 'Action'] : ['Email', 'Status']); ?>
            <?php echo table('users', $header, ['table-hover py-1 px-0 mx-0']); ?>
        </div>
    </div>
</div>