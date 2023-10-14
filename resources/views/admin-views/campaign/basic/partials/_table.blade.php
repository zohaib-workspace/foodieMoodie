@foreach($campaigns as $key=>$campaign)
    <tr>
        <td>{{$key+1}}</td>
        <td>
            <span class="d-block text-body"><a href="{{route('admin.campaign.view',['basic',$campaign->id])}}">{{Str::limit($campaign['title'],25, '...')}}</a>
            </span>
        </td>
       
        <td>
            <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$campaign->id}}">
                <input type="checkbox" onclick="location.href='{{route('admin.campaign.status',['basic',$campaign['id'],$campaign->status?0:1])}}'"class="toggle-switch-input" id="stocksCheckbox{{$campaign->id}}" {{$campaign->status?'checked':''}}>
                <span class="toggle-switch-label">
                    <span class="toggle-switch-indicator"></span>
                </span>
            </label>
        </td>
        <td>
            <div class="btn--container justify-content-center">
                <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                    href="{{route('admin.campaign.edit',['basic',$campaign['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.campaign')}}"><i class="tio-edit"></i>
                </a>
                <a class="btn btn-sm btn--danger btn-outline-danger action-btn" href="javascript:"
                    onclick="form_alert('campaign-{{$campaign['id']}}','{{translate('messages.Want_to_delete_this_item')}}')" title="{{translate('messages.delete')}} {{translate('messages.campaign')}}"><i class="tio-delete-outlined"></i>
                </a>
                <form action="{{route('admin.campaign.delete',[$campaign['id']])}}"
                            method="post" id="campaign-{{$campaign['id']}}">
                    @csrf @method('delete')
                </form>
            </div>
        </td>
    </tr>
@endforeach
