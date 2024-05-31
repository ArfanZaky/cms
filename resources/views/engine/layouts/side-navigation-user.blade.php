<?php
$segment_2 = Request::segment(2);
$segment_3 = Request::segment(3);
$type = Request::input('type');
$permission =  session('permission');

$permission_content = session('permission_content');

$pages = false;
$users = false;
$post = false;
$form = false;
$menu_page = ['page/generic', 'page/section'];
$menu_post = ['article'];
$menu_user = ['user', 'role', 'permission'];
$menu_form = [ 'form/contact'];
foreach ($permission as $key => $value) {
    if (in_array($value, $menu_page)) {
        $pages = true;
    }
    if (in_array($value, $menu_post)) {
        $post = true;
    }
    if (in_array($value, $menu_user)) {
        $users = true;
    }
    if (in_array($value, $menu_form)) {
        $form = true;
    }
}

$content = \App\Models\WebContent::with(['translations' => function ($q) {
    $q->where('language_id', 2);
}])
    ->orderBy('sort', 'asc')
    ->get();
if (request()->parent || request()->content) {
    if (! in_array(request()->parent, $permission_content)) {
        return redirect()->route('dashboard')->with('error', 'permission denied, please contact your administrator');
    }
    if (! in_array(request()->content, $permission_content)) {
        return redirect()->route('dashboard')->with('error', 'permission denied, please contact your administrator');
    }
}

$data = collect($content)->filter(function ($item) use ($permission_content) {
        return in_array($item->id, $permission_content);
    })->map(function ($item) {
        $breadcrumb = \\App\Helper\Helper::_content_slug_map($item, 1);
        $slug = '';
        collect($breadcrumb)->map(function ($item, $key) use (&$slug) {
            $slug .= $item['slug'].'/';
        })->toArray();
        $url = '/content/'.$slug;
        $item->url = $url;
        return $item;
});

$data_tree = [];
foreach ($data as $key => $value) {
    $data_tree[] = [
        'id' => $value->id,
        'parent' => $value->parent,
        'children' => [],
        'name' => $value->translations[0]->name,
        'visibility' => $value->visibility,
        'sort' => $value->sort,
        'status' => $value->status,
        'url' => $value->url,
    ];
}
$data_tree = \\App\Helper\Helper::tree($data_tree);
$menu_table = menu_table($data_tree, 0, $data = []);


?>

