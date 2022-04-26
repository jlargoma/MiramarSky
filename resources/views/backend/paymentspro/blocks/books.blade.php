<div class="table-responsive">
    <table class="table table-bookings">
        <thead>
          <th class="text-left bg-complete text-white">Cliente</th>
          <th class="text-center bg-complete text-white">Pers</th>
          <th class="text-center bg-complete text-white">IN</th>
          <th class="text-center bg-complete text-white">OUT</th>
          <th class="text-center bg-complete text-white">ING. PROP</th>
          <th class="text-center bg-complete text-white">Apto</th>
          <th class="text-center bg-complete text-white">Park.</th>
          <th class="text-center bg-complete text-white">Luz</th>
        <?php if ($room != 'all'): ?>
          <?php if ($room->luxury == 1): ?>
    								<th class="text-center bg-complete text-white">Sup.Lujo</th>
          <?php endif ?>
        <?php endif ?>
								<th class="text-center bg-complete text-white">&nbsp;</th>
        </thead>
        <tbody>
            <?php foreach ($books as $book): ?>
              <tr>
                  <td class="text-left" data-id="<?php echo $book->id; ?>">
                      <a href="{{ url('/admin/reservas/update') }}/ <?php echo $book->id ?>">
                          <?php echo ucfirst(strtolower($book->customer->name)) ?>
                      </a>
                  </td>
                  <td class="text-center"><?php echo $book->pax ?>
                  <?php if (!empty($book->comment) || !empty($book->book_comments) || !empty($book->book_owned_comments)): ?>
                    <div data-booking="<?php echo $book->id; ?>" class="showBookComm pull-right" >
                      <i class="far fa-comment-dots" style="color: #000;" aria-hidden="true"></i>
                      <div class="BookComm tooltiptext"></div>
                    </div>
                <?php endif ?>
               </td>
                  <td class="text-center">
                    {{ convertDateToShow_text($book->start)}}
                  </td>
                  <td class="text-center">
                    {{ convertDateToShow_text($book->finish)}}
                  </td>
                  <td class="text-center total">
                      {{moneda($book->get_costProp(),false,2)}}
                  </td>
                  <td class="text-center">
                      <?php if ($book->type_book != 7 && $book->type_book != 8): ?>
                        {{moneda($book->cost_apto)}}
                      <?php endif ?>
                  </td>
                  <td class="text-center">
                    <?php if ($book->type_book != 7 && $book->type_book != 8): ?>
                    {{moneda($book->cost_park,true,2)}}
                      <?php endif ?>
                  </td>
                    <td class="text-center">{{moneda($book->luz_cost)}}</td>
                    <td class="text-center">
                        {{moneda($book->get_costLujo(),false,2)}}
                    </td>
                    <td class="text-center">
                      <?php if (!empty($book->book_owned_comments) && $book->promociones != 0): ?>
                            <img src="/pages/oferta.png" style="width: 40px;">
                      <?php endif ?>
                    </td>
              </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>