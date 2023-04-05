<!-- sidebar menu -->
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
        <h3>General</h3>
        <ul class="nav side-menu">
            <li><a><i class="fa fa-user"></i> Home <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{url('/admin')}}">Dashboard</a></li>
                </ul>
            </li>
            <li><a><i class="fa fa-edit"></i>Main Content<span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">

                </ul>
            </li>

            <li><a><i class="fa fa-home"></i>Home Page<span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{route('admin.banners.index')}}">Slider and Banners</a></li>
				</ul>
            </li>

			<li><a><i class="fa fa-file-powerpoint-o"></i>Static Pages<span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{route('admin.groups.index')}}">Groups</a></li>
                    <li><a href="{{route('admin.review')}}">Products Reviews</a></li>
                    <li><a href="{{route('admin.coupons.index')}}">Coupons</a></li>
				</ul>
            </li>

			<li><a><i class="fa fa-desktop"></i>Dynamic Pages<span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{route('admin.products.index')}}">Products</a></li>
                    <li><a href="{{route('admin.brands.index')}}">Brands</a></li>
                    <li><a href="{{route('admin.categories.index')}}">Categories</a></li>
                    <li><a href="{{route('admin.tags.index')}}">Tags</a></li>
                    <li><a href="{{route('admin.applications.index')}}">Applications</a></li>
                    <!--  -->
                </ul>
            </li>
        
			<li><a><i class="fa fa-envelope"></i> رسائل من العملاء <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{route('feedbacks.index')}}">رسائل الموقع</a></li>
                </ul>
            </li>
       

            @can('users_manage')
            <li><a><i class="fa fa-users"></i>Users<span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{ route('admin.users.index') }}">Users</a></li>
                    <li><a href="{{ route('admin.roles.index') }}">Role</a></li>
                    <li><a href="{{ route('admin.permissions.index') }}">Permissions</a></li>
                </ul>
            </li>
            @endcan
        </ul>
    </div>
</div>
<!-- /sidebar menu -->
<!-- /menu footer buttons -->
<div class="sidebar-footer hidden-small">
    <a data-toggle="tooltip" data-placement="top" title="Settings">
        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="Lock">
        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="Logout" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();">
        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
    </a>
</div>
<!-- /menu footer buttons -->
</div>
</div>
