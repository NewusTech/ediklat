<?php

/**
 * This helper function for generate breadcrumb
 * @param array $breadcrumb [ ["text" => "asd", "link" => ""], ["text" => "asd", "link" => "", "action" => ""] ]
 * @param string $title
 * @return html
 */
function breadcrumb(array $breadcrumb = [], string $title = '')
{
    $html = '';
    $i = 1;
    $count = count($breadcrumb);
    $html .= '
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
    ';
    foreach ($breadcrumb as $k => $v) {
        if ($i == $count) {
            $html .= '<li class="breadcrumb-item text-sm active">' . $v['text'] . '</li>';
        } else {
            $html .= '<li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" style="text-decoration: none" href=" ' . ((isset($v['url'])) ? $v['url'] : 'javascript:void(0)') . '" ' . ((isset($v['action'])) ? 'onclick="' . $v['action'] . '"' : '') . '>' . $v['text'] . '</a></li>';
        }
        $i++;
    }

    $html .= '
          </ol>
          <h6 class="font-weight-bolder mb-0">' . $title . '</h6>
        </nav>
    ';
    return $html;
}


/**
 * This helper function for generate tag basic button
 * @param string $text
 * @param array $class
 * @param array $attribute
 * @return html
 */
function button(string $text, array $class = [], array $attribute = [])
{
    $stringClass = implode(' ', $class);
    $stringAttribute = '';
    foreach ($attribute as $k => $v) {
        $stringAttribute .= ' ' . $k . '="' . $v . '" ';
    }
    $html = '';
    $html .= '<a class="btn ' . $stringClass . '" href="javascript:void(0)" ' . $stringAttribute . '>' . $text . '</a>';
    return $html;
}



/**
 * This helper function for generate tag input
 * @param string $type = "text"|"email"|"number"|"date"|"checkbox"|"radio"|"file"
 * @param string $name
 * @param string $placeholder
 * @param array $class // default is null, if you add other class input in array to $class ex: ["form-add","form-test"]
 * @param array $attribute // default is null, if you add other class input in array to $attribute
 * @return html
 */
function input(string $type = 'text', string $name = '', string $placeholder = '', array $class = [], array $attribute = [])
{
    $stringClass = implode(' ', $class);
    $stringAttribute = '';
    $temp = '';
    foreach ($attribute as $k => $v) {
        if (is_int($k)) {
            $temp = $v;
        } else {
            $stringAttribute .= ' ' . $k . '="' . $v . '" ';
        }
    }
    $html = '';
    $html .= '
             <input type="' . $type . '" name="' . $name . '" class="' . (($type == "checkbox" || $type == "radio") ? "form-check-input" : "form-control") . ' mb-1 ' . $stringClass . '" placeholder="' . $placeholder . '" ' . $stringAttribute . ' ' . $temp . ' autofocus />

             ';
    return $html;
}

/**
 * this function for generate input group
 * @param string $label
 * @param array $data | ['<span class="input-group-text">Full Name</span>',input('text','firstName'),input('text','lastName'),]
 * @param array $classOnInputGroup | for add class into tag div input group
 * @return html
 */
function inputGroupWithFormGroup(string $label = '', array $data = [], array $classOnInputGroup = [])
{
    if ($label != '') {
        $label = '<label>' . $label . '</label>';
    }
    $input = '';
    foreach ($data as $d => $k) {
        $input .= $k;
    }
    $html = '
        <div class="form-group">
            ' . $label . '
            <div class="input-group ' . implode(' ', $classOnInputGroup) . '">
                ' . $input . '
            </div>
        </div>
    ';
    return $html;
}

/**
 * This helper function for generate tag input with form group
 * @param string $label
 * @param string $type = "text"|"email"|"number"|"date"|"checkbox"|"radio"|"file"
 * @param string $name
 * @param string $placeholder
 * @param array $class // default is null, if you add other class input in array to $class ex: ["form-add","form-test"]
 * @param array $attribute // default is null, if you add other class input in array to $attribute
 * @return html
 */
function inputWithFormGroup(string $label = '', string $type = 'text', string $name = '', string $placeholder = '', array $class = [], array $attribute = [])
{
    $html = '';
    $html .= '<div class="form-group">
                <label>' . $label . '</label>

                ' . input($type, $name, $placeholder, $class, $attribute) . '
              </div>
                ';
    return $html;
}


