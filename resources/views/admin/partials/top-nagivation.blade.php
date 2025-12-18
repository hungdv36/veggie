<div class="top_nav">
    <div class="nav_menu">
        <div class="nav toggle">
            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>
        <nav class="nav navbar-nav">
            <ul class="navbar-right">
                <li class="nav-item dropdown">
                    <a href="javascript:;" class="user-profile dropdown-toggle" id="navbarDropdown" data-toggle="dropdown"
                        aria-expanded="false">
                        <img src="{{ asset('assets/clients/img/avt.png') }}" class="img-circle img-fluid"
                            style="width:40px;height:40px;object-fit:cover;">
                        {{ Auth::guard('admin')->check() ? Auth::guard('admin')->user()->name : 'Guest' }}
                    </a>
                    ...
                </li>

                <li class="nav-item dropdown">
                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>

                        @if ($pendingReturnsCount > 0)
                            <span class="badge bg-green">{{ $pendingReturnsCount }}</span>
                        @endif
                    </a>

                    <ul class="dropdown-menu list-unstyled msg_list">
                        <li class="nav-item text-center px-3 py-2">
                            <strong>{{ $pendingReturnsCount }}</strong> yêu cầu hoàn hàng mới
                        </li>

                        <li class="divider"></li>

                        <li class="nav-item text-center">
                            <a href="{{ route('admin.returns.index') }}">
                                <strong>Xem quản lý hoàn đơn</strong>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>
