<style>
    h1{
        margin-bottom: 0 !important;
    }

    .stars {
        width: auto;
        margin: 7px auto;
        padding-bottom: 2em;
        text-align: center;
    }
    .stars i{
        color: #f28d7b;
        font-size: 2em
    }
    .img-colmun .gallery-top{
        margin-bottom: 15px;
    }
    .img-colmun .gallery-top .swiper-slide{
        min-height: 350px;
        background-size: cover;
    }
    .img-colmun .gallery-thumbs .swiper-slide{
        height: 75px;
        background-size: cover;
    }
    .content-colmun{
        margin-bottom: 25px;
    }
    .content-colmun .tab-content {
        background-color: #EDEDED;
        padding: 15px;
        color: #000;
        border: 1px solid #000;
    }
    .content-colmun .tab-content .active {
        color: inherit;
    }
    .content-colmun .nav-tabs li a{
        padding: 20px 25px;
        font-weight: 700;
        line-height: 1;
        margin-bottom: -2px;
        display: block;
        color: #303030;
    }
    .content-colmun .nav-tabs li a:hover{
        text-decoration: none;
    }
    .content-colmun .nav-tabs li a.active{
        background-color: #EDEDED;
        color: #000;
        border: 1px solid #000;
        border-bottom: none;
        color: #FF907C;
    }
</style>
<section >
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-7 img-colmun">
                <div class="clearfix">
                    <!-- Swiper -->
                    <div class="swiper-container gallery-top">
                        <div class="swiper-wrapper">
                            <?php foreach ($photos as $img): ?>
                              <div class="swiper-slide" style="background-image:url({{url($img->file_rute.'/'.$img->file_name)}})"></div>
                            <?php endforeach; ?>

                        </div>
                        <!-- Add Arrows -->
                        <div class="swiper-button-next swiper-button-white"></div>
                        <div class="swiper-button-prev swiper-button-white"></div>
                    </div>
                    <div class="swiper-container gallery-thumbs">
                        <div class="swiper-wrapper">
                            <?php foreach ($photos as $img): ?>
                              <div class="swiper-slide" style="background-image:url({{url($img->file_rute.'/'.$img->file_name)}})"></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-5 content-colmun" >
                @if($texts)

                <ul class="nav nav-tabs">
                    <li><a class="active" data-toggle="tab" href="#detail">Detalles</a></li>
                    <li><a data-toggle="tab" href="#charact">Características</a></li>
                </ul>

                <div class="tab-content">
                    <div id="detail" class="tab-pane fade in show active">
                        {!!$texts['detail'] !!}
                    </div>
                    <div id="charact" class="tab-pane fade">
                        {!!$texts['charact'] !!}
                    </div>
                </div>

                @endif
            </div>
        </div>
    </div>


    <?php if ($url == '9F'): ?>
      <input type="hidden" id="nameAptoFoto" value="ATICO DUPLEX DE LUJO <?php echo strtoupper($room->nameRoom); ?>">
    <?php else: ?>
      <input type="hidden" id="nameAptoFoto" value="<?php echo strtoupper($aptoHeading . ' ' . $room->nameRoom); ?>">
    <?php endif ?>
</section>
