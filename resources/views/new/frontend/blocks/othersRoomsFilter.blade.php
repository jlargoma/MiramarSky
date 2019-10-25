<?php

$oRooms = \App\RoomsType::where('name','!=',$url)->get();
    
?>

<!-- apartamentos (sierra nevada) -->
<section class="summer-secsion">
  <div class="row push-30" style="margin-top: 20px;">
    <div class="rooms-title centered">
      <div class="section-title"><h1>Otros <span style="color: #3f51b5;">Apartamentos</span></h1></div>              
    </div>
   <div class="moreRooms-box">
      <div class="lower-content centered moreRooms_1">
        <ul id="moreRooms_1" class="content-slider">
<?php foreach ($oRooms as $item): ?>
            <?php
            $photo = App\RoomsPhotos::where('gallery_key', $item->id)->orderBy('main', 'DESC')->orderBy('position')->first();
            if ($photo):
            ?>

            <li class="hover-effect moreRooms" >

              <a href="{{route('web.apto',$item->name)}}" title="{{$item->title}}">
                <div class="col-xs-12 not-padding  container-image-box">
                  <div class="col-xs-12 not-padding push-0">
                    <img class="img-responsive imga"
                         src="{{$photo->file_rute}}/{{$photo->file_name}}"
                         alt="{{$item->title}}"/>
                  </div>
                  <div class="col-xs-12 not-padding text-right overlay-text">
                    <h2 class="font-w600 center push-10 text-center text font-s24 white hvr-reveal"
                        style="padding: 55px 10px;width: 90%;">{{$item->title}}
                    </h2>
                  </div>
                </div>
              </a>
            </li>
            <?php endif; ?>
<?php endforeach; ?>

        </ul>
      </div>
    </div>
  </div>
</section>
<!-- fin apartamentos (sierra nevada) -->