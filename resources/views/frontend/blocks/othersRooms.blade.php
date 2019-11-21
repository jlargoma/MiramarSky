<section class="more-rooms-secsion">
  <div class="row push-30" style="margin-top: 20px;">
    <h2 class="text-center black font-w300">
      GALERÍA DE <span class="font-w800 green ">APARTAMENTOS</span>
    </h2>

    <?php
    $oRooms = \App\RoomsType::all();
    ?>

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
                         src="{{getCloudfl($photo->file_rute)}}/{{$photo->file_name}}"
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