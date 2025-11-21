<!-- ========== Left Sidebar Start ========== -->
<div class="leftside-menu">

    <!-- Brand Logo Light -->
    <a href="javascript:" class="logo logo-light">
        <span class="logo-lg">
            <img src="assetss/images/logo.svg" alt="logo">
        </span>
        <span class="logo-sm">
            <img src="assetss/images/logo-dark-sm.svg" alt="small logo">
        </span>
    </a>

    <!-- Brand Logo Dark -->
    <a href="javascript:" class="logo logo-dark">
        <span class="logo-lg">
            <img src="assetss/images/logo-dark.svg" alt="dark logo">
        </span>
        <span class="logo-sm">
            <img src="assetss/images/logo-dark-sm.svg" alt="small logo">
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" title="Show Full Sidebar">
        <i class="ri-checkbox-blank-circle-line align-middle"></i>
    </div>

    <!-- Full Sidebar Menu Close Button -->
    <div class="button-close-fullsidebar">
        <i class="ri-close-fill align-middle"></i>
    </div>

    <!-- Sidebar -left -->
    <div class="h-100" id="leftside-menu-container" data-simplebar>
        <!-- Leftbar User -->
        <div class="leftbar-user">
            <a href="javascript:">
                <img src="assetss/images/users/avatar-1.jpg" alt="user-image" height="42"
                    class="rounded-circle shadow-sm">
                <span class="leftbar-user-name mt-2">Dominic Keller</span>
            </a>
        </div>

        <!--- Sidemenu -->
        <ul class="side-nav">

            <!-- <li class="side-nav-title">Navigation</li> -->

            <li class="side-nav-item">
                <a href="dashboard.php" class="side-nav-link">
                    <i class="ri-dashboard-line"></i>
                    <span> Dashboard </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarDropdown" aria-expanded="false" aria-controls="sidebarDropdown" class="side-nav-link">
                    <i class="ri-settings-3-line"></i>
                    <span> Configurations </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarDropdown">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="modules.php">Modules</a>
                        </li>
                        <li>
                            <a href="ui-buttons.php">Roles</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarTasks" aria-expanded="false" aria-controls="sidebarTasks"
                    class="side-nav-link">
                    <i class="ri-lock-password-line"></i>
                    <span> Master Settings </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarTasks">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="javascript:">Users</a>
                        </li>
                        <li>
                            <a href="javascript:">Assign Roles</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarProductCatalogue" aria-expanded="false"
                    aria-controls="sidebarProductCatalogue" class="side-nav-link">
                    <i class="ri-book-read-line"></i>
                    <span>Product Catalogue </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarProductCatalogue">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="javascript:">Product Categories</a>
                        </li>
                        <li>
                            <a href="javascript:">Product Options</a>
                        </li>
                        <li>
                            <a href="javascript:">Products</a>
                        </li>
                        <li>
                            <a href="javascript:">Inventory</a>
                        </li>
                        <li>
                            <a href="javascript:">Product FAQs</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarOrders" aria-expanded="false" aria-controls="sidebarOrders"
                    class="side-nav-link">
                    <i class="ri-file-list-3-line"></i>
                    <span>Orders Management</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarOrders">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="javascript:">Customers</a>
                        </li>
                        <li>
                            <a href="javascript:">Orders</a>
                        </li>
                        <li>
                            <a href="javascript:">Return Request</a>
                        </li>
                        <li>
                            <a href="javascript:">Order Status</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarReports" aria-expanded="false" aria-controls="sidebarReports"
                    class="side-nav-link">
                    <i class="ri-survey-line"></i>
                    <span>Reports</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarReports">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="javascript:">Segments</a>
                        </li>
                        <li>
                            <a href="javascript:">Sales Reports</a>
                        </li>
                        <li>
                            <a href="javascript:">Inventory Reports</a>
                        </li>
                        <li>
                            <a href="javascript:">Performance Matrix</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarPromotions" aria-expanded="false"
                    aria-controls="sidebarPromotions" class="side-nav-link">
                    <i class="ri-gift-2-line"></i>
                    <span>Promotions</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarPromotions">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="javascript:">Coupons</a>
                        </li>
                        <li>
                            <a href="javascript:">Promotions</a>
                        </li>
                        <li>
                            <a href="javascript:">Newsletters</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarMultiDropdown" aria-expanded="false"
                    aria-controls="sidebarMultiDropdown" class="side-nav-link">
                    <i class="ri-computer-line"></i>
                    <span>Content Management </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarMultiDropdown">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="javascript:">CMS Pages</a>
                        </li>
                        <li>
                            <a href="javascript:">Banners</a>
                        </li>
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarPagesAuth" aria-expanded="false"
                                aria-controls="sidebarPagesAuth">
                                <span> Media Gallery </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarPagesAuth">
                                <ul class="side-nav-third-level">
                                    <li>
                                        <a href="javascript:">Upload</a>
                                    </li>
                                    <li>
                                        <a href="javascript:">YouTube Links</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
        <!--- End Sidemenu -->

        <div class="clearfix"></div>
    </div>
</div>
<!-- ========== Left Sidebar End ========== -->