@foreach($cities as $key=>$city)
    <tr>
        <td>{{$key+1}}</td>
        <td class="text-center">
            <span class="move-left">
                {{$city->id}}
            </span>
        </td>
        <td class="pl-5">
            <span class="d-block font-size-sm text-body">
            {{$city['country']['name']}}
            </span>
        </td>
        <td class="pl-5">
            <span class="d-block font-size-sm text-body">
            {{$city['name']}}
            </span>
        </td>
        <td class="pl-5">
            <span class="d-block font-size-sm text-body">
            {{$city['provence']}}
            </span>
        </td>
        <td>
                                    <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$city->id}}">
                                            <input type="checkbox" onclick="status_form_alert('status-{{$city['id']}}','{{ translate('Are You Sure') }}', event)" class="toggle-switch-input" id="stocksCheckbox{{$city->id}}" {{$city->status?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <form action="{{route('admin.city.status',[$city['id'],$city->status?0:1])}}" method="get" id="status-{{$city['id']}}">
                                        </form>
                                    </td>
                                    <td>
                                    <div class="btn--container justify-content-center">
                                            <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                                                href="{{route('admin.city.edit',[$city['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.city')}}"><i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-sm btn--warning btn-outline-warning action-btn" href="javascript:"
                                            onclick="form_alert('city-{{$city['id']}}','Want to delete this city ?')" title="{{translate('messages.delete')}} {{translate('messages.city')}}"><i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{route('admin.city.delete',[$city['id']])}}" method="post" id="city-{{$city['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
    </tr>
@endforeach
