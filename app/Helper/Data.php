<?php

define('AES_METHOD', 'aes-256-cbc');

function languages()
{
    return [
        'English',
        'Indonesia',
        'Chinese',
        'Japanese',
        'Korean',
        'Arabic',
        'Spanish',
        'French',
    ];
}

function _isExist($variable, $key)
{
    return isset($variable[$key]) ? $variable[$key] : '';
}

function code_lang()
{
    return [
        'en',
        'id',
        'cn',
        'jp',
        'kr',
        'ar',
        'es',
        'fr',
    ];
}

function languages_code()
{
    return [
        'en' => '1',
        'id' => '2',
        'cn' => '3',
        'jp' => '4',
        'kr' => '5',
        'ar' => '6',
        'es' => '7',
        'fr' => '8',
    ];
}

function _get_languages($language)
{
    $languages = languages_code();

    return $languages[$language] ?? 2;
}

function _get_visibility_post($config)
{
    return collect($config)->flatMap(function ($subarray) {
        return collect($subarray)->map(function ($item, $key) {
            return [
                'key' => $key,
                'value' => $item,
            ];

        });
    });
}

function _custom_visibility_menu()
{
    return collect(config('cms.visibility.post.content'))->filter(
        function ($value, $key) {
            return $key == 'Template Pages' || $key == 'Template List';
        }
    )->flatMap(function ($subarray) {
        return collect($subarray)->map(function ($item, $key) {
            return [
                'key' => $key,
                'value' => $item,
            ];
        });
    })->pluck('key')->toArray();
}

function languageSettings()
{
    $array_languages = languages();
    $languages = config('app.language_setting');
    $html = '';
    for ($i = 0; $i < $languages; $i++) {
        ($i == 0) ? $data = 'active' : $data = '';
        $html .= '<li class="nav-item">
                    <a class="nav-link '.$data.'" data-target_seo="seo_'.$i.'"  data-toggle="tab" href="#'.$array_languages[$i].'" role="tab" aria-controls="'.$array_languages[$i].'" aria-selected="true">'.$array_languages[$i].'</a>
                </li>';
    }

    return $html;
}

function Status($status)
{
    if ($status == 1) {
        return '<span class="badge badge-success">Active</span>';
    } else {
        return '<span class="badge badge-danger">Inactive</span>';
    }
}

function Visibility($visibility, $config = false)
{
    $color = [
        '0' => 'badge-primary',
        '1' => 'badge-success',
        '2' => 'badge-warning',
        '3' => 'badge-danger',
        '4' => 'badge-info',
        '5' => 'badge-dark',
    ];
    $color = isset($color[$visibility]) ? $color[$visibility] : 'badge-dark';
    if ($config) {
        $config = config($config);

        return '<span class="badge '.$color.'">'.$config[$visibility].'</span>';
    }
    if ($visibility == 0) {
        return '<span class="badge badge-primary">visibility 0</span>';
    } elseif ($visibility == 1) {
        return '<span class="badge badge-success">visibility 1</span>';
    } elseif ($visibility == 2) {
        return '<span class="badge badge-warning">visibility 2</span>';
    } elseif ($visibility == 3) {
        return '<span class="badge badge-danger">visibility 3</span>';
    } elseif ($visibility == 4) {
        return '<span class="badge badge-info">visibility 4</span>';
    } else {
        return '<span class="badge badge-dark">visibility</span>';
    }
}

function has_child($value, $route)
{
    if ($value['has_child']) {
        return '<a href="'.route($route, ['parent' => $value['id']]).'">'.$value['name'].'</a>';
    } else {
        return $value['name'];
    }
}

function _menu_breadcrumb($menu, $parent)
{
    if (empty($menu[$parent])) {
        return [];
    }

    $data = $menu[$parent];

    $result = ($data->parent) ? _menu_breadcrumb($menu, $data->parent) : [];
    $result[] = ['url' => $data->url, 'slug' => $data->slug, 'target' => $data->target, 'name' => $data->name];

    return $result;
}

function _custom_breadcrumb($menu, $data)
{
    $breadcrumb = array_merge([['url' => '/', 'slug' => '/', 'target' => false, 'name' => 'Home']], _menu_breadcrumb(_menu_flat($menu), $data->menu_relation->parent));
    $breadcrumb = array_merge($breadcrumb, [['url' => false, 'slug' => false, 'target' => ($data->menu_relation->target) ? $data->menu_relation->target : false, 'name' => $data->menu_relation->translations[0]->name]]);

    $data = _custom_breadcrumb_v2($breadcrumb);

    return $data;
}

