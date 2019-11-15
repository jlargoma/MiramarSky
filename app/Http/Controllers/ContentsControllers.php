<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contents;
use App\Http\Requests;
use Intervention\Image\Facades\Image;

class ContentsControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($key = null)
    {
      $current = null;
      $fields = null;
      $lst = Contents::getKeyLst();
      if ($key && isset($lst[$key])){
        $fields = Contents::getKeyContent($key);
        $current = $key;
        $oContent = Contents::where('key',$key)->get();
        if ($oContent){
          foreach ($oContent as $cont){
            if (isset($fields[$cont->field])){
              $fields[$cont->field][2] = $cont->content;
            }
          }
        }
      }
      return view('backend.contents.index',[
          'lst'=> $lst,
          'current' =>$current,
          'fields' => $fields
              ]);
    }
    
    public function update($key,Request $request) {

       $lst = Contents::getKeyLst();
      if ($key && isset($lst[$key])){
        $fields = Contents::getKeyContent($key);
        $i=0;
        if ($fields){
          foreach ($fields as $k=>$f){
            $i++;
            
            //remove objectss
            if ($request->has($k.'_remove')){
              $obj = Contents::where('key',$key)->where('field',$k)->first();
              if ($obj) {
                $obj->content = '';
                $obj->save();
              }
            }
            
            switch ($f[1]){
              case 'ckeditor':
              case 'string':
              case 'textarea':
                $this->saveText($k,$key,$request);
              break;
              case 'file':
                $this->saveImg($k,$key,$request,$i);
              break;
              case 'video':
                $this->saveVideo($k,$key,$request,$i);
              break;
            }            
          }
        }
      }
      return redirect()->back()->with('success', 'Contenido Guardado');  
    }
    
    
    private function saveText($field,$key,Request $request) {
      $value = $request->input($field,null);
      if ($value){
        $obj = Contents::firstOrCreate(array('key' => $key,'field'=>$field));
       
        $obj->content = $value;
        $obj->save();
      }
    }
    
    
    private function saveImg($field,$key,Request $request,$i) {
    
      $rute = "/img/miramarski/contents";
      $directory = public_path() . $rute;
      if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
      }
      if (!file_exists($directory.'/a')) {
        mkdir($directory.'/a', 0777, true);
      }
      /** Upload FILES */
      if($request->hasfile($field)){
        $obj = Contents::firstOrCreate(array('key' => $key,'field'=>$field));
        
        $img_file = $request->file($field);
        $extension = explode('.', $img_file->getClientOriginalName());
        $imagename_desktop =  $i.'-'.time().'.'.$extension[count($extension)-1];
//        $img = Image::make($img_file->getRealPath());
        $img = Image::make($img_file->getRealPath())->interlace();
        $width = $img->width();
        if ($width>1024){
          $img->widen(1024);
        }

        $img->save($directory.'/'.$imagename_desktop,80);
        $img->fit(800, 600)->save($directory.'/a/'.$imagename_desktop,8);
        //Save photo item
        $obj->content = $rute.'/'.$imagename_desktop;
        $obj->save();

      }
      
    }
    private function saveVideo($field,$key,Request $request,$i) {
    
      $rute = "/img/miramarski/contents";
      $directory = public_path() . $rute;
      if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
      }
      /** Upload FILES */
      if($request->hasfile($field)){
        $obj = Contents::firstOrCreate(array('key' => $key,'field'=>$field));
        
        $file = $request->file($field);
        $filename = $file->getClientOriginalName();
        $saved = $file->move($directory, $filename);
        //Save video item
        $obj->content = $rute.'/'.$filename;
        $obj->save();

      }
      
    }
    
    public function re_saveImg() {
       return null;
      $obj = \App\RoomsPhotos::where('gallery_key','>=',1)->get();
      foreach ($obj as $i){
        $folder = $i->gallery_key;
        if (!is_numeric($folder)){
          continue;
        }
        $imagename = $i->file_name;
        $directory = public_path() . $i->file_rute.'/';
        $directoryMobile = $directory. "/mobile";
        if (!is_file($directory.$imagename)){
          continue;
        }
        if (!file_exists($directoryMobile)) {
          mkdir($directoryMobile, 0777, true);
        }
         $destinationPath = $directoryMobile;
//         var_dump($directory.$imagename); die;
          $img = Image::make($directory.$imagename)->interlace();
          $img->resize(430, 430, function ($constraint) {
                      $constraint->aspectRatio();
                  })->save($destinationPath.'/'.$imagename);
                
      }
    return null;
      $rute = "/img/miramarski/contents";
      $directory = public_path();
      
      
      $lst = Contents::getKeyLst();
      foreach ($lst as $k=>$v){
        $fields = Contents::getKeyContent($k);
        foreach ($fields as $kf=>$f){
          if ($f[1] == 'file'){
            $oContent = Contents::where('key',$k)->where('field',$kf)->first();
            if ($oContent){
              $fileName = $oContent->content;
              $img = Image::make($directory.$fileName)->interlace();
              $img->save($directory.$fileName,80);
            }
          }
        }
      }
    }
}
