 <div class="col-md-3 left_col">
     <div class="left_col scroll-view">
         <div class="navbar nav_title" style="border: 0;">
             <a href="{{ route('admin.dashboard') }}" class="site_title"><i class="fa fa-shopping-bag"></i>
                 <span>ClotheStore!</span></a>
         </div>
         <!-- menu profile quick info -->
         <div class="profile clearfix">
             <div class="profile_pic">
                 <img src="{{ asset('assets/clients/img/avt.png') }}" alt="" class="img-circle img-fluid"
                     style="object-fit: cover; width: 100px; height: 100px;">
             </div>
             <div class="profile_info">
                 <span>Welcome,</span>
                 <h2>{{ Auth::guard('admin')->user()->name ?? 'Guest' }}</h2>
             </div>
         </div>
         <!-- /menu profile quick info -->

         <br />

         <!-- sidebar menu -->
         <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
             <div class="menu_section">
                 <h3>General</h3>
                 <ul class="nav side-menu">
                     <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i> Home</a>
                     </li>
                     <li>
                         <a href="{{ route('admin.users.index') }}">
                             <i class="fa fa-users"></i> Quản lý người dùng
                         </a>
                     </li>
                     <li>
                         <a>
                             <i class="fa fa-list"></i> Quản lý danh mục
                             <span class="fa fa-chevron-down"></span>
                         </a>
                         <ul class="nav child_menu">
                             <li><a href="{{ route('admin.categories.add') }}">Thêm danh mục</a></li>
                             <li><a href="{{ route('admin.categories.index') }}">Danh sách danh mục</a></li>
                             <li><a href="{{ route('admin.categories.trash') }}">Thùng rác</a></li>
                         </ul>
                     </li>
                     <li>
                         <a>
                             <i class="fa fa-cube"></i> Quản lý sản phẩm
                             <span class="fa fa-chevron-down"></span>
                         </a>
                         <ul class="nav child_menu">
                             <li><a href="{{ route('admin.products.add') }}">Thêm sản phẩm</a></li>

                             <li><a href="{{ route('admin.products.index') }}">Danh sách sản phẩm</a></li>

                             <li><a href="{{ route('admin.products.trash') }}">Thùng rác</a></li>
                         </ul>
                     </li>
                     <li>
                         <a>
                             <i class="fa fa-arrows-v"></i> Quản lý kích thước
                             <span class="fa fa-chevron-down"></span>
                         </a>
                         <ul class="nav child_menu">
                             <li><a href="{{ route('admin.sizes.add') }}">Thêm kích thước</a></li>
                             <li><a href="{{ route('admin.sizes.index') }}">Danh sách kích thước</a></li>
                         </ul>
                     </li>
                     <li>
                         <a>
                             <i class="fa fa-paint-brush"></i> Quản lý màu sắc
                             <span class="fa fa-chevron-down"></span>
                         </a>
                         <ul class="nav child_menu">
                             <li><a href="{{ route('admin.colors.add') }}">Thêm màu sắc</a></li>
                             <li><a href="{{ route('admin.colors.index') }}">Danh sách màu sắc</a></li>
                         </ul>
                     </li>

                     <li>
                         <a>
                             <i class="fa fa-ticket"></i> Quản lý mã giảm giá
                             <span class="fa fa-chevron-down"></span>
                         </a>
                         <ul class="nav child_menu">
                             <li><a href="{{ route('admin.coupons.add') }}">Thêm mã giảm giá</a></li>
                             <li><a href="{{ route('admin.coupons.index') }}">Danh sách mã giảm giá</a></li>
                         </ul>
                     </li>
                     <li>
                         <a href="{{ route('admin.orders.index') }}">
                             <i class="fa fa-shopping-cart"></i> Quản lý đơn hàng
                         </a>
                     </li>
                     <li>
                         <a href="{{ route('admin.refunds.index') }}">
                             <i class="fa fa-money"></i> Hoàn tiền đơn hàng
                         </a>
                     </li>
                     <li>
                         <a>
                             <i class="fa fa-comments"></i> Quản lý bình luận
                             <span class="fa fa-chevron-down"></span>
                         </a>
                         <ul class="nav child_menu">
                             <li><a href="{{ route('admin.reviews.index') }}">Danh sách bình luận</a></li>
                             <li><a href="{{ route('admin.reviews.logs') }}">Lịch sử xóa bình luận</a></li>
                         </ul>
                     </li>
                     <li class="{{ request()->routeIs('admin.flash_sales.*') ? 'active' : '' }}">
                         <a href="{{ route('admin.flash_sales.index') }}">
                             <i class="fa fa-bolt"></i> Flash Sale
                         </a>
                     </li>


                     <li>
                         <a href="{{ route('admin.contacts.index') }}">
                             <i class="fa fa-users"></i> Quản lý Liên hệ
                         </a>
                     </li>
                     <li>
                         <a href="{{ route('admin.banners.index') }}">
                             <i class="fa fa-image"></i> Quản lý banner
                         </a>
                     </li>


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
             <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
                 <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
             </a>
         </div>
         <!-- /menu footer buttons -->
     </div>
 </div>
