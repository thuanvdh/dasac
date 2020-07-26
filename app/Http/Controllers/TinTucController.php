<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use App\TinTuc;
use App\TheLoai;
use App\LoaiTin;
use App\Comment;

class TinTucController extends Controller
{
    public function getDanhSach(){
        $tintuc = TinTuc::orderBy('id','desc')->get();
        return view('admin.tintuc.danhsach',['tintuc'=>$tintuc]); 
    }

    public function getThem(){
        $theloai = TheLoai::all();
        $loaitin = LoaiTin::all();
        return view('admin.tintuc.them',['theloai'=>$theloai,'loaitin'=>$loaitin]);        
    }

    public function postThem(Request $request){
        $this->validate($request,
        [
            'LoaiTin' => 'required',
            'TieuDe' => 'required|min:3|unique:TinTuc,TieuDe',
            'TomTat' => 'required',
            'NoiDung' => 'required',
        ],
        [
            'LoaiTin.required' => 'Bạn chưa chọn loại tin',
            'TieuDe.required' => 'Bạn chưa nhập tiêu đề',
            'TieuDe.min' => 'Tên tiêu đề phải có ít nhất 3 ký tự',
            'TieuDe.unique' => 'Tên tiêu đề đã bị trùng',
            'TomTat.required' => 'Bạn chưa nhập tóm tắt',
            'NoiDung.required' => 'Bạn chưa nhập nội dung',
        ]);

        $tintuc = new TinTuc;
        $tintuc->TieuDe = $request->TieuDe;
        $tintuc->TieuDeKhongDau = str_slug($request->TieuDe,'-');
        $tintuc->idLoaiTin = $request->LoaiTin;
        $tintuc->NoiDung = $request->NoiDung;
        $tintuc->TomTat = $request->TomTat;
        $tintuc->NoiBat = $request->NoiBat;
        $tintuc->SoLuotXem = 0;
        if($request->hasFile('Hinh')){
            $file = $request->file('Hinh');
            $duoi = $file->getClientOriginalExtension();
            if($duoi != 'jpg' && $duoi != 'png' && $duoi != 'jpeg'){
                return redirect('admin/tintuc/them')->with('loi','Bạn chỉ được chọn file có đuôi là jpg,png,jpeg');
            }
            $namefile = $file->getClientOriginalName();
            $Hinh = str_random(4)."_".$namefile;
            while(file_exists("upload/tintuc/".$Hinh)){
                $Hinh = str_random(4)."_".$namefile;
            }
            $file->move("upload/tintuc",$Hinh);
            $tintuc->Hinh = $Hinh;
        }
        else
        {
            $tintuc->Hinh = "";
        }
        $tintuc->save();
        return redirect('admin/tintuc/them')->with('thongbao','Bạn đã thêm tin tức thành công');
        
    }

    public function getSua($id){
        $theloai = TheLoai::all();
        $loaitin = LoaiTin::all();
        $tintuc = TinTuc::find($id);
        return view('admin.tintuc.sua',['tintuc'=>$tintuc,'theloai'=>$theloai,'loaitin'=>$loaitin]);
    }

    public function postSua(Request $request,$id){
        $tintuc = TinTuc::find($id);
        $this->validate($request,
        [
            'LoaiTin' => 'required',
            'TieuDe' => 'required|min:3|unique:TinTuc,TieuDe',
            'TomTat' => 'required',
            'NoiDung' => 'required',
        ],
        [
            'LoaiTin.required' => 'Bạn chưa chọn loại tin',
            'TieuDe.required' => 'Bạn chưa nhập tiêu đề',
            'TieuDe.min' => 'Tên tiêu đề phải có ít nhất 3 ký tự',
            'TieuDe.unique' => 'Tên tiêu đề đã bị trùng',
            'TomTat.required' => 'Bạn chưa nhập tóm tắt',
            'NoiDung.required' => 'Bạn chưa nhập nội dung',
        ]);

        $tintuc->TieuDe = $request->TieuDe;
        $tintuc->TieuDeKhongDau = str_slug($request->TieuDe,'-');
        $tintuc->idLoaiTin = $request->LoaiTin;
        $tintuc->NoiDung = $request->NoiDung;
        $tintuc->TomTat = $request->TomTat;
        if($request->hasFile('Hinh')){
            $file = $request->file('Hinh');
            $duoi = $file->getClientOriginalExtension();
            if($duoi != 'jpg' && $duoi != 'png' && $duoi != 'jpeg'){
                return redirect('admin/tintuc/them')->with('loi','Bạn chỉ được chọn file có đuôi là jpg,png,jpeg');
            }
            $namefile = $file->getClientOriginalName();
            $Hinh = str_random(4)."_".$namefile;
            while(file_exists("upload/tintuc/".$Hinh)){
                $Hinh = str_random(4)."_".$namefile;
            }
            $file->move("upload/tintuc",$Hinh);
            unlink("upload/tintuc/".$tintuc->Hinh);
            $tintuc->Hinh = $Hinh;
        }
        $tintuc->save();
        return redirect('admin/tintuc/sua/'.$id)->with('thongbao','Sửa thành công');

    }

    public function getXoa($id){
        $tintuc = TinTuc::find($id);
        $tintuc->delete();
        return redirect('admin/tintuc/danhsach')->with('thongbao','Đã xóa thành công');
    }
}
