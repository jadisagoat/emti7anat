<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Back-office authentication + privilege guard (config/hooks.php: post_controller_constructor).
 * Runs for every request whose router directory is back_office/, before the controller method, so no
 * controller has to check anything itself:
 *  1. back_office/home: index, login_validate, lang and logout are public; every other method needs a session.
 *  2. Every other back_office controller needs session user_id. Without it: AJAX / JSON endpoints
 *     (grid_data, sorted, X-Requested-With, Accept: application/json) get HTTP 401 JSON {"error":"auth"}
 *     (dxgrid.php sends the browser to the login page on 401); page requests are redirected to back_office/home.
 *  3. Level 0 sub-users (session level 0, table `user`) are checked against `priviledge`:
 *     the controller maps to content_type (used_name, or name with spaces -> underscores) and the method needs
 *       read_p   for bo_guard_kind() 'read'   (index, lists, results, grid_data, export, view, read, ...)
 *       delete_p for 'delete'                 (delete, delete_all)
 *       write_p  for everything else          (add, edit, submit, sorted, delete_image, renew, ...)
 *     Controllers that are not content types are closed to level 0, except what the original UI showed them:
 *     home (dashboard, lang, logout) and settings (index + submit_user = their own `user` row).
 *     Denied: HTTP 403 JSON {"error":"forbidden"} for AJAX/JSON, else an HTTP 403 page with a dashboard link.
 *  Levels 1 and 2 (table `admin`) keep full access.
 */

/** 'read' | 'write' | 'delete' for a controller method. */
function bo_guard_kind($method)
{
    $m = strtolower((string) $method);
    if (in_array($m, array('delete', 'delete_all'), true)) return 'delete';
    if (in_array($m, array('index', 'lists', 'list', 'results', 'result', 'grid_data', 'export', 'view', 'read',
                           'show', 'details', 'detail', 'preview', 'unread', 'opened', 'search'), true)) return 'read';
    return 'write';
}

/** TRUE when the client expects JSON (grid / drag-sort / any XHR). */
function bo_guard_wants_json($method)
{
    $m = strtolower((string) $method);
    if (in_array($m, array('grid_data', 'sorted', 'sorted_attr', 'sorteddd'), true)) return true;
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') return true;
    return isset($_SERVER['HTTP_ACCEPT']) && stripos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false
        && stripos($_SERVER['HTTP_ACCEPT'], 'text/html') === false;
}

/** Stop the request: JSON error or (401) redirect to the login page / (403) a small "access denied" page. */
function bo_guard_deny($status, $method)
{
    if (!headers_sent()) {
        header('Cache-Control: no-store, no-cache, must-revalidate');
    }
    if (bo_guard_wants_json($method)) {
        if (!headers_sent()) {
            set_status_header($status);
            header('Content-Type: application/json; charset=utf-8');
        }
        echo json_encode(array('error' => $status === 401 ? 'auth' : 'forbidden', 'data' => array(), 'totalCount' => 0));
        exit;
    }
    if ($status === 401) {
        header('Location: ' . base_url() . 'back_office/home', true, 302);
        exit;
    }
    $rtl = bo_rtl();
    $h = function ($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); };
    if (!headers_sent()) {
        set_status_header(403);
        header('Content-Type: text/html; charset=utf-8');
    }
    echo '<!DOCTYPE html><html lang="' . ($rtl ? 'ar' : 'en') . '" dir="' . ($rtl ? 'rtl' : 'ltr') . '"><head><meta charset="utf-8">'
       . '<meta name="viewport" content="width=device-width, initial-scale=1"><title>' . $h(bo_t('msg_forbidden_title')) . '</title>'
       . '<style>body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;background:#f5f5f9;'
       . 'font-family:"Public Sans",Tahoma,Arial,sans-serif;color:#566a7f}.box{background:#fff;border-radius:.5rem;'
       . 'box-shadow:0 2px 6px rgba(67,89,113,.12);padding:2rem 2.5rem;max-width:460px;margin:16px;text-align:center}'
       . 'h1{font-size:1.4rem;color:#ff3e1d;margin:0 0 .75rem}p{margin:0 0 1.5rem;line-height:1.6}'
       . 'a{display:inline-block;background:#696cff;color:#fff;text-decoration:none;padding:.5rem 1.25rem;border-radius:.375rem}</style>'
       . '</head><body><div class="box"><h1>' . $h(bo_t('msg_forbidden_title')) . '</h1><p>' . $h(bo_t('msg_forbidden')) . '</p>'
       . '<a href="' . $h(base_url() . 'back_office/home/dashboard') . '">' . $h(bo_t('msg_back_dashboard')) . '</a></div></body></html>';
    exit;
}

/** The hook. */
function bo_guard()
{
    $CI =& get_instance();
    if (!is_object($CI) || !isset($CI->router)) return;
    if (strtolower(trim((string) $CI->router->fetch_directory(), '/')) !== 'back_office') return;
    $class  = strtolower((string) $CI->router->fetch_class());
    $method = strtolower((string) $CI->router->fetch_method());
    if ($method === '') $method = 'index';

    // 1 + 2: authentication
    if ($class === 'home' && in_array($method, array('index', 'login_validate', 'lang', 'logout'), true)) return;
    if (!$CI->session->userdata('user_id')) bo_guard_deny(401, $method);

    // 3: level 0 sub-users
    // levels 1 / 2 (admin table): full access. A session without a level is treated as level 0 (closed).
    $level = $CI->session->userdata('level');
    if ($level !== false && $level !== null && $level !== '' && (int) $level !== 0) return;
    if ($class === 'home') {
        if (in_array($method, array('dashboard'), true)) return;
        bo_guard_deny(403, $method);
    }
    if ($class === 'settings') {
        if (in_array($method, array('index', 'submit_user'), true)) return;
        bo_guard_deny(403, $method);
    }
    $ct = $CI->db->query('SELECT id_content FROM content_type WHERE used_name = ? OR REPLACE(name, \' \', \'_\') = ? ORDER BY id_content LIMIT 1',
                         array($class, $class))->row_array();
    if (!$ct) bo_guard_deny(403, $method);
    $p = $CI->db->query('SELECT read_p, write_p, delete_p FROM priviledge WHERE id_user = ? AND id_content = ? ORDER BY id_privi LIMIT 1',
                        array((int) $CI->session->userdata('user_id'), (int) $ct['id_content']))->row_array();
    $col = array('read' => 'read_p', 'write' => 'write_p', 'delete' => 'delete_p');
    $kind = bo_guard_kind($method);
    if (!$p || (int) $p[$col[$kind]] !== 1) bo_guard_deny(403, $method);
}
