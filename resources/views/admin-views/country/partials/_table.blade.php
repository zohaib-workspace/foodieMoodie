@foreach($countries as $key=>$country)
    <tr>
        <td>{{$key+1}}</td>
        <td class="text-center">
            <span class="move-left">
                {{$country->id}}
            </span>
        </td>
        <td class="pl-5">
            <span class="d-block font-size-sm text-body">
                {{$country['name']}}
            </span>
        </td>
        <td class="pl-5">
            <span class="d-block font-size-sm text-body">
                {{$country['sortname']}}
            </span>
        </td>
        <td class="pl-5">
            <span class="d-block font-size-sm text-body">
                {{$country['phonecode']}}
            </span>
        </td>
        <td>
                                    <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$country->id}}">
                                            <input type="checkbox" onclick="status_form_alert('status-{{$country['id']}}','{{ translate('Are You Sure') }}', event)" class="toggle-switch-input" id="stocksCheckbox{{$country->id}}" {{$country->status?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        <form action="{{route('admin.country.status',[$country['id'],$country->status?0:1])}}" method="get" id="status-{{$country['id']}}">
                                        </form>
                                    </td>
                                    <td>
                                    <div class="btn--container justify-content-center">
                                            <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                                                href="{{route('admin.country.edit',[$country['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.country')}}"><i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-sm btn--warning btn-outline-warning action-btn" href="javascript:"
                                            onclick="form_alert('country-{{$country['id']}}','Want to delete this country ?')" title="{{translate('messages.delete')}} {{translate('messages.country')}}"><i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{route('admin.country.delete',[$country['id']])}}" method="post" id="country-{{$country['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
    </tr>
@endforeach
