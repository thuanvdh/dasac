<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TinTuc;
use App\TheLoai;
use App\LoaiTin;
use App\Comment;
use App\Slide;

class SlideController extends Controller
{
    public function getDanhSach()
    {
        $slide = Slide::all();
        return view('admin.slide.danhsach',['slide'=>$slide]);
    }

    public function getThem()
    {
        return view('admin.slide.them');            
    }

    public function postThem(Request $request)
    {
        $this->validate($request,
        [
            'Ten'=>'required|min:3',
            'NoiDung'=>'required',
            'link'=>'required'
        ],
        [
            'Ten.required'=>'Bạn chưa nhập tên',
            'Ten.min'=>'Tên phải có ít nhất 3 kí tự',
            'NoiDung.required'=>'Bạn chưa nhập nội dung',
            'link.required'=>'Bạn chưa nhập link'
        ]);
        $slide = new Slide;
        $slide->Ten = $request->Ten;
        $slide->NoiDung = $request->NoiDung;
        if($request->has('link'))
           {
             $slide->link = $request->link;
           }
        if($request->hasFile('Hinh')){
            $file = $request->file('Hinh');
            $duoi = $file->getClientOriginalExtension();
            if($duoi != 'jpg' && $duoi != 'png' && $duoi != 'jpeg'){
                return redirect('admin/slide/them')->with('loi','Bạn chỉ được chọn file có đuôi là jpg,png,jpeg');
            }
            $namefile = $file->getClientOriginalName();
            $Hinh = str_random(4)."_".$namefile;
            while(file_exists("upload/slide/".$Hinh)){
                    $Hinh = str_random(4)."_".$namefile;
            }
            $file->move("upload/slide",$Hinh);
            $slide->Hinh = $Hinh;
        }
        else
        {
            $slide->Hinh = "";
        }
        $slide->save();
        return redirect('admin/slide/them')->with('thongbao','Thêm thành công');
    }

    public function getSua($id)
    {
        $slide = Slide::find($id);
        return view('admin.slide.sua',['slide'=>$slide]);
    }

    public function postSua(Request $request,$id)
    {
       
        $this->validate($request,
        [
            'Ten'=>'required|min:3',
            'NoiDung'=>'required'
        ],
        [
            'Ten.required'=>'Bạn chưa nhập tên',
            'Ten.min'=>'Tên phải có ít nhất 3 kí tự',
            'NoiDung.required'=>'Bạn chưa nhập nội dung'
        ]);
        $slide = Slide::find($id);
        $slide->Ten = $request->Ten;
        $slide->NoiDung = $request->NoiDung;
        if($request->has('link'))
           {
             $slide->link = $request->link;
           }
        if($request->hasFile('Hinh')){
            $file = $request->file('Hinh');
            $duoi = $file->getClientOriginalExtension();
            if($duoi != 'jpg' && $duoi != 'png' && $duoi != 'jpeg'){
                return redirect('admin/slide/them')->with('loi','Bạn chỉ được chọn file có đuôi là jpg,png,jpeg');
            }
            $namefile = $file->getClientOriginalName();
            $Hinh = str_random(4)."_".$namefile;
            while(file_exists("upload/slide/".$Hinh)){
                    $Hinh = str_random(4)."_".$namefile;
            }
            unlink("upload/slide/".$slide->Hinh);
            $file->move("upload/slide",$Hinh);
            $slide->Hinh = $Hinh;
        }

        $slide->save();
        return redirect('admin/slide/sua/'.$id)->with('thongbao','Đã sửa thành công');
    }

    public function getXoa($id)
    {
        $slide = Slide::find($id);
        $slide->delete();
        return redirect('admin/slide/danhsach')->with('thongbao','Đã xóa thành công');
    }
}