/**
 * This helper function for generate checkbox
 * 
 * @param string $name untuk atribut name pada tag input
 * @param array $label | ['Create Group' => 'CG', 'Delete Group' => 'DG']
 * @param array $selected | ['CG', 'DG']
 * @param array|null $atribut untuk tambahan atribut pada tag input
 * 
 * @return string
 */

function checkbox(string $name, array $label = [], array $selected = [], array $atribut = [])
{
    $html = '';
    foreach ($label as $k => $v) {
        $html .= '<div class="form-check mb-1">';
        $new_atribut = $atribut;
        if (in_array($v, $selected)) {
            array_push($new_atribut, 'checked');
        }
        $new_atribut['value'] = $v;
        $html .= input('checkbox', $name, '', [], $new_atribut);
        $html .= '<label class="custom-control-label" >' . $k . '</label>';
        $html .= '</div>';
    }

    return $html;
}
/**
 * This helper function for generate checkbox
 * 
 * @param string $name untuk atribut name pada tag input
 * @param array $label | ['Create Group' => 'CG']
 * @param array $selected | ['CG', 'DG']
 * @param array|null $atribut untuk tambahan atribut pada tag input
 * 
 * @return string
 */

function toggle(string $name, array $label = [], array $selected = [], array $atribut = [])
{
    $html = '';
    foreach ($label as $k => $v) {
        $html .= '<div class="form-check form-switch mb-1">';
        $new_atribut = $atribut;
        if (in_array($v, $selected)) {
            array_push($new_atribut, 'checked');
        }
        $new_atribut['value'] = $v;
        $html .= input('checkbox', $name, '', [], $new_atribut);
        $html .= '<label class="custom-control-label" >' . $k . '</label>';
        $html .= '</div>';
    }

    return $html;
}


/**
 * This helper function for generate checkbox
 * 
 * @param string $name untuk atribut name pada tag input
 * @param array $label | ['Laki-laki' => 'l', 'Perempuan' => 'p']
 * @param string $checked | memilih label yang langsung di check 
 * @param array|null $atribut untuk tambahan atribut pada tag input
 * 
 * @return string
 */
function radio(string $name, array $label = [], string $checked = '',  array $atribut = [])
{
    $html = '';
    foreach ($label as $k => $v) {
        $html .= '<div class="form-check mb-1">';
        $new_atribut = $atribut;
        if ($v == $checked) {
            array_push($new_atribut, 'checked');
        }
        $new_atribut['value'] = $v;
        $html .= input('radio', $name, '', [], $new_atribut);
        $html .= '<label class="custom-control-label" >' . $k . '</label>';
        $html .= '</div>';
    }

    return $html;
}


/**
 * This helper function is used to generate modal 
 * 
 * sebelum memanggil modal pastikan tag <button>/<a> sudah diberikan atribut: 
 * data-bs-toggle="modal" 
 * data-bs-target="#idModal"
 * 
 * untuk menutup modal bisa menggunakan atribut 
 * data-bs-dismiss="modal"
 * pada tag untuk menutup modal 
 * 
 * @param string $id untuk atribut "id" di modal
 * @param any $data untuk data yang akan ditampilkan di modal
 * 
 * 
 * @return string
 */

function modal(string $id,  $data)
{
    $html = '<div class="modal fade" id="' . $id . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-body">' .
        $data
        . '</div>
      </div>
    </div>
  </div>';
    return $html;
}


/**
 * This function for generate select
 * @param string $name
 * @param array $option | ["value"=>"text", "value 2"=>"text 2"]
 * @param string $selected
 * @return html
 */
function select(string $name = '', array $option = [], string $selected = '', array $class = [], array $attribute = [], $labelDefault = 'Pilih')
{
    $stringClass = implode(' ', $class);
    $stringAttribute = '';
    $temp = '';
    foreach ($attribute as $k => $v) {
        if (is_int($k)) {
            $temp = $v;
        } else {
            $stringAttribute .= ' ' . $k . '="' . $v . '" ';
        }
    }

    $optionHtml = $labelDefault === 'Pilih' ? '<option value="" selected disabled hidden>-- Pilih --</option>' : '<option value="" selected  >-- '.$labelDefault.' --</option>';
    foreach ($option as $o => $v) {
        $optionHtml .= '<option value="' . $o . '" ' . (($o == $selected) ? 'selected' : '') . '>' . $v . '</option>';
    }
    $html = '
        <select class="form-control ' . $stringClass . '"  name="' . $name . '" ' . $stringAttribute . ' ' . $temp . '>
            ' . $optionHtml . '
        </select>
    ';
    return $html;
}

