<div class="card mb-4">
    <div class="card-body px-5 pt-2 pb-2">
        <div class="d-flex justify-content-end my-3">
            <?php echo ((in_array('CBROADCAST', $userPermission)) ? '<a href="' . base_url('ediklat/broadcast/add') . '"><i class="ri-add-circle-line ri-xl text-success " role="button" title="Tambah"></i></a>' : '') ?>
        </div>
        <div class="table-responsive">
            <table class="table table-sm text-sm" id="broadcast">
                <thead>
                    <tr>
                        <th>Kode Broadcast</th>
                        <th>Text</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($broadcast as $b => $v) : ?>
                        <tr>
                            <td><?php echo $v['broadcastCode'] ?></td>
                            <td><?php echo $v['text'] ?></td>
                            <td>
                                <a href="<?php echo base_url('ediklat/broadcast/delete/' . $v['broadcastCode']) ?>"><i class="ri-delete-bin-line ri-lg text-danger m-1" role="button"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>