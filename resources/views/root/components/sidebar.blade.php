<!-- BEGIN: Left Aside -->
<button class="m-aside-left-close m-aside-left-close--skin-light" id="m_aside_left_close_btn">
    <i class="la la-close"></i></button>

<div id="m_aside_left" class="m-grid__item m-aside-left ">
    <!-- BEGIN: Aside Menu -->
    <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-light m-aside-menu--submenu-skin-light" data-menu-vertical="true" data-menu-scrollable="false" data-menu-dropdown-timeout="500">
        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow">
            {{ $slot }}

            <!-- Back -->
            <li class="m-menu__item" aria-haspopup="true">
                <a href="{{ URL::previous() }}" class="m-menu__link">
                    <i class="m-menu__link-icon la la-arrow-circle-left"></i>
                    <span class="m-menu__link-title">
                        <span class="m-menu__link-text">Back</span>
                    </span>
                </a>
            </li>
        </ul>
    </div>
</div>