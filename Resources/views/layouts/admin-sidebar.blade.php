<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
        <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
        <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
        <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
            <li class="heading">
                <h3 class="sidebar-heading">
                    This is a Title
                </h3>
            </li>
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler">
                    <span></span>
                </div>
            </li>
            <!-- END SIDEBAR TOGGLER BUTTON -->
            <li class="heading">
                <h3 class="uppercase">Features</h3>
            </li>
            <li class="nav-item start ">
                <a href="{{ url('dashboard') }}" class="nav-link">
                    <i class="icon-home"></i>
                    <span class="title">主頁</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link">
                    <i class="icon-earphones-alt"></i>
                    <span class="title">管理介面</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="javascript:;" class="nav-link">
                    <i class="icon-bar-chart"></i>
                    <span class="title">統計報表</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link nav-toggle">
                    <i class="icon-wallet"></i>
                    <span class="title">資料管理</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item">
                        <a href="" class="nav-link ">
                            <span class="title">裝修材料管理</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link ">
                            <span class="title">門窗資料管理</span>
                        </a>
                    </li>
                </ul>
            </li>
            
            <li class="nav-item  ">
                <a href="" class="nav-link">
                    <i class="icon-user"></i>
                    <span class="title">使用者管理</span>
                </a>
            </li>
        </ul>
        <!-- END SIDEBAR MENU -->
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>