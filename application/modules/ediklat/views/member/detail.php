<style>
    .imgCustom {
        height: auto;
        /* in order that both header and footer of .modal would be on the screen */
        max-width: 100%;
        margin: auto;
        display: block;
    }
</style>
<div class="modal fade" id="detailMember" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Member</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <?php if (file_exists(path_by_os(FCPATH . 'assets/img/participant/' . $picture))) { ?>
                            <img src="<?php echo base_url('assets/img/participant/' . $picture) ?>" class="img-fluid rounded imgCustom" alt="">
                        <?php } else { ?>
                            <img src="<?php echo base_url('assets/img/participant/default.png') ?>" class="img-fluid rounded imgCustom" alt="">
                        <?php } ?>
                    </div>
                    <div class="col-md-8">
                        <table class=text-xs>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td>
                                    <p class="text-xs my-0 py-0"><?php echo $name ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>NIK</td>
                                <td>:</td>
                                <td>
                                    <p class="text-xs my-0 py-0"><?php echo $nik ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>NPWP</td>
                                <td>:</td>
                                <td>
                                    <p class="text-xs my-0 py-0"><?php echo $npwp ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>NUPTK</td>
                                <td>:</td>
                                <td>
                                    <p class="text-xs my-0 py-0"><?php echo $npsn ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>Tempat Lahir</td>
                                <td>:</td>
                                <td>
                                    <p class="text-xs my-0 py-0"><?php echo $birthplace ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Lahir</td>
                                <td>:</td>
                                <td>
                                    <p class="text-xs my-0 py-0"><?php echo tanggal_indo($birthdate) ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>No Hp</td>
                                <td>:</td>
                                <td>
                                    <p class="text-xs my-0 py-0"><?php echo $phone ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>Instansi</td>
                                <td>:</td>
                                <td>
                                    <p class="text-xs my-0 py-0"><?php echo $agency ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>Jenis Kelamin</td>
                                <td>:</td>
                                <td>
                                    <p class="text-xs my-0 py-0"><?php echo ($gender == 'L' ? 'Laki-laki' : 'Perempuan') ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>Pangkat/Golongan</td>
                                <td>:</td>
                                <td>
                                    <p class="text-xs my-0 py-0"><?php echo $rank ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>Jabatan dalam dinas</td>
                                <td>:</td>
                                <td>
                                    <p class="text-xs my-0 py-0"><?php echo $rank_dinas ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>Layanan Pendidikan</td>
                                <td>:</td>
                                <td>
                                    <p class="text-xs my-0 py-0"><?php echo $education_service ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>Pendidikan Terakhir</td>
                                <td>:</td>
                                <td>
                                    <p class="text-xs my-0 py-0"><?php echo $education ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>Kabupaten/Kota</td>
                                <td>:</td>
                                <td>
                                    <p class="text-xs my-0 py-0"><?php echo $state ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td>
                                    <p class="text-xs my-0 py-0"><?php echo $address ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>