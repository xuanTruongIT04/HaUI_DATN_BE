@extends('layouts.admin')

@section('content')
    <div id="list" class="container-fluid">
        <div class="card">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <div id="title-btn-add">
                    <h5 class="m-0 ">Danh sách slide</h5>
                    <a href="{{ route('slide.add') }}" class="btn btn-primary ml-3">THÊM MỚI</a>
                </div>
                <div class="form-search form-inline">
                    <form action="#" method="GET">
                        @csrf
                        <input type="text" class="form-control form-search" name="keyWord"
                            value="{{ request()->input('keyWord') }}" placeholder="Tìm kiếm">
                        <input type="submit" name="btn_search" value="Tìm kiếm" class="btn btn-primary">
                        <input type="hidden" name="status"
                            value="{{ empty(request()->input('status')) ? 'active' : request()->input('status') }}" />
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" class="text-primary">Kích
                        hoạt<span class="text-muted">({{ $countSlideStatus[0] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'licensed']) }}" class="text-primary">Đã đăng
                        <span class="text-muted">({{ $countSlideStatus[1] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="text-primary">Chờ duyệt
                        <span class="text-muted">({{ $countSlideStatus[2] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'trashed']) }}" class="text-primary">Vô hiệu
                        hoá<span class="text-muted">({{ $countSlideStatus[3] }})</span></a>
                </div>
                <form action="{{ url('slide/action') }}" method="GET">
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" name="act" id="">
                            <option value="">Chọn hành động</option>
                            @foreach ($listAct as $k => $act)
                                <option value="{{ $k }}">{{ $act }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
                    @if (!empty(request()->keyWord))
                        <div class="count-slide"><span>Kết quả tìm kiếm: <b>{{ $countSlidesSearch }}</b> slide</span></div>
                    @endif
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="checkAll">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Tên slide</th>
                                <th scope="col">Mô tả ngắn</th>
                                <th scope="col">Cấp độ (Thứ tự)</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($slides) > 0)
                                @php
                                    $cnt = empty(request()->page) ? 0 : (request()->page - 1) * 20;
                                @endphp
                                @foreach ($slides as $slide)
                                    @php
                                        $cnt++;
                                    @endphp
                                    <tr class="row-in-list">
                                        <td>
                                            <input type="checkbox" name="listCheck[]" value="{{ $slide->id }}"">
                                        </td>
                                        <th scope=" row">{{ $cnt }}</th>
                                        @if (request()->status != 'trashed')
                                            <td> <a href="{{ route('slide.edit', $slide->id) }}"
                                                    class="text-primary">{{ $slide->name }}
                                                </a></td>
                                        @else
                                            <td> <a class="text-primary">{{ $slide->name }}
                                                </a></td>
                                        @endif
                                        <td>{{ briefName($slide->description, 10) }}</td>
                                        <td>{!! $slide->level !!}</td>
                                        <td>{!! fieldStatusCategory($slide->status) !!}</td>
                                        @if (request()->status != 'trashed')
                                            <td>
                                                <a href="{{ route('slide.edit', $slide->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" name="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                                <a href="{{ route('slide.delete', $slide->id) }}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xoá tạm thời slide {{ $slide->name }}?')"
                                                    data-placement="top" name="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        @else
                                            <td>
                                                <a href="{{ route('slide.restore', $slide->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn khôi phục slide {{ $slide->name }}?')"
                                                    data-placement="top" name="Restore"><i
                                                        class="fas fa-trash-restore-alt"></i></a>
                                                <a href="{{ route('slide.delete', $slide->id) }}"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip"
                                                    onclick="return confirm('Bạn có chắc chắn muốn xoá vĩnh viễn slide {{ $slide->name }}?')"
                                                    data-placement="top" name="Delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        @endif

                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="bg-white">Không tìm thấy slide nào!</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </form>
                {{ $slides->links() }}
            </div>
        </div>
    </div>
@endsection
