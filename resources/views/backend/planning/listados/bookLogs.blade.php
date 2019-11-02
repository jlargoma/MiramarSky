<div  id="chats">
  <a href="#"  id="loadchatboxMore" data-date="{{$date}}">ver anteriores..</a>
  <div class="info-date"><b>{{getMonthsSpanish($month)}} {{$year}}</b></div>
  @if($allLogs)
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
@endif
</div>