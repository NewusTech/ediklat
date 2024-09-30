<?php

/**
 * Get all permission from user by userCode
 * @param string $userCode optional
 * @return array
 */
function getPermissionFromUser(string $userCode = ''): array
{
    $CI = get_instance();
    $access = [];
    if ($userCode == '') {
        $userCode = $CI->session->userdata('userCode');
    } else {
        $userCode = $userCode;
    }
    if ($userCode == NULL) {
        return $access;
    } else {
        $role = $CI->db->get_where('role_user', ['userCode' => $userCode, 'deleteAt' => NULL])->result_array();
        $tempPermission = [];
        foreach ($role as $k => $v) {
            $permission = $CI->db
                ->select('p.permission')
                ->join('permission p', 'p.permissionCode=rp.permissionCode')
                ->get_where('role_permission rp', ['rp.roleCode' => $v['roleCode'], 'rp.deleteAt' => NULL])
                ->result_array();
            foreach ($permission as $p => $f) {
                if (!in_array($f['permission'], $tempPermission)) {
                    $tempPermission[] = $f['permission'];
                }
            }
        }

        $specialPermission = $CI->db
            ->select('p.permission')
            ->join('permission p', 'p.permissionCode=up.permissionCode')
            ->get_where('user_permission up', ['up.userCode' => $userCode, 'up.deleteAt' => NULL])
            ->result_array();
        foreach ($specialPermission as $k => $v) {
            if (!in_array($v['permission'], $tempPermission)) {
                $tempPermission[] = $v['permission'];
            }
        }
        $access = $tempPermission;
        return array_values($access);
    }
}

/**
 * Check permission of user
 * @param string $permission
 * @return bool
 */
function checkPermission(string $permission): bool
{
    if (in_array($permission, getPermissionFromUser())) {
        return true;
    } else {
        return false;
    }
}

/**
 * Check user is login
 * @return bool
 */
function isLogin(): bool
{
    $CI = &get_instance();
    if ($CI->session->userdata('userCode') == NULL) {
        return false;
    } else {
        return true;
    }
}

/**
 * Check role of user
 * @param string $roleCode
 * @param string $userCode
 * @return bool
 */
function checkRole(string $roleCode = '', string $userCode = '')
{
    $CI = get_instance();
    if ($userCode == '') {
        $userCode = $CI->session->userdata('userCode');
    } else {
        $userCode = $userCode;
    }
    $check = $CI->db->get_where('role_user', [
        'deleteAt' => NULL,
        'userCode' => $userCode,
        'roleCode' => $roleCode
    ])->row();
    if ($check == NULL) {
        return false;
    } else {
        return true;
    }
}

/**
 * Check role member of user
 * @param string $roleCode
 * @param string $userCode
 * @return bool
 */
function checkMember(string $userCode = '')
{
    $CI = get_instance();
    if ($userCode == '') {
        $userCode = $CI->session->userdata('userCode');
    } else {
        $userCode = $userCode;
    }
    $check = $CI->db->get_where('member', [
        'deleteAt' => NULL,
        'userCode' => $userCode,
    ])->row();
    if ($check == NULL) {
        return false;
    } else {
        return true;
    }
}


/**
 * Check role admin of user
 * @param string $roleCode
 * @param string $userCode
 * @return bool
 */
function checkAdmin(string $userCode = '')
{
    $CI = get_instance();
    if ($userCode == '') {
        $userCode = $CI->session->userdata('userCode');
    } else {
        $userCode = $userCode;
    }
    $check = $CI->db->get_where('role_user', [
        'deleteAt' => NULL,
        'userCode' => $userCode,
        'roleCode' => '2',
    ])->row();
    if ($check == NULL) {
        return false;
    } else {
        return true;
    }
}
