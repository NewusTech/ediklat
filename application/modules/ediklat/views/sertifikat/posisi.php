<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body px-5 pt-2 pb-2">
                <div class="row mb-3">
                    <div class="d-flex justify-content-between mt-2 py-auto">
                        <i title="back" role="button" class="ri-arrow-left-circle-line ri-lg my-auto text-danger" onclick="back()"></i>
                        <p class="pl-4 my-auto fw-bolder"> <?php echo $title ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <?php echo form_open_multipart('', ["id" => "form"]); ?>
                        <?php foreach ($position as $k => $v) : ?>
                            <p class="text-bold text-sm text-uppercase my-0 py-0">Halaman <?php echo $k ?></p>
                            <input type="hidden" name="halaman[<?php echo $k ?>]" value="<?php echo $k ?>">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <?php echo selectWithFormGroup('status', 'Status', 'status[' . $k . ']', [
                                        false => 'Tidak Aktif',
                                        true => 'Aktif',
                                    ], $v['aktif']) ?>
                                </div>
                            </div>
                            <?php if ($v['aktif'] == true) : ?>
                                <div class="for<?php echo $k ?>">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo inputWithFormGroup('Jarak kiri nomer', 'number', 'nomerLeft[' . $k . ']', 'Jarak kiri nomer', [], ['value' => $v['nomer']['left'], 'required']) ?>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo inputWithFormGroup('Jarak atas nomer', 'number', 'nomerTop[' . $k . ']', 'Jarak atas nomer', [], ['value' => $v['nomer']['top'], 'required']) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo inputWithFormGroup('Jarak kiri tanggal kegiatan', 'number', 'tanggalKegiatanLeft[' . $k . ']', 'Jarak kiri tanggal kegiatan', [], ['value' => $v['tanggalKegiatan']['left'], 'required']) ?>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo inputWithFormGroup('Jarak atas tanggal kegiatan', 'number', 'tanggalKegiatanTop[' . $k . ']', 'Jarak atas tanggal kegiatan', [], ['value' => $v['tanggalKegiatan']['top'], 'required']) ?>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo inputWithFormGroup('Jarak kiri tanggal tanda tangan', 'number', 'tanggalTandaTanganLeft[' . $k . ']', 'Jarak kiri tanggal tanda tangan', [], ['value' => $v['tanggalTandaTangan']['left'], 'required']) ?>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo inputWithFormGroup('Jarak atas tanggal tanda tangan', 'number', 'tanggalTandaTanganTop[' . $k . ']', 'Jarak atas tanggal tanda tangan', [], ['value' => $v['tanggalTandaTangan']['top'], 'required']) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo inputWithFormGroup('Horizontal nama', 'number', 'namaX[' . $k . ']', 'Horizontal nama', [], ['value' => $v['nama']['x'], 'required']) ?>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo inputWithFormGroup('Vertikal nama', 'number', 'namaY[' . $k . ']', 'Vertikal nama', [], ['value' => $v['nama']['y'], 'required']) ?>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo inputWithFormGroup('Lebar nama', 'number', 'namaWidth[' . $k . ']', 'Lebar nama', [], ['value' => $v['nama']['width'], 'required']) ?>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo inputWithFormGroup('Tinggi nama', 'number', 'namaHeight[' . $k . ']', 'Tinggi nama', [], ['value' => $v['nama']['height'], 'required']) ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo inputWithFormGroup('Horizontal kegiatan', 'number', 'kegiatanX[' . $k . ']', 'Horizontal kegiatan', [], ['value' => $v['kegiatan']['x'], 'required']) ?>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo inputWithFormGroup('Vertikal kegiatan', 'number', 'kegiatanY[' . $k . ']', 'Vertikal kegiatan', [], ['value' => $v['kegiatan']['y'], 'required']) ?>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo inputWithFormGroup('Lebar kegiatan', 'number', 'kegiatanWidth[' . $k . ']', 'Lebar kegiatan', [], ['value' => $v['kegiatan']['width'], 'required']) ?>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo inputWithFormGroup('Tinggi kegiatan', 'number', 'kegiatanHeight[' . $k . ']', 'Tinggi kegiatan', [], ['value' => $v['kegiatan']['height'], 'required']) ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo inputWithFormGroup('Horizontal QRCode', 'number', 'QRCodeX[' . $k . ']', 'Horizontal QRCode', [], ['value' => $v['QRCode']['x'], 'required']) ?>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo inputWithFormGroup('Vertikal QRCode', 'number', 'QRCodeY[' . $k . ']', 'Vertikal QRCode', [], ['value' => $v['QRCode']['y'], 'required']) ?>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo inputWithFormGroup('Jarak kiri text QRCode', 'number', 'textQRCodeLeft[' . $k . ']', 'Jarak kiri text QRCode', [], ['value' => $v['textQRCode']['left'], 'required']) ?>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <?php echo inputWithFormGroup('Jarak atas text QRCode', 'number', 'textQRCodeTop[' . $k . ']', 'Jarak atas text QRCode', [], ['value' => $v['textQRCode']['top'], 'required']) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <div class="row">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-sm btn-primary" onclick="updatePDF(<?php echo $certificateCode ?>)"><i class="fa fa-refresh" aria-hidden="true"></i> UPDATE</button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>

                    </div>
                    <div class="col-sm-12 col-md-12" id="viewPDF">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        getPDF();
    });

    function getPDF() {
        $.ajax({
            url: base_url + 'ediklat/ajax/sertifikat/getPDF',
            type: "POST",
            success: function(data) {
                if (data.status) {
                    var html = `
                    <embed src="` + data.url + `" style="width: 100%;height:300px">
                    `;
                    $("#viewPDF").html(html);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function updatePDF(id) {
        $.ajax({
            url: base_url + 'ediklat/ajax/sertifikat/updatePDF/' + id,
            type: "POST",
            data: $("#form").serialize(),
            dataType: "json",
            success: function(data) {
                if (data.status) {
                    posisiData(id);
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