<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class DownloadController extends Controller 
{
    function index()
    {
        $data = Download::latest()->get();
        return response()->json(['status' => true, 'data' => $data]);
    }

    function store(Request $request)
    {   
        $validator = Validator::make($request->all(), [
            'file' => 'mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsx|max:1024',
            'name' => 'required|max:100'
        ]);
            
        if($validator->fails())
        {
            return response()->json($validator->errors(), 422);
        }

        $file_name = '';
        if($request->hasFile('file') AND $request->file('file')->isValid()) 
        {        
            $file = $request->file('file');
            $file_name = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('download'), $file_name);
        }

        $download = new Download;
        $download->file = $file_name;
        $download->name = $request->name;
        
        if($download->save())
        {
            return response()->json(['status' => true, 'message' => 'Data Berhasil Disimpan'], 201);
        }else
        {
            return response()->json(['status' => false, 'message' => 'Data Gagal Disimpan!'], 409);
        }
    }

    function detail($id)
    {
        $data = Download::find($id);
        if($data)
        {
            return response()->json(['status' => true, 'data' => $data]);
        }else
        {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan!'], 404);
        }
    }

    function update(Request $request, $id)
    {   
        $download = Download::select('id','file')->find($id);
        if($download)
        {
            $validator = Validator::make($request->all(), [
                'file' => 'mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsx|max:1024|nullable',
                'name' => 'required|max:100'
            ]);
                
            if($validator->fails())
            {
                return response()->json($validator->errors(), 422);
            }
    
            if($request->hasFile('file') AND $request->file('file')->isValid()) 
            {        
                $file = $request->file('file');
                $file_name = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('download'), $file_name);
                if(File::exists("download/$download->file"))
                {
                    File::delete("download/$download->file");
                }
            }else
            {
                $file_name = $download->file;
            }
    
            $download->file = $file_name;
            $download->name = $request->name;
    
            if($download->save())
            {
                return response()->json(['status' => true, 'message' => 'Data Berhasil Diupdate']);
            }else
            {
                return response()->json(['status' => false, 'message' => 'Data Gagal Diupdate!'], 409);
            }    
        }else
        {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan!'], 404);
        }
    }

    function delete($id)
    {   
        $data = Download::select('id','file')->find($id);
        if($data)
        {
            if(File::exists("download/$data->file"))
            {
                File::delete("download/$data->file");
            }
            
            if($data->delete())
            {
                return response()->json(['status' => true, 'message' => 'Data Berhasil Dihapus'], 200);
            }else
            {
                return response()->json(['status' => false, 'message' => 'Data Gagal Dihapus!'], 409);
            }
        }else
        {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan!'], 404);
        }
    }  

}