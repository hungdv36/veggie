@extends('layouts.admin')
@section('title', 'Quản lý người dùng')

@section('content')
    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Quản lý người dùng</h3>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="x_panel">
                <div class="x_content">
                    <div class="row">
                        @foreach ($users as $user)
                            <div class="col-md-4 col-sm-4  profile_details">
                                <div class="well profile_view">
                                    <div class="col-sm-12">
                                        <h4 class="brief"><i>{{ $user->role->name }}</i></h4>
                                        <div class="left col-md-7 col-sm-7">
                                            <h2>{{ $user->name }}</h2>
                                            <p><strong>About: </strong> {{ $user->email }}</p>
                                            <ul class="list-unstyled">
                                                <li><i class="fa fa-building"></i> Address: {{ $user->address }}</li>
                                                <li><i class="fa fa-phone"></i> Phone : {{ $user->phone_number }}</li>
                                            </ul>
                                        </div>
                                        <div class="right col-md-5 col-sm-5 text-center">
                                            <img src="images/img.jpg" alt="" class="img-circle img-fluid">
                                        </div>
                                    </div>
                                    <div class=" profile-bottom text-center">
                                        <div class="col-sm-4 emphasis"></div>
                                        <div class=" col-sm-8 emphasis">
                                            @if ($user->role->name == 'customer')
                                                <button type="button" class="btn btn-primary btn-sm upgradeStaff"
                                                    data-userid="{{ $user->id }}">
                                                    <i class="fa fa-user"></i>Nhân viên
                                                </button>
                                                {{-- Chặn / Bỏ chặn --}}
                                                @if ($user->status !== 'deleted')
                                                    @if ($user->status === 'banned')
                                                        <button type="button" class="btn btn-warning btn-sm toggleBlock"
                                                            data-userid="{{ $user->id }}">
                                                            <i class="fa fa-times"></i> Bỏ chặn
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-success btn-sm toggleBlock"
                                                            data-userid="{{ $user->id }}">
                                                            <i class="fa fa-check"></i> Chặn
                                                        </button>
                                                    @endif
                                                @endif

                                                {{-- Xóa / Khôi phục --}}
                                                @if ($user->status === 'deleted')
                                                    <button type="button" class="btn btn-secondary btn-sm toggleDelete"
                                                        data-userid="{{ $user->id }}">
                                                        <i class="fa fa-check"></i> Khôi phục
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-danger btn-sm toggleDelete"
                                                        data-userid="{{ $user->id }}">
                                                        <i class="fa fa-times"></i> Xóa
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->
@endsection
