<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TheLoai;
use Illuminate\Support\Facades\View;
use App\Slide;
use App\LoaiTin;
use App\TinTuc;
use Illuminate\Support\Facades\Auth;
use App\User;

class PagesController extends Controller
{
    function trangchu(){
        
        return view('pages.trangchu');
    }

    function lienhe(){
        
        return view('pages.lienhe');
    }

    function gioithieu(){
        
        return view('pages.gioithieu');
    }

    function loaitin($id){
        $loaitin = LoaiTin::find($id);
        $tintuc = TinTuc::where('idLoaiTin',$id)->paginate(5);
        return view('pages.loaitin',['loaitin'=>$loaitin],['tintuc'=>$tintuc]);
    }

    function tintuc($id){
        $tintuc = TinTuc::find($id);
        $tinnoibat = TinTuc::where('NoiBat',1)->take(4)->get();
        $tinlienquan = TinTuc::where('idLoaiTin',$tintuc->idLoaiTin)->take(4)->get();
        return view('pages.tintuc',['tintuc'=>$tintuc,'tinnoibat'=>$tinnoibat,'tinlienquan'=>$tinlienquan]);
    }

    public function getDangnhap(){
        return view('pages.dangnhap');
    }

    public function postDangnhap(Request $request){
        $this->validate($request,
        [
            'email'=>'required',
            'password'=>'required|min:3|max:32',
        ],
        [
            'email.required'=>'Bạn chưa nhập email',
            'password.required'=>'Bạn chưa nhập password',
            'password.min'=>'Password không được nhỏ hơn 3 kí tự',
            'password.max'=>'Password không được quá 32 kí tự',
        ]);

        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password]))
        {
            return redirect('trangchu')->with('thongbaodangnhap','Bạn đã đăng nhập thành công');
        }
        else {
            return redirect('dangnhap')->with('thongbao','Bạn chưa nhập đúng email hoặc mật khẩu');
        }
    }

    public function getDangxuat()
    {
        Auth::logout();
        return redirect('trangchu');
    }

    public function getNguoidung()
    {   
        // $user = Auth::user();
        return view('pages.nguoidung');
    }

    public function postNguoidung(Request $request){
        $this->validate($request,
       [
           'name'=>'required|min:3',
       ],
       [
           'name.required'=>'Bạn chưa nhập tên người dùng',
           'name.min'=>'Tên người dùng phải có ít nhất 3 kí tự',
           
       ]);
       $user = Auth::user();
       $user->name = $request->name;

       if($request->changePassword == "on")
       {
        $this->validate($request,
        [
            'password'=>'required|min:3|max:32',
            'passwordAgain'=>'required|same:password',
        ],
        [
            'password.required'=>'Bạn chưa nhập mật khẩu',
            'password.min'=>'Mật khẩu phải có ít nhất 3 kí tự và nhiều nhất 32 kí tự',
            'password.max'=>'Mật khẩu phải có ít nhất 3 kí tự và nhiều nhất 32 kí tự',
            'passwordAgain.required'=>'Bạn chưa nhập lại mật khẩu',
            'passwordAgain.same'=>'Mật khẩu nhập lại không khớp',
        ]);
            $user->password = bcrypt($request->password);
       }
       $user->save();

       return redirect('nguoidung')->with('thongbao','Bạn đã sửa thành công');

    }

    public function getDangky()
    {
        return view('pages.dangky');
    }

    public function postDangky(Request $request)
    {
        $this->validate($request,
       [
           'name'=>'required|min:3',
           'email'=>'required|email|unique:users,email',
           'password'=>'required|min:3|max:32',
           'passwordAgain'=>'required|same:password',
       ],
       [
           'name.required'=>'Bạn chưa nhập tên người dùng',
           'name.min'=>'Tên người dùng phải có ít nhất 3 kí tự',
           'email.required'=>'Bạn chưa nhập email',
           'email.email'=>'Bạn chưa nhập đúng định dạnh email',
           'email.unique'=>'Email đã tồn tại',
           'password.required'=>'Bạn chưa nhập mật khẩu',
           'password.min'=>'Mật khẩu phải có ít nhất 3 kí tự và nhiều nhất 32 kí tự',
           'password.max'=>'Mật khẩu phải có ít nhất 3 kí tự và nhiều nhất 32 kí tự',
           'passwordAgain.required'=>'Bạn chưa nhập lại mật khẩu',
           'passwordAgain.same'=>'Mật khẩu nhập lại không khớp',
       ]);

       $user = new User;
       $user->name = $request->name;
       $user->email = $request->email;
       $user->password = bcrypt($request->password);
       $user->quyen = 0;
       $user->save();
       return redirect('dangnhap')->with('thongbao','Bạn đã đăng ký thành công');
    }

    public function timkiem(Request $request)   
    {   
        $tukhoa = $request->tukhoa;
        $tintuc = TinTuc::where('TieuDe','like',"%$tukhoa%")->orWhere('TomTat','like',"%$tukhoa%")->orWhere('NoiDung','like',"%$tukhoa%")->take(30)->paginate(5);
        return view('pages.timkiem',['tintuc'=>$tintuc,'tukhoa'=>$tukhoa]);
    }
}
