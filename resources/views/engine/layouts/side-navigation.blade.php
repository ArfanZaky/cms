<?php
$segment_2 = Request::segment(2);
$segment_3 = Request::segment(3);
$type = Request::input('type');
$permission =  session('permission');


$users = false;
$form = false;
$menu_user = ['user', 'role', 'permission'];
$menu_form = [ 'form/contact'];
foreach ($permission as $key => $value) {
    if (in_array($value, $menu_user)) {
        $users = true;
    }
    if (in_array($value, $menu_form)) {
        $form = true;
    }
}

$data = session('permission_content');

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

            @if (in_array('content/article', $permission))
                <li class="@routeis(['content.article.*', 'content.article'])
                    @if (request()->is_menu != 1)
                        active
                    @endif
                @endrouteis" >
                    <a class="nav-link" href="{{ route('content.article', ['component' => 'content']) }}">
                        <i class="fas fa-window-restore"></i>
                        <span>Content</span>
                    </a>
                </li>
            @endif

            @if (in_array('content/article', $permission))
                @if (!empty($data))
                    @foreach($data as $key => $item)
                        <li class="dropdown @if (request()->edit == $item['id'] || request()->list == $item['id'])  active @endif">
                            <a href="#" class="nav-link has-dropdown"><i class="fas fa-window-restore"></i><span>
                                {{ \Illuminate\Support\Str::limit(\Illuminate\Support\Str::title(strip_tags($item['rname'])), 15, '...')}}</span>
                            </a>
                            <ul class="dropdown-menu" @if (request()->edit == $item['id'] || request()->list == $item['id']
                                ) style="display:block" @endif
                                >
                                <li
                                        @routeis(['content.article.edit', 'content.article.edit.*'])
                                            @if (request()->edit == $item['id']) class="active" @endif
                                        @endrouteis
                                    >
                                    <a class="nav-link" href="{{  route('content.article.edit', [$item['id'], 'edit' => $item['id'], 'is_menu' => 1]) }}">
                                        <i class="fas fa-solid fa-edit"></i>
                                        Edit
                                    </a>
                                </li>

                                <li
                                    @routeis(['content.article.*', 'content.article', 'article.*', 'article'])
                                        @if (request()->list == $item['id']) class="active" @endif
                                    @endrouteis
                                    >
                                    <a class="nav-link" href="{{ route('content.article', ['parent' => $item['id'], 'list' => $item['id'], 'is_menu' => 1]) }}">
                                        <i class="fas fa-solid fa-layer-group"></i>List
                                    </a>
                                </li>
                                <li >
                                    <a class="nav-link" href="{{ \App\Helper\Helper::_view_page() . $item['url'] }}" target="_blank" >
                                        <i class="fas fa-solid fa-eye"></i>View
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endforeach
                @endif
            @endif


            @if (in_array('form/contact', $permission))
                <li class="dropdown @routeis(['form.contact.*', 'form.contact', 'form.email'])  active @endrouteis">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-window-restore"></i><span>Form</span></a>
                    <ul class="dropdown-menu" @routeis(['form.contact.*', 'form.contact', 'form.email'])  style="display: block;" @endrouteis >
                        <li class="@routeis(['form.contact.*', 'form.contact']) @endrouteis">
                            <a class="nav-link" href="{{ route('form.contact') }}">
                                <i class="fas fa-file"></i>Contact Form
                            </a>
                        </li>
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
