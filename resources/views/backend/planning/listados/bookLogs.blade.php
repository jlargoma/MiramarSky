<div class="chat-lst" id="chats">
  @foreach($allLogs as $item)
  <div class="chat-item">
    @if($item->user_id)
    @if($item->action == 'change_status')
    <div class="status">
      <div class="chat-text">{{$item->subject}} 
        @if(isset($roomLst[$item->room_id]))<span> {{$roomLst[$item->room_id]}}</span> @endif
      </div>
      <div class="chat-user">
        @if(isset($userLst[$item->user_id])) 
          {{$userLst[$item->user_id]}} 
        @else
          Admin
        @endif
      </div>
      <div class="chat-date">{{$item->created_at->format('d.m.Y H:i')}}</div>
    </div>
    @else
    <div class="admin">
      <div class="chat-text">
        @if($item->action == 'user_mail_response')
        {{ $item->content }}
        @else
        {{$item->subject}}
        <a href="#" class="see_more" data-id="{{$item->id}}">ver mas</a>
        @endif
        <a href="#" class="see_more" data-id="{{$item->id}}">
          <i class="fa fa-eye"></i>
        </a>
      </div>
      <div class="chat-user">
        @if(isset($userLst[$item->user_id])) 
          {{$userLst[$item->user_id]}} 
        @else
          Admin
        @endif
      </div>
      <div class="chat-date">{{$item->created_at->format('d.m.Y H:i')}}</div>
    </div>
    @endif
    @else
    <div class="user">
      <div class="chat-text">
        {{ $item->content }}
        <a href="#" class="see_more" data-id="{{$item->id}}"><i class="fa fa-eye"></i></a>
      </div>
      <div class="chat-date">{{$item->created_at->format('d.m.Y H:i')}}</div>
    </div>
    @endif
  </div>
  @endforeach
</div>
  <button class="btn btn-success btn-cons m-b-10" type="button"
          data-toggle="modal" data-target="#modalResponseEmail">Enviar Nueva Respuesta</button>

  <div class="modal fade slide-up in" id="modalResponseEmail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xd">
      <div class="modal-content-classic">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
    <i class="fa fa-times fa-2x" style="color: #000!important;"></i>
</button>
        <form method="POST" action="/admin/response-email">
          <input type="hidden" id="booking" name="booking" value="{{$bookID}}">
          <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
          <div class="form-group text-left">
            <label for="subject">Asunto</label>
            <input type="text" class="form-control" id="subject" name="subject" value="Repuesta desde {{env('APP_NAME')}}" />
          </div>
          <div class="form-group text-left">
            <label for="content">Contenido</label>
            <textarea class="form-control" id="content" name="content" rows="5"></textarea>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary" >Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="modal fade slide-up in" id="modal_seeLog" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xd text-left">
      <div class="modal-content-classic">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="position: absolute; top: 0px; right: 10px; z-index: 100">
    <i class="fa fa-times fa-2x" style="color: #000!important;"></i>
</button>
        <div class="msl-data">
          <label>Asunto</label>
          <div id="msl_subj"></div>
        </div>
        <div class="msl-data">
          <label>Fecha</label>
          <div id="msl_date"></div>
        </div>
        <div class="msl-data">
          <label>Usuario</label>
          <div id="msl_user"></div>
        </div>
        <div class="msl-data">
          <label>Apto</label>
          <div id="msl_room"></div>
        </div>
        <div class="msl-data">
          <label>Mensaje</label>
          <div id="msl_content"></div>
        </div>
      </div>
    </div>
  </div>
<style>
  .chat-lst{
    max-width: 90%;
    height: 450px;
    margin: 1em auto;
    overflow-y: scroll;
    padding: 7px;
    border-radius: 8px;
    box-shadow: -6px 4px 5px #848282;
  }

  .chat-item {
    clear: both;
    overflow: auto;
    margin-bottom: 0.7em;
  }
  .chat-item .admin {
    padding: 3px 12px;
    float: left;
    max-width: 80%;
  }
   .chat-item .user {
    padding: 3px 12px;
    float: right;
    max-width: 80%;
  }
  .chat-item .admin .chat-text{
    background-color: rgba(41, 93, 155, 0.22);
    border-radius: 14px;
    padding: 7px 12px;
    box-shadow: 3px 3px 11px 0px #d8d8d8;
    text-align: left;
  }
  .chat-item .user .chat-text{
    padding: 7px 12px;
    border-radius: 14px;
    background-color: #fbfbfb;
    box-shadow: 3px 3px 11px 0px #d8d8d8;
    text-align: left;
  }
  .chat-item .status .chat-text{
    color: #295d9b;
    border-radius: 14px;
    padding: 5px 12px;
    font-weight: 800;
    text-transform: uppercase;
    text-align: left;
  }
  .chat-item .status .chat-text span{
    font-style: italic;
  }

  .chat-item .admin .chat-user,
  .chat-item .status .chat-user {
    float: left;
    color: #867d7d;
    font-weight: 600;
    padding: 1px 10px;
  }
  .chat-date {
    font-size: 0.8em;
    padding-top: 4px;
    color: #867d7d;
    
    white-space: nowrap;
  }
  .chat-item .user .chat-date {
    float: right;
  }

 
  

</style>