function _custom_breadcrumb_v2($data, $url = '')
{
    $result = [];
    foreach ($data as $key => $value) {
        if ($value['slug'] == 'javascript:void(0);') {
            $result[] = ['url' => 'javascript:void(0);', 'slug' => $value['slug'], 'target' => $value['target'], 'name' => $value['name']];
        } elseif ($value['slug'] == '/') {
            $result[] = ['url' => '/', 'slug' => $value['slug'], 'target' => $value['target'], 'name' => $value['name']];
        } elseif ($value['slug'] == false) {
            $result[] = ['url' => 'javascript:void(0);', 'slug' => $value['slug'], 'target' => $value['target'], 'name' => $value['name']];
        } else {
            $url .= $value['slug'];
            $result[] = ['url' => $url, 'slug' => $value['slug'], 'target' => $value['target'], 'name' => $value['name']];
        }
    }

    return $result;
}

function _menu_flat($menu)
{
    $result = [];

    foreach ($menu as $key => $value) {
        if (empty($value->children)) {
        } else {
            $result = $result + _menu_flat($value->children);
        }

        unset($value->children);

        $result[$value->id] = $value;
    }

    return $result;
}

function menu_table($array, $recursive, $data, $loops = 0)
{
    foreach ($array as $value) {
        $loops++;
        $separator = str_repeat('-', 5);
        $separator = $value['parent'] == 0 ? '' : str_repeat($separator, $recursive).' ';
        if ($recursive == 0) {
            $icon = '<i class="fa fa-bars"></i>';
        } else {
            $icon = '<i class="fa fa-angle-double-right"></i>';
        }
        $data[] = [
            'id' => $value['id'],
            'parent' => $value['parent'],
            'recursive' => $recursive,
            'name' => $separator.$icon.'&nbsp;'.$value['name'],
            'visibility' => $value['visibility'],
            'sort' => $value['sort'],
            'status' => $value['status'],
            'url' => ! empty($value['url']) ? $value['url'] : '',
            'has_child' => ! empty($value['children']) ? true : false,
        ];
        if (! empty($value['children'])) {
            $data = menu_table($value['children'], $recursive + 1, $data, $loops);
        }
    }

    return $data;
}

function menu_table_tab($array, $recursive, $data, $loops = 0)
{
    foreach ($array as $value) {
        $loops++;
        $separator = str_repeat('-', 5);
        $separator = $value['parent'] == 0 ? '' : str_repeat($separator, $recursive).' ';
        $data[] = [
            'id' => $value['id'],
            'parent' => $value['parent'],
            'name' => $value['name'],
            'visibility' => $value['visibility'],
            'description' => $value['description'],
            'sort' => $value['sort'],
            'status' => $value['status'],
            'url' => ! empty($value['url']) ? $value['url'] : '',
            'has_child' => ! empty($value['children']) ? true : false,
        ];
        if (! empty($value['children'])) {
            $data = menu_table_tab($value['children'], $recursive + 1, $data, $loops);
        }
    }

    return $data;
}

function menu_table_space($array, $recursive, $data, $loops = 0)
{
    foreach ($array as $value) {
        $loops++;
        $separator = str_repeat('&nbsp;', 5);
        $separator = $value['parent'] == 0 ? '' : str_repeat($separator, $recursive).' ';
        if ($recursive == 0) {
            $icon = '-';
        } else {
            $icon = '<svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M10 7L15 12L10 17" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>';
        }
        $data[] = [
            'id' => $value['id'],
            'parent' => $value['parent'],
            'recursive' => $recursive,
            'name' => $separator.$icon.'&nbsp;'.$value['name'],
            'visibility' => $value['visibility'],
            'sort' => $value['sort'],
            'status' => $value['status'],
            'url' => ! empty($value['url']) ? $value['url'] : '',
            'has_child' => ! empty($value['children']) ? true : false,
        ];
        if (! empty($value['children'])) {
            $data = menu_table_space($value['children'], $recursive + 1, $data, $loops);
        }
    }

    return $data;
}