<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('engine') }}">{{\App\Helper\Helper::_setting_code('name_company')}}</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="">N</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Menu</li>
            <li class="@routeis('dashboard') active @endrouteis" >
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="far fa-square"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            @if (in_array('menu', $permission))
                <li class="@routeis(['menu.*', 'menu']) active @endrouteis" >
                    <a class="nav-link" href="{{ route('menu') }}">
                        <i class="fas fa-sitemap"></i>
                        <span>Menu</span>
                    </a>
                </li>
            @endif
            @if ($pages)
                <li class="dropdown @routeis(['page.*', 'page','template.*', 'template']) active @endrouteis">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-window-restore"></i><span>Page</span></a>
                    <ul class="dropdown-menu" @routeis(['page.*', 'page','template.*', 'template']) style="display: block;" @endrouteis >
                        @if (in_array('page/section', $permission))
                        <li class="<?php if ($segment_3 == 'section' && !isset($type)) {echo 'active';} ?>"><a class="nav-link" href="{{ route('page.section') }}">  <i class="fas fa-window-maximize"></i>Page Menu</a></li>
                        @endif
                        @if (in_array('page/generic', $permission))
                        <li class="<?php if ($segment_3 == 'generic' && !isset($type)) {echo 'active';} ?>"><a class="nav-link" href="{{ route('page.generic') }}">  <i class="fas fa-window-maximize"></i>Page Builder</a></li>
                        @endif
                        @if (in_array('page/generic', $permission))
                            <li class="@routeis(['template.*','template']) active @endrouteis" >
                                <a class="nav-link" href="{{ route('template') }}">
                                    <i class="fas fa-building"></i>
                                    <span>Template</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if (in_array('content/article', $permission))
                <li
                @routeis(['content.article.*', 'content.article'])
                    @if (request()->component == 'product') class="active" @endif 
                @endrouteis
                >
                    <a class="nav-link" href="{{ route('content.article', ['component' => 'product']) }}">  <i class="fas fa-solid fa-cubes"></i>Product</a>
                </li>
                @foreach($menu_table as $key => $item)
                    <li class="dropdown">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-window-restore"></i><span>content</span></a>
                        <ul class="dropdown-menu" 
                            @routeis(['content.article.*', 'content.article'])
                                @if (request()->component == 'content') style="display: block;" @endif 
                            @endrouteis
                            @routeis(['article.*', 'article'])  style="display:block"  @endrouteis>
                            
                            <li 
                                    @routeis(['content.article.*', 'content.article'])
                                        @if (request()->component == 'content') class="active" @endif 
                                    @endrouteis
                                >
                                <a class="nav-link" href="{{ route('content.article', ['component' => 'content']) }}">  <i class="fas fa-solid fa-layer-group"></i>List content</a>
                            </li>

                        </ul>
                    </li>
                @endforeach
               
            @endif
            @if (in_array('kurs', $permission))
                <li class="@routeis('kurs') active @endrouteis" >
                    <a class="nav-link" href="{{ route('kurs') }}">
                        <i class="fas fa-solid fa-money-check"></i>
                        <span>Kurs Document</span>
                    </a>
                </li>
            @endif

            @if (in_array('content/chatbot', $permission))
                <li
                    @routeis(['content.chatbot.*', 'content.chatbot'])
                        class="active"
                    @endrouteis
                    ><a class="nav-link" href="{{ route('content.chatbot') }}">  <i class="fas fa-solid fa-robot"></i>Chatbot Option</a></li>
            @endif

            
            @if (in_array('form/contact', $permission))
                <li class="dropdown @routeis(['form.contact.*', 'form.contact', 'form.email'])  active @endrouteis">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-window-restore"></i><span>Form</span></a>
                    <ul class="dropdown-menu" @routeis(['form.contact.*', 'form.contact', 'form.email'])  style="display: block;" @endrouteis >
                       
                        <li class="@routeis(['form.contact.*', 'form.contact']) @if (request()->type == 'application') active @endif @endrouteis"> 
                            <a class="nav-link" href="{{ route('form.contact', ['type' => 'application']) }}">
                                <i class="fas fa-file"></i>Application Form
                            </a>
                        </li>   <!-- Application -->
                            
                        <li class="@routeis(['form.contact.*', 'form.contact']) @if (request()->type == 'complaint') active @endif @endrouteis"> 
                            <a class="nav-link" href="{{ route('form.contact', ['type' => 'complaint']) }}">
                                <i class="fas fa-file"></i>Complaint Form
                            </a>
                        </li>   <!-- Complaint -->

                        <li class="@routeis(['form.email.*', 'form.email']) active @endrouteis"> 
                            <a class="nav-link" href="{{ route('form.email') }}">
                                <i class="fas fa-envelope"></i>Email Management
                            </a>
                        </li>   <!-- email -->
                    </ul>
                </li>
            @endif

            @if (in_array('user', $permission) || in_array('role', $permission) || in_array('permission', $permission) || in_array('settings', $permission) || in_array('wordings', $permission))
                <li class="dropdown  @routeis(['wordings.*', 'wordings','user.*', 'user','role.*', 'role','permission.*', 'permission','settings.*', 'settings']) active @endrouteis">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-shield-alt"></i><span>Administrator</span></a>

                    <ul class="dropdown-menu" @routeis(['wordings.*', 'wordings','user.*', 'user','role.*', 'role','permission.*', 'permission','settings.*', 'settings']) style="display: block;" @endrouteis>
                        @if (in_array('wordings', $permission))
                            <li class="@routeis(['wordings.*', 'wordings']) active @endrouteis" >
                                <a class="nav-link" href="{{ route('wordings') }}">
                                    <i class="fas fa-language"></i>
                                    <span>Wordings</span>
                                </a>
                            </li>
                        @endif

                    @if ($users)
                        <li class="dropdown @routeis(['user.*', 'user','role.*', 'role','permission.*', 'permission']) active @endrouteis">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i><span>User</span></a>
                            <ul class="dropdown-menu" @routeis(['user.*', 'user','role.*', 'role','permission.*', 'permission']) style="display: block;" @endrouteis >
                                @if (in_array('user', $permission))
                                    <li class="@routeis(['user.*', 'user']) active @endrouteis"><a class="nav-link" href="{{ route('user') }}">  <i class="fas fa-user-alt"></i>User</a></li>
                                @endif
                                @if (in_array('role', $permission))
                                    <li class="@routeis(['role.*', 'role']) active @endrouteis"> <a class="nav-link" href="{{ route('role') }}"> <i class="fas fa-user-shield"></i>Role</a></li>
                                @endif
                                @if (in_array('permission', $permission))
                                    <li class="@routeis(['permission.*', 'permission']) active @endrouteis"><a class="nav-link" href="{{ route('permission') }}"> <i class="fas fa-user-slash"></i>Permission</a></li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        @if (in_array('settings', $permission))
                            <li class="@routeis(['settings.*', 'settings']) active @endrouteis" >
                                <a class="nav-link" href="{{ route('settings') }}">
                                    <i class="fas fa-cogs"></i>
                                    <span>Settings</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

        </ul>
        

        <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
        </div>
    </aside>
</div>
