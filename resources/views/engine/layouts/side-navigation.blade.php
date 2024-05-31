<?php
$segment_2 = Request::segment(2);
$segment_3 = Request::segment(3);
$type = Request::input('type');
$permission =  session('permission');

$permission_category = session('permission_category');

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
$article_news_menu = \App\Models\WebContent::with(['translations' => function ($q) {
    $q->where('language_id', 1);
}])
->orderBy('sort', 'asc')
->where('status', 1)
->get();

$category = request()->category;

$visibility = false;
if($category){
    $visibility = \App\Models\WebContent::with(['translations' => function ($q) {
        $q->where('language_id', 1);
    }])
    ->orderBy('sort', 'asc')
    ->where('id', $category)
    ->first('visibility');
}

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
           

            @if ($post)
                @if (in_array('category/article', $permission))
                    <li class="dropdown 
                    @routeis(['category.article.*', 'category.article'])
                        @if (request()->component == 'category') active @endif 
                    @endrouteis
                    @if($visibility && in_array($visibility->visibility, _custom_visibility_menu()))
                        @if (request()->component == 'category') active @endif 
                    @endif
                    @routeis(['article.*', 'article'])  active @endrouteis
                    ">
                        <a href="#" class="nav-link has-dropdown"><i class="fas fa-window-restore"></i><span>Category</span></a>
                        <ul class="dropdown-menu" 
                        
                        @routeis(['category.article.*', 'category.article'])
                            @if (request()->component == 'category') style="display: block;" @endif 
                        @endrouteis
                        @if($visibility && in_array($visibility->visibility, _custom_visibility_menu()))
                            @if (request()->component == 'category') style="display: block;" @endif 
                        @endif
                        @routeis(['article.*', 'article'])  style="display:block"  @endrouteis
                        
                        >
                            <li 
                                    @routeis(['category.article.*', 'category.article'])
                                        @if (request()->component == 'category') class="active" @endif 
                                    @endrouteis
                                    @if($visibility && in_array($visibility->visibility, _custom_visibility_menu()))
                                        @if (request()->component == 'category') class="active" @endif 
                                    @endif
                                >
                                <a class="nav-link" href="{{ route('category.article', ['component' => 'category']) }}">  <i class="fas fa-solid fa-layer-group"></i>List Category</a>
                            </li>
                            @foreach ($article_news_menu as $item)
                                @if (! in_array($item->id, (array) $permission_category)) 
                                    @continue
                                @endif
                                
                                @if( in_array($item->visibility, _custom_visibility_menu()))
                                    <li @routeis(['article.*', 'article']) @if (request()->category == $item->id) class="active" @endif @endrouteis>
                                        <a class="nav-link" href="{{ route('article',['category' => $item->id]) }}"><i class="fas fa-stream"></i>
                                            {{ \Illuminate\Support\Str::limit(\Illuminate\Support\Str::title(strip_tags($item?->translations?->first()?->name)), 15, '...')}}
            
                                        </a>
                                    </li>
                                @endif  
                            @endforeach

                        </ul>
                    </li>


                  
                   
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