function search_data($array, $key, $value)
{
    $results = [];

    if (isset($array)) {
        foreach ($array as $keys => $values) {
            if ($values[$key] == $value) {
                $results = $values;
                break;
            }
        }
    }

    return $results;
}
function description($value)
{
    $data = str_replace('src="/'.env('APP_URL'), 'src="/', $value);
    $data = str_replace('src="/', 'src="'.env('APP_URL').'/', $data);
    $data = str_replace('href="/storage/documents/1/', 'href="'.env('APP_URL').'/storage/documents/1/', $data);

    return $data;
}

function formatDateIndonesia($timestamp = null)
{
    $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    $timezone = new DateTimeZone('Asia/Jakarta');

    // Jika timestamp tidak disediakan, gunakan waktu sekarang
    if ($timestamp === null) {
        $datetime = new DateTime('now', $timezone);
    } else {
        $datetime = new DateTime($timestamp, $timezone);
    }

    $formattedDate = $hari[(int) $datetime->format('w')].', '.
                     $datetime->format('j').' '.
                     $bulan[(int) $datetime->format('n')].' '.
                     $datetime->format('Y').' '.
                     $datetime->format('h:i:s A').' ('.
                     $datetime->format('e').')';

    return $formattedDate;
}

function meta($data, $string = 'title')
{
    if (! $data) {
        return false;
    }
    if ($string == 'title') {
        return (! empty($data->meta_title)) ? $data->meta_title : (isset($data->name) ? $data->name : false);
    } elseif ($string == 'description') {
        return (! empty($data->meta_description)) ? $data->meta_description : ((isset($data->description)) ? strip_tags($data->description) : false);
    } elseif ($string == 'keywords') {
        return (! empty($data->meta_keyword)) ? $data->meta_keyword : (isset($data->name) ? $data->name : false);
    }

    return false;
}

function generateBreadcrumbArray($url, $languages)
{

    $segments = explode('/', trim($url, '/'));
    $result = [];
    $currentUrl = '';

    $result[] = [
        'url' => '/',
        'slug' => '/',
        'target' => '_self',
        'name' => $languages == 'en' ? 'Home' : 'Beranda',
    ];
    foreach ($segments as $segment) {
        $currentUrl .= '/'.$segment;

        if ($segment == 'content' || in_array($segment, code_lang())) {
            continue;
        }

        $name = ucwords(str_replace('-', ' ', $segment));
        $name = preg_replace('/[0-9]+/', '', $name);

        $result[] = [
            'url' => $currentUrl,
            'slug' => $segment,
            'target' => '_self',
            'name' => $name,
        ];
    }

    return $result;
}

function reference_format($input)
{
    return substr($input, 0, 6).'-'.substr($input, 6, 2).'-'.substr($input, 8);
}

function reference_format_tender($input)
{
    return substr($input, 0, 4).'-'.substr($input, 4, 2).'-'.substr($input, 6);
}

function shortCode($text, $data)
{
    $zpreg = preg_match_all('#\[\[([a-zA-Z0-9]+)\]\]#', $text, $matches);
    if ($zpreg > 0) {
        $newtext = preg_replace_callback(
            '#\[\[([a-zA-Z0-9]+)\]\]#',
            function ($matches) use ($data) {
                $result = $matches[1];

                return $data->$result;
            },
            $text
        );

        return $newtext;
    }

    return $text;
}

function encryptAES($message)
{
    if (OPENSSL_VERSION_NUMBER <= 268443727) {
        throw new RuntimeException('OpenSSL Version too old, vulnerability to Heartbleed');
    }

    $iv_size = openssl_cipher_iv_length(AES_METHOD);
    $iv = openssl_random_pseudo_bytes($iv_size);
    $ciphertext = openssl_encrypt($message, AES_METHOD, env('APP_AES_PASSWORD'), OPENSSL_RAW_DATA, $iv);
    $ciphertext_hex = bin2hex($ciphertext);
    $iv_hex = bin2hex($iv);

    return "$iv_hex:$ciphertext_hex";
}
function decryptAES($ciphered)
{
    $iv_size = openssl_cipher_iv_length(AES_METHOD);
    $data = explode(':', $ciphered);
    $iv = hex2bin($data[0]);
    $ciphertext = hex2bin($data[1]);

    return openssl_decrypt($ciphertext, AES_METHOD, env('APP_AES_PASSWORD'), OPENSSL_RAW_DATA, $iv);
}
