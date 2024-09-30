<?php

/**
 * get all profile in file json
 * @return array
 */
function getProfileWeb(): array
{
    if (stripos(PHP_OS, "WIN") === 0) {
        $json = file_get_contents(APPPATH . '\setting\profile_web.json');
    } else {
        $json = file_get_contents(APPPATH . '/setting/profile_web.json');
    }
    return json_decode($json, true);
}


function translateOpenClose(string $status, bool $withColor = true): string
{
    if ($status == 'open') {
        return ($withColor == true ? '<span class="text-success">Buka</span>' : 'Buka');
    }
    if ($status == 'close') {
        return ($withColor == true ? '<span class="text-danger">Tutup</span>' : 'Tutup');
    }
}

function path_by_os($path)
{
    if (stripos(PHP_OS, "WIN") === 0) {
        $data = str_replace('/', '\\', $path);
    } else {
        $data = str_replace('\\', '/', $path);
    }
    return $data;
}

function hariIndo($hariInggris)
{
    switch ($hariInggris) {
        case 'Sunday':
            return 'Minggu';
        case 'Monday':
            return 'Senin';
        case 'Tuesday':
            return 'Selasa';
        case 'Wednesday':
            return 'Rabu';
        case 'Thursday':
            return 'Kamis';
        case 'Friday':
            return 'Jumat';
        case 'Saturday':
            return 'Sabtu';
        default:
            return 'hari tidak valid';
    }
}

function tanggal_indo($tanggal)
{
    if($tanggal == NULL || $tanggal == ''){
        return '';
    }
    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $split = explode('-', $tanggal);
    return substr($split[2], 0, 2) . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

function tgl_hari($tanggal)
{
    $tgl = tanggal_indo($tanggal);
    $date = new DateTime($tanggal);
    $hari = $date->format('l');
    $hari = hariIndo($hari);
    return $hari . ', ' . $tgl;
}

function waktu($tanggal){
    return substr($tanggal,11,5);
}

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function notifText($text){
    if(isMobile()){
        return wordwrap($text,40,"<br>\n");
    }else{
        return $text;
    }
}
