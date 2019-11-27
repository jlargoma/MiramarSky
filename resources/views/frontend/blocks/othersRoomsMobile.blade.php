<section class="page-section" style="letter-spacing: 0;line-height: 1; margin: 45px 1em;">
  <div class="heading-block center push-20">
    <h3 class="green">NUESTROS APARTAMENTOS</h3>
  </div>
  <?php
  $oRooms = \App\RoomsType::all();
  ?>
  <?php foreach ($oRooms as $item): ?>
    <?php
    $photo = App\RoomsPhotos::where('gallery_key', $item->id)->orderBy('main', 'DESC')->orderBy('position')->first();
    if ($photo):
    ?>
    <div class=" row animatable" data-aos="zoom-in">
      <a href="{{route('web.apto',$item->name)}}" title="{{$item->title}}" style="width: 100%;">
        <div class="section noborder center"
             style="background-image: url({{getCloudfl($photo->file_rute)}}/mobile/{{$photo->file_name}}); padding: 70px 0; margin: 20px 0;background-size: cover;
    background-repeat: no-repeat;"
             data-stellar-background-ratio="0.4">
          <h3 class="h2 text-center white text-white font-w800 wst"
              style="text-shadow: 2px 1px #000;">{{$item->title}}</h3>
        </div>
      </a>
    </div>
              <?php endif; ?>
  <?php endforeach; ?>
</section>
