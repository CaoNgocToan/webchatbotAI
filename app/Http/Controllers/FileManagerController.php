<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ObjectController;
use App\Http\Controllers\LogController;
use App\Models\FileManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Config;use Session;use File;

class FileManagerController extends Controller
{
    //
    public $arr_breadcrumb = array('');
    function list(Request $request, $locale = '') {
        $id_parent = $request->input('id_parent');
        $keywords = $request->input('keywords');
        if($id_parent) {
            $id_parent = ObjectController::ObjectId($id_parent);
        } else {
            $id_parent = '';
        }
        $folders = FileManager::where('id_parent', '=', $id_parent)->where('file_type','=','folder')->get();
        if($keywords){
            $files = FileManager::where('id_parent', '=', $id_parent)->where('file_type','=','file')->where('title', 'regexp', '/.*'.$keywords.'/i')->orderBy('updated_at', 'desc')->paginate(30);
        } else {
            $files = FileManager::where('id_parent', '=', $id_parent)->where('file_type','=','file')->orderBy('updated_at', 'desc')->paginate(30);
        }
        return view('Admin.FileManager.list')->with(compact('id_parent', 'folders', 'files','keywords'));
    }

    function update(Request $request, $locale = ''){
        $data = $request->all();
        $id_user = $request->session()->get('user._id');
        if($data['id_folder']) {
            $db = FileManager::find($data['id_folder']);
            $id = ObjectController::ObjectId($data['id_folder']);
            $logQuery = array (
                'action' => 'Chỉnh sửa thư mục ['.$data['title'].']',
                'id_collection' => $id,
                'collection' => 'file_manager',
                'data' => $data
            );
        } else {
            $db = new FileManager();
            $id = ObjectController::Id();
            $db->_id = $id;
            $logQuery = array (
                'action' => 'Thêm thư mục ['.$data['title'].']',
                'id_collection' => $id,
                'collection' => 'file_manager',
                'data' => $data
            );
        }
        $id_parent = $data['id_parent'] ? ObjectController::ObjectId($data['id_parent']) : '';
        $db->title = $data['title'];
        $db->type = 'folder';
        $db->file_type = 'folder';
        $db->id_parent = $id_parent;
        $db->id_user = ObjectController::ObjectId($id_user);
        $db->save();
        LogController::addLog($logQuery);
        Session::flash('msg', 'Cập nhật thành công');
        return redirect(env('APP_URL').$locale.'/admin/file-manager?id_parent='.$id_parent);
    }

    function update_file_title(Request $request, $locale = '') {
        $data = $request->all();
        $id_user = $request->session()->get('user._id');
        $f = FileManager::find($data['id_file']);
        $f->title = $data['title_file'];
        $f->save();
        Session::flash('msg', 'Cập nhật thành công');
        return redirect(env('APP_URL').$locale.'/admin/file-manager?id_parent='.$data['id_parent']);
    }

    function uploads(Request $request, $locale = '') {
        $files = $request->file('upload_files');
        $id_parent = $request->input('id_parent');
        $id_user = $request->session()->get('user._id');
        if(!empty($files)){
            foreach($files as $file) {
                $extension = $file->getClientOriginalExtension();
                $realname = $file->getClientOriginalName();
                $filename = date("YmdHis") . '_' . strtolower(uniqid()) . '.' . $extension;
                Storage::put('public/files/filemanager/'.$filename, file_get_contents($file), 'public');
                $size = Storage::size('public/files/filemanager/'.$filename);
                $db = new FileManager();
                $id = ObjectController::Id();
                $db->_id = $id;
                $db->aliasname = $filename;
                $db->filename = $realname;
                $db->title = $realname;
                $db->type = $extension;
                $db->size = $size;
                $db->file_type = 'file';
                $db->id_parent = ObjectController::ObjectId($id_parent);
                $db->id_user = ObjectController::ObjectId($id_user);
                $db->save();
                echo '<tr>';
                echo '<td><a href="'.env('APP_URL').$locale.'/admin/file-manager/xem-truc-tuyen/'.$id.'" data-toggle="modal" data-target="#ViewModal" class="ViewFile">'.$realname.'</a></td>';
                echo '<td class="text-center">'.$extension.'</td>';
                echo '<td class="text-center">'.$size.'</td>';
                echo '<td class="text-center">'.date("d/m/Y").'</td>';
                echo '<td class="text-center">
                        <a href="'.env('APP_URL').$locale.'/admin/file-manager/delete-file/'.$id.'" onclick="return confirm(\'Chắc chắn xóa?\')"><i class="fa fa-trash text-danger"></i></a>
                        <a href="'.env('APP_URL').$locale.'/admin/file-manager/edit-file/'.$id.'"><i class="fa fa-pencil-alt"></i></a>
                        <a href="'.env('APP_URL').$locale.'/admin/file-manager/download/'.$id.'"><i class="fa fa-download text-success"></i></a>
                    </td>';
                echo '</tr>';
            }
        }

    }

