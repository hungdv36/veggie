@extends('layouts.client')

@section('title', 'Đội ngũ')

@section('breadcrumb', 'Đội ngũ')

@section('content')
    <!-- TEAM AREA START -->
    <div class="team-area py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h6 class="text-uppercase fw-bold" style="color:#0f9d58;">// Đội ngũ chuyên nghiệp</h6>
                <h2 class="fw-bold mb-3">Gặp gỡ <span style="color:#0f9d58;">Đội ngũ của chúng tôi</span></h2>
                <p class="text-muted">Những con người trẻ trung, sáng tạo và đầy đam mê trong lĩnh vực thời trang.</p>
            </div>

            <div class="row justify-content-center g-4">
                @php
                    $members = [
                        ['name' => 'Đào Văn Hùng', 'role' => 'Người sáng lập', 'icon' => 'fa-user-tie'],
                        ['name' => 'Lê Quang Tuyến', 'role' => 'Giám đốc điều hành', 'icon' => 'fa-briefcase'],
                        ['name' => 'Trần Đình Anh Quân', 'role' => 'Nhà thiết kế', 'icon' => 'fa-pencil-ruler'],
                        ['name' => 'Nguyễn Đình Ngọc', 'role' => 'Chuyên viên Marketing', 'icon' => 'fa-bullhorn'],
                        ['name' => 'Lê Đại Dương', 'role' => 'Nhà phát triển Web', 'icon' => 'fa-code'],
                    ];
                @endphp

                @foreach ($members as $member)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="team-card text-center shadow-sm p-4 rounded-4 bg-white h-100">
                            <div class="icon-wrapper mb-3">
                                <i class="fas {{ $member['icon'] }} fa-3x" style="color:#0f9d58;"></i>
                            </div>
                            <h5 class="fw-bold">{{ $member['name'] }}</h5>
                            <p class="text-muted mb-3">{{ $member['role'] }}</p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="#" class="text-dark"><i class="fab fa-facebook-f"></i></a>
                                <a href="#" class="text-dark"><i class="fab fa-instagram"></i></a>
                                <a href="#" class="text-dark"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- TEAM AREA END -->

    <!-- SKILLS AREA START -->
    <div class="skills-area py-5" style="background-color:#0f9d58;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0 text-white">
                    <h6 class="text-uppercase fw-bold">// Kỹ năng</h6>
                    <h2 class="fw-bold mb-3">Chúng tôi có đội ngũ <br><span class="fw-bold">tay nghề cao nhất.</span></h2>
                    <p class="text-light">Tập thể của chúng tôi bao gồm những chuyên gia trong lĩnh vực thời trang, công nghệ và truyền thông — kết hợp hoàn hảo giữa sáng tạo và kỹ thuật.</p>
                </div>
                <div class="col-lg-6">
                    @php
                        $skills = [
                            ['name' => 'Thiết kế thời trang', 'percent' => 90],
                            ['name' => 'Marketing & Truyền thông', 'percent' => 85],
                            ['name' => 'Phát triển website', 'percent' => 80],
                            ['name' => 'Dịch vụ khách hàng', 'percent' => 95],
                        ];
                    @endphp

                    @foreach ($skills as $skill)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between text-white fw-semibold mb-1">
                                <span>{{ $skill['name'] }}</span>
                                <span>{{ $skill['percent'] }}%</span>
                            </div>
                            <div class="progress" style="height: 8px; border-radius: 10px;">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ $skill['percent'] }}%; background-color:white;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- SKILLS AREA END -->
@endsection

@push('styles')
<style>
    .team-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        transition: 0.3s ease;
    }
    .icon-wrapper i {
        background: #eaf8f0;
        padding: 20px;
        border-radius: 50%;
    }
</style>
@endpush
