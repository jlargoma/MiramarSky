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
            switch ($f[1]){
              case 'ckeditor':
              case 'string':
                $this->saveText($k,$key,$request);
              break;
              case 'file':
                $this->saveImg($k,$key,$request,$i);
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
      /** Upload FILES */
      if($request->hasfile($field)){
        $obj = Contents::firstOrCreate(array('key' => $key,'field'=>$field));
        
        $img_file = $request->file($field);
        $extension = explode('.', $img_file->getClientOriginalName());
        $imagename_desktop =  $i.'-'.time().'.'.$extension[count($extension)-1];
        $img = Image::make($img_file->getRealPath());
        $width = $img->width();
        if ($width>1024){
          $img->widen(1024);
        }

        $img->save($directory.'/'.$imagename_desktop);
        //Save photo item
        $obj->content = $rute.'/'.$imagename_desktop;
        $obj->save();

      }
      
  }
}