    function delete_folder(Request $request, $locale = '', $id = '') {
        $data = FileManager::find($id);
        $logQuery = array (
            'action' => 'Xóa thư mục ['.$data['title'].']',
            'id_collection' => $data['_id'],
            'collection' => 'file_manager',
            'data' => $data
        );
        FileManager::destroy($id);
        LogController::addLog($logQuery);
        Session::flash('msg', __('Xóa thành công'));
        return redirect(env('APP_URL').$locale.'/admin/file-manager?id_parent='.$data['id_parent']);
    }

    function delete_file(Request $request, $locale = '', $id = '') {
        $file = FileManager::find($id);
        FileManager::destroy($id);
        Storage::delete('public/files/filemanager/'.$file['aliasname']);
        Session::flash('msg', 'Xóa thành công');
        return redirect(env('APP_URL').$locale.'/admin/file-manager?id_parent='.$file['id_parent']);
    }

    function download(Request $request, $locale ='', $id='') {
        $f = FileManager::find($id);
        $file_path = 'public/files/filemanager/' . $f['aliasname'];
        $name  = Str::slug($f['title'], '-').'.' . $f['type'];
        return Storage::download($file_path, $name);
    }

    public function breadcrumb($id = '') {
        if($id) {
            $f = FileManager::find($id);
            if($f['id_parent']) {
                //$this->arr[] = $f['id_parent'];
                $this->breadcrumb($f['id_parent']);
            }
            $this->arr_breadcrumb[] = $id;
        }
        return $this->arr_breadcrumb;
    }

    function xem_truc_tuyen(Request $request, $locale = '', $id = '') {
        $f = FileManager::find($id);
        if(strtolower($f['type']) == 'doc' || strtolower($f['type']) == 'docx' || strtolower($f['type']) == 'xlsx'){
            $path_file = env('APP_URL') . 'storage/files/filemanager/'.$f['aliasname'];
            $frame_path = 'https://view.officeapps.live.com/op/embed.aspx?src='.$path_file;
            echo '<iframe src="'.$frame_path.'" onload=\'javascript:(function(o){o.style.height=o.contentWindow.document.body.scrollHeight+"px";}(this));\' style="min-width:100% !important;min-height:70vh !important;border:none;overflow:hidden;"></iframe>';
        } else if(strtolower($f['type']) == 'pdf') {
            echo '<embed src="'.env('APP_URL').'storage/files/filemanager/'.$f['aliasname'].'" style="min-width:100% !important;min-height:70vh !important;" />';
        } else {
            echo 'Không thể xem, vui lòng download về xem. Cám ơn!';
        }
    }

    function get_quy_trinh_bieu_mau(Request $request, $locale = '') {
        $q = $request->input('q');
        $id_parent = ObjectController::ObjectId("6348cca0d46e4f1c805f0a3e");
        $page = $request->input('page');
        if($q) {
            $files = FileManager::where('id_parent', '=', $id_parent)->where('file_type','=','file')->where('title', 'regexp', '/.*'.$q.'/i')->orderBy('updated_at', 'desc')->paginate(30);
        } else {
            $files = FileManager::where('id_parent', '=', $id_parent)->where('file_type','=','file')->orderBy('updated_at', 'desc')->paginate(30);
        }

        return view('Admin.FileManager.quy-trinh-bieu-mau')->with(compact('files', 'id_parent','q'));
    }
}
