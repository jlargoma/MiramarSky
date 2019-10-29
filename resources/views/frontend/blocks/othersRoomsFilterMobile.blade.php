<?php

$oRooms = \App\RoomsType::where('name','!=',$url)->get();
    
?>

<!-- apartamentos (sierra nevada) -->
<section class="summer-secsion">
  <div class="row push-30" style="margin-top: 20px;">
    <div class="rooms-title centered">
      <div class="section-title"><h1>Otros <span style="color: #3f51b5;">Apartamentos</span></h1></div>              
    </div>
  <?php foreach ($oRooms as $item): ?>
    <?php
    $photo = App\RoomsPhotos::where('gallery_key', $item->id)->orderBy('main', 'DESC')->orderBy('position')->first();
    if ($photo):
    ?>
    <div class=" row animatable" data-aos="zoom-in">
      <a href="{{route('web.apto',$item->name)}}" title="{{$item->title}}">
        <div class="section parallax noborder center"
             style="background-image: url({{$photo->file_rute}}/{{$photo->file_name}}); padding: 70px 0; margin: 20px 0;"
             data-stellar-background-ratio="0.4">
          <h3 class="h2 text-center white text-white font-w800 wst"
              style="text-shadow: 2px 1px #000;">{{$item->title}}</h3>
        </div>
      </a>
    </div>
                <?php endif; ?>
  <?php endforeach; ?>
  </div>
</section>
<!-- fin apartamentos (sierra nevada) -->