@extends('admin.layout.index')
<!-- Page Content -->
@section('content')
<div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Tin Tức
                            <small>{{$tintuc->TieuDe}}</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    <div class="col-lg-7" style="padding-bottom:120px">
                        @if(count($errors) > 0)
                            <div class = "alert alert-danger">
                                @foreach($errors->all() as $error)
                                    {{$error}}
                                @endforeach
                            </div>
                        @endif

                        @if(session('thongbao'))
                            <div class= "alert alert-success">
                                {{session('thongbao')}}
                            </div>
                        @endif
                        @if(session('loi'))
                            <div class= "alert alert-success">
                                {{session('loi')}}
                            </div>
                        @endif
                        <form action="admin/tintuc/sua/{{$tintuc->id}}" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="form-group">
                                <label>Thể loại</label>
                                <select class="form-control" id="TheLoai">
                                @foreach($theloai as $tl)
                                    <option 
                                    @if($tintuc->loaitin->theloai->id == $tl->id)
                                        {{"selected"}}
                                    @endif
                                    value="{{$tl->id}}" name="TheLoai">{{$tl->Ten}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Loại tin</label>
                                <select class="form-control" id="LoaiTin"
                                name="LoaiTin">
                                @foreach($loaitin as $lt)
                                    <option
                                    @if($tintuc->loaitin->id == $lt->id){
                                        {{"selected"}}
                                    }
                                    @endif
                                     value="{{$lt->id}}">{{$lt->Ten}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tiêu đề</label>
                                <input class="form-control" name="TieuDe" placeholder="Nhập tiêu đề" value="{{$tintuc->TieuDe}}" />
                            </div>
                            <div class="form-group">
                                <label>Tóm tắt</label>
                                <textarea name="TomTat" id="demo" class="form-control ckeditor"  rows="3" >{{$tintuc->TomTat}}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Nội dung</label>
                                <textarea name="NoiDung" id="demo" class="form-control ckeditor" rows="5" >{{$tintuc->NoiDung}}</textarea>
                            </div>
                            <div class="form-group">
                                <label>Hình ảnh</label>
                                <p>
                                <img width="400px" src="upload/tintuc/{{$tintuc->Hinh}}">
                                </p>
                                <input type="file" name="Hinh" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label>Nổi bật</label>
                                <label class="radio-inline">
                                    <input name="NoiBat" value="0"
                                    @if($tintuc->NoiBat == 0)
                                        {{"checked"}}
                                    @endif
                                    type="radio">Không
                                </label>
                                <label class="radio-inline">
                                    <input name="NoiBat" value="1"
                                    @if($tintuc->NoiBat == 1)
                                        {{"checked"}}
                                    @endif
                                    type="radio">Có
                                </label>
                            </div>
                            <button type="submit" class="btn btn-default">Sửa</button>
                            <button type="reset" class="btn btn-default">Làm mới</button>
                        <form>
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Tin Tức
                            <small>Danh Sách</small>
                        </h1>
                    </div>
                    <!-- /.col-lg-12 -->
                    @if(session('thongbao'))
                            <div class= "alert alert-success">
                                {{session('thongbao')}}
                            </div>
                        @endif
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr align="center">
                                <th>ID</th>
                                <th>User</th>
                                <th>Nội dung</th>
                                <th>Ngày đăng</th>
                                <th>Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tintuc->comment as $cm)
                            <tr class="odd gradeX" align="center">
                                <td>{{$cm->id}}</td>
                                <td>{{$cm->user->name}}</td>
                                <td>{{$cm->NoiDung}}</td>
                                <td>{{$cm->created_at}}</td>
                                <td class="center"><i class="fa fa-trash-o  fa-fw"></i><a href="admin/comment/xoa/{{$cm->id}}/{{$tintuc->id}}">Xóa</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- end row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $("#TheLoai").change(function(){
                var idTheLoai = $(this).val();
                $.get("admin/ajax/loaitin/"+idTheLoai,function(data){
                    $("#LoaiTin").html(data);
                    // alert(data);
                });
            });
        });
    </script>
@endsection