/**
 * This function for generate select
 * @param string $id
 * @param string $label
 * @param string $name
 * @param array $option | ["value"=>"text", "value 2"=>"text 2"]
 * @param string $selected
 * @return html
 */
function selectWithFormGroup(string $id = '', string $label = '', string $name = '', array $option = [], string $selected = '', array $class = [], array $attribute = [], $labelDefault = 'Pilih')
{
    $attribute["id"] = $id;
    $html = '
    <div class="form-group d-flex flex-column">
        <label for="' . $id . '">' . $label . '</label>
        '
        . select($name, $option, $selected, $class, $attribute, $labelDefault) .
        '
    </div>
    ';
    return $html;
}




/**
 * This Function for generate table
 * @param string $id untuk atribut "id" di tag tbody 
 * @param array|null $col Nama-nama kolom
 * @param string|null $title Judul tabel
 * 
 * @return string
 */

function table(string $id = '', $col = [], array $class = [], $action = true)
{
    $stringClass = implode(' ', $class);
    $head = tampil($col, array_depth($col), 1);
    // $totalLength = array_length_child($col);

    $tr = [];
    $html = '
                            
                                <table class="table align-items-center mb-0 ' . $stringClass . '"  id="' . $id . '">
                                    <thead class=""> ';
    foreach ($head as $c => $v) {
        $align = $c == (count($head) - 1) && $action ? "text-center" : "";
        $tr[$v['level']][] = '<th class=" ps-2 ' . $align . ' text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan=' . $v['row'] . ' colspan=' . $v['col'] . '>' . $v['title'] . '</th>';
    }
    //Coloum Action
    // if ($action == true) {
    //     $tr[1][] = '<th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" rowspan=' . array_depth($col) . ' colspan=' . 1 . '>' . "Action" . '</th>';
    // }
    foreach ($tr as $k => $v) {
        $html .= '<tr>' . implode("", $v) . '</tr>';
    }

    $html .= '
                </thead>
                    <tbody>
    
                    </tbody>
                                </table>
                            
    ';

    return $html;
}

/**
 * fungsi untuk mencari semua header beserta colspan, rowspan, dan level
 * @param array $a Array yang akan digunakan
 * @param integer $depth Kedalaman array $a. Dapat dicari menggunakan fungsi array_depth()
 * @param integer $lev Level array.
 * @param array|null &$data array yang digunakan dalam rekursif
 */

function  tampil($a, $depth, $lev = 1, &$data = array())
{

    foreach ($a as $b => $value) {
        if (is_array($value)) {

            $col = array_length_child($value);
            array_push($data, [
                "title" => $b,
                "col" => $col,
                "row" => 1,
                "level" => $lev
            ]);
            // array_push($data, $lev);
            //echo $b . "(row:1 col:" . $col . " lev:" . $lev . ")" . "<br>";
            tampil($value, $depth - 1, $lev + 1, $data);
        } else {
            array_push($data, [
                "title" => $value,
                "col" => 1,
                "row" => $depth,
                "level" => $lev
            ]);
            // array_push($data, $lev);
            //echo $value . "(row:" . $depth . " col=1 lev:" . $lev . ")" . "|";
        }
    }
    return $data;
}

/**
 * Mencari kedalaman dari array (berapa aray child)
 * @param array $array aray yang ingin digunakan
 * 
 * @return integer
 */
function array_depth(array $array)
{
    $max_depth = 1;

    foreach ($array as $value) {
        if (is_array($value)) {
            $depth = array_depth($value) + 1;

            if ($depth > $max_depth) {
                $max_depth = $depth;
            }
        }
    }

    return $max_depth;
}



/**
 * Mencari Panjang dari satu array 
 * @param array $array aray yang ingin digunakan
 * 
 * @return integer
 */
function array_length(array $array)
{
    $length = 0;
    foreach ($array as $value) {

        $length++;
    }

    return $length;
}

/**
 * Mencari Panjang dari satu array child 
 * @param array $array aray yang ingin digunakan
 * 
 * @return integer
 */
function array_length_child(array $array)
{
    $length = array_length($array);
    $new_l = 0;
    foreach ($array as $value) {
        if (is_array($value)) {
            $new_l = array_length($value) - 1;
        }
    }

    return $new_l + $length;
}
