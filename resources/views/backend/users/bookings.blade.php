<style>
  .bByUser {
    padding: 4px 10px;
  }

  .bByUser th {
    background-color: #48b0f7;
    color: #fff !important;
    text-align: center;
  }

  .bByUser td {
    text-align: center;
  }

  .bByUser h2 {
    text-align: center;
  }

  .bByUser span.role {
    font-size: 14px;
    font-weight: bold;
    padding: 0 5px;
  }

  .bByUser .total {
    text-align: center;
    font-size: 16px;
    font-weight: bold;
    margin: 4px auto 1em;
  }
  .bByUser .table-responsive {
    max-height: 70vh;
  }
</style>
<div class="bByUser">
  <h2>{{$oUser->name}}<span class="role">{{strtoupper($oUser->role)}}</span></h2>
  <div class="total">Total {{moneda($tPVP)}}</div>



  <div class="table-responsive">
    <table class="table ">
      <thead>
        <tr>
          <th class="text-left"> Cliente</th>
          <th style="max-width:65px!important;">&nbsp;</th>
          <th style="width: 10%!important"> Pax</th>
          <th style="width: 10%!important"> Apart</th>
          <th style="width: 65px!important"> IN</th>
          <th style="width: 65px!important"> OUT</th>
          <th style="width: 6%!important"><i class="fa fa-moon-o"></i></th>
          <th style="width: 12%!important"> Estado</th>
          
          <th style="min-width: 120px;">Pagos</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($lstBooks as $b) : ?>
          <tr class="" data-id="{{$b->id}}">
            <td class="fix-col td-b1 text-left">
              <div class="fix-col-data">
                <?php if ($b->agency != 0) : ?>
                  <img class="img-agency" src="/pages/<?php echo strtolower($b->getAgency($b->agency)) ?>.png" />
                <?php endif ?>
                <a class="update-book" data-id="<?php echo $b->id ?>" href="{{url ('/admin/reservas/update')}}/<?php echo $b->id ?>">
                  <?php echo $b->customer['name']  ?>
                </a>
              </div>
            </td>
            <td class="text-center" style="max-width:65px!important;">


            <?php if (!empty($b->comment) || !empty($b->book_comments) || !empty($b->book_owned_comments)): ?>
                    <div data-booking="<?php echo $b->id; ?>" class="showBookComm pull-right" >
                      <i class="far fa-comment-dots" style="color: #000;" aria-hidden="true"></i>
                      <div class="BookComm tooltiptext"></div>
                    </div>

                 <?php endif;?>
            </td>
            <td>
              <?php if ($b->real_pax > 6) : ?>
                <?php echo $b->real_pax ?><i class="fa fa-exclamation" aria-hidden="true" style="color: red"></i>
              <?php else : ?>
                <?php echo $b->pax ?>
              <?php endif ?>

            </td>
            <td>
              <?php
              if ($b->room) {
                $room = $b->room;
                echo substr($room->nameRoom . " - " . $room->name, 0, 15);
              }
              ?>
            </td>
            <td class="td-date" data-order="{{$b->start}}">
              <?php echo dateMin($b->start) ?>
            </td>
            <td class="td-date" data-order="{{$b->finish}}">
              <?php echo dateMin($b->finish) ?>
            </td>
            <td><?php echo $b->nigths ?></td>
            <td>
              {{$b->getStatus($b->type_book)}}
            </td>
            
            <td>
              <?php
              echo $b->showPricePlanning($lstBooks);
              ?>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>

</div>
