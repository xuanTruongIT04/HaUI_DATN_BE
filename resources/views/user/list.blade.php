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
                    <h5 class="m-0 ">Danh sách người dùng</h5>
                </div>
                <div class="form-search form-inline">
                    <form action="#" method="GET">
                        @csrf
                        <input type="text" class="form-control form-search" name="keyWord"
                            value="{{ request()->input('keyWord') }}" placeholder="Tìm kiếm theo tên">
                        <input type="submit" name="btn_search" value="Tìm kiếm" class="btn btn-primary">
                        <input type="hidden" name="status"
                            value="{{ empty(request()->input('status')) ? 'active' : request()->input('status') }}" />
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="analytic">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" class="text-primary">Có hiệu
                        lực<span class="text-muted">({{ $countUserStatus[0] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="text-primary">Chờ duyệt
                        <span class="text-muted">({{ $countUserStatus[1] }})</span></a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'blocked']) }}" class="text-primary">Đã khoá<span
                            class="text-muted">({{ $countUserStatus[2] }})</span></a>
                </div>
                <form action="{{ url('user/action') }}" method="GET">
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
                        <div class="count-user"><span>Kết quả tìm kiếm: <b>{{ $countUsersSearch }}</b> người dùng</span>
                        </div>
                    @endif
                    @if ($countUsers > 0)
                        <table class="table table-striped table-checkall">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="checkAll">
                                    </th>
                                    <th scope="col">#</th>
                                    <th scope="col">Họ</th>
                                    <th scope="col">Tên</th>
                                    <th scope="col">Địa chỉ email</th>
                                    <th scope="col">Số điện thoại</th>
                                    <th scope="col">Số fax</th>
                                    <th scope="col">Tổng đơn</th>
                                    <th scope="col">Trạng thái</th>
                                    <th scope="col">Tác vụ</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                    $cnt = empty(request()->page) ? 0 : (request()->page - 1) * 20;
                                @endphp
                                @if (count($users) > 0)
                                    @foreach ($users as $user)
                                        @php
                                            $cnt++;
                                        @endphp
                                        <tr class="row-in-list">
                                            <td>
                                                <input type="checkbox" name="listCheck[]" value="{{ $user->id }}"">
                                            </td>
                                            <th scope=" row">{{ $cnt }}</th>
                                            <td> {{ $user->last_name }}</td>
                                            @if (request()->status != 'trashed')
                                                <td> <a href="{{ route('user.edit', $user->id) }}"
                                                        class="text-primary">{{ $user->first_name }}
                                                    </a></td>
                                            @else
                                                <td> <a class="text-primary">{{ $user->first_name }}
                                                    </a></td>
                                            @endif
                                            <td>
                                                <a href="mailto:{{ $user->email }}"
                                                    class="text-primary">{{ $user->email }}</a>
                                            </td>
                                            <td> {{ $user->phone }}</td>
                                            <td> {{ $user->fax }}</td>
                                            <td> {{ $user->total_order }}</td>
                                            <td>{!! fieldStatusUser($user->status) !!}</td>
                                            <td>
                                                <a href="{{ route('user.edit', $user->id) }}"
                                                    class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" name="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                            </td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="bg-white">Không tồn tại người dùng nào ở trạng thái này!
                                        </td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                    @else
                        <tr>
                            <td colspan="7" class="bg-white">Không tìm thấy người dùng nào!</td>
                        </tr>
                    @endif
                </form>
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
