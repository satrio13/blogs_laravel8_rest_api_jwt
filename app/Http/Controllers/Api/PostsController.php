<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller 
{
    function index()
    {
        $data = Posts::latest()->get();
        return response()->json(['status' => true, 'data' => $data]);
    }

    function store(Request $request)
    {   
        try
        {
            $validator = Validator::make($request->all(), [
                'image' => 'image|mimes:jpeg,jpg,png|max:1024',
                'title' => 'required|max:100',
                'content' => 'required'
            ]);
                
            if($validator->fails())
            {
                return response()->json($validator->errors(), 422);
            }

            $file_name = '';
            if($request->hasFile('image') AND $request->file('image')->isValid()) 
            {        
                $image = $request->file('image');
                $file_name = time().'_'.$image->getClientOriginalName();
                $image->move(public_path('img'), $file_name);
            }

            $data = [
                'image' => $file_name,
                'title' => $request->title,
                'content' => $request->content 
            ];
            
            Posts::create($data);
            return response()->json(['status' => true, 'message' => 'Data Berhasil Disimpan'], 201);
        }catch(\Exception $e)
        {
            return response()->json(['status' => false, 'message' => 'Data gagal disimpan, terjadi kesalahan'], 409);
        }
    }

    function detail($id)
    {
        $data = Posts::find($id);
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
        try
        {
            $post = Posts::select('id','image')->find($id);
            if($post)
            {
                $validator = Validator::make($request->all(), [
                    'image' => 'image|mimes:jpeg,jpg,png|max:1024|nullable',
                    'title' => 'required|max:100',
                    'content' => 'required'
                ]);
                    
                if($validator->fails())
                {
                    return response()->json($validator->errors(), 422);
                }

                if($request->hasFile('image') AND $request->file('image')->isValid()) 
                {        
                    $image = $request->file('image');
                    $file_name = time().'_'.$image->getClientOriginalName();
                    $image->move(public_path('img'), $file_name);
                    if(File::exists("img/$post->image"))
                    {
                        File::delete("img/$post->image");
                    }
                }else
                {
                    $file_name = $post->image;
                }

                $data = [
                    'image' => $file_name,
                    'title' => $request->title,
                    'content' => $request->content 
                ];
                
                $post->update($data);
                return response()->json(['status' => true, 'message' => 'Data Berhasil Diupdate']);   
            }else
            {
                return response()->json(['status' => false, 'message' => 'Data tidak ditemukan!'], 404);
            }
        }catch(\Exception $e)
        {
            return response()->json(['status' => false, 'message' => 'Data gagal diupdate, terjadi kesalahan'], 409);
        }
    }

    function delete($id)
    {   
        try
        {
            $data = Posts::select('id','image')->find($id);
            if($data)
            {
                if(File::exists("img/$data->image"))
                {
                    File::delete("img/$data->image");
                }
        
                $data->delete();
                return response()->json(['status' => true, 'message' => 'Data Berhasil Dihapus'], 200);
            }else
            {
                return response()->json(['status' => false, 'message' => 'Data tidak ditemukan!'], 404);
            }
        }catch(\Exception $e)
        {
            return response()->json(['status' => false, 'message' => 'Data gagal dihapus, terjadi kesalahan'], 409);
        }
    }  

}