<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TheLoai;

class TheLoaiController extends Controller
{
    public function getDanhSach(){
        $theloai = TheLoai::all();
        return view('admin.theloai.danhsach',['theloai'=>$theloai]);
    }

    public function getThem(){
        return view('admin.theloai.them');

    }

    public function postThem(Request $request){
        $this->validate($request,
        [
            'Ten' => 'required|min:3|max:100|unique:TheLoai,Ten'
        ],
        [
            'Ten.required' => 'Bạn chưa nhập tên thể loại',
            'Ten.min' => 'Tên thể loại phải chứa từ 3 cho đến 100 kí tự',
            'Ten.max' => 'Tên thể loại phải chứa từ 3 cho đến 100 kí tự',
            'Ten.unique'=> 'Tên thể loại đã bị trùng',
        ]);

        $theloai = new TheLoai;

        $theloai->Ten = $request->Ten;
        $theloai->TenKhongDau = str_slug($request->Ten,'-');

        $theloai->save();

        return redirect('admin/theloai/them')->with('thongbao','Thêm Thành Công Thể Loại Mới');
    }

    public function getSua($id){
        $theloai = TheLoai::find($id);
        return view('admin.theloai.sua',['theloai'=>$theloai]);

    }

    public function postSua(Request $request,$id){
        $theloai = TheLoai::find($id);
        $this->validate($request,
        [
            'Ten'=>'required|unique:TheLoai,Ten|min:3|max:100'
        ],
        [
            'Ten.required' => 'Bạn chưa nhập tên thể loại',
            'Ten.unique' => 'Tên thể loại đã bị trùng',
            'Ten.min' => 'Tên thể loại phải có ít nhất 3 ký tự và nhiều nhất là 100',
            'Ten.max' => 'Tên thể loại phải có ít nhất 3 ký tự và nhiều nhất là 100',
        ]);
        $theloai->Ten = $request->Ten;
        $theloai->TenKhongDau = str_slug($request->Ten,'-');
        $theloai->save();
        return redirect('admin/theloai/sua/'.$id)->with('thongbao','Bạn đã sửa thành công');

    }

    public function getXoa($id){
        $theloai = TheLoai::find($id);
        $theloai->delete();

        return redirect('admin/theloai/danhsach')->with('thongbao','Bạn đã xóa thành công');
    }
}
