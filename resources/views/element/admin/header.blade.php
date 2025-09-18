<nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none"> 
  	<a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)"> <i class="icon-base ti tabler-menu-2 icon-md"></i> </a> 
  </div>
  <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
    
    <!-- Search -->
    
    <?php /*?><div class="navbar-nav align-items-center">

                <div class="nav-item navbar-search-wrapper px-md-0 px-2 mb-0">

                  <a class="nav-item nav-link search-toggler d-flex align-items-center px-0" href="javascript:void(0);">

                    <span class="d-inline-block text-body-secondary fw-normal" id="autocomplete"></span>

                  </a>

                </div>

              </div><?php */?>
    
    <!-- /Search -->
    
    <ul class="navbar-nav flex-row align-items-center ms-md-auto">
      
      <!-- User -->
      
      <li class="nav-item navbar-dropdown dropdown-user dropdown"> 
      	<a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
        	<div class="avatar avatar-online"> <img src="{{ asset('public/admin/img/avatars/1.png') }}" alt class="rounded-circle" /> </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li> 
          <a class="dropdown-item mt-0" href="{{route('admin.dashboard')}}">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0 me-2">
                <div class="avatar avatar-online"> <img src="{{ asset('public/admin/img/avatars/1.png') }}" alt class="rounded-circle" /> </div>
              </div>
              <div class="flex-grow-1">
                <h6 class="mb-0">Admin</h6>
                <small class="text-body-secondary">Admin</small> </div>
            </div>
            </a> </li>
          <li>
            <div class="dropdown-divider my-1 mx-n2"></div>
          </li>
          <li> <a class="dropdown-item" href="{{route('admin.profile')}}"> <i class="icon-base ti tabler-user me-3 icon-md"></i><span class="align-middle">My Profile</span> </a> </li>
          <li>
            <div class="dropdown-divider my-1 mx-n2"></div>
          </li>
          <li> <a class="dropdown-item" href="{{route('admin.change-password')}}"> <i class="icon-base ti tabler-settings me-3 icon-md"></i><span class="align-middle">Change Password</span> </a> </li>
          <li>
            <div class="dropdown-divider my-1 mx-n2"></div>
          </li>
          <li>
            <div class="d-grid px-2 pt-2 pb-1"> <a class="btn btn-sm btn-danger d-flex" href="{{route('admin.logout')}}"> <small class="align-middle">Logout</small> <i class="icon-base ti tabler-logout ms-2 icon-14px"></i> </a> </div>
          </li>
        </ul>
      </li>
      
      <!--/ User -->
      
    </ul>
  </div>
</nav>