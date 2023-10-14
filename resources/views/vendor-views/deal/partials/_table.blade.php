@foreach($deals as $key=>$deal)
    <tr>
        <td>{{$key+1}}</td>
        <td>
            <a class="media align-items-center" href="{{route('vendor.deal.view',[$deal['id']])}}">
                <img class="avatar avatar-lg mr-3" src="{{asset('storage/app/public/product')}}/{{$deal['image']}}"
                        onerror="this.src='{{asset('/public/assets/admin/img/100x100/food-default-image.png')}}'" alt="{{$deal->title}} image">
                <div class="media-body">
                    <h5 class="text-hover-primary mb-0">{{Str::limit($deal['title'],20,'...')}}</h5>
                </div>
            </a>
        </td>
        <td>
        {{$deal->number_of_items}}
        </td>
        <td>
            <div class="text-right mx-auto mw-36px">
            <!-- Static Symbol -->
            {{ \App\CentralLogics\Helpers::currency_symbol() }}
            <!-- Static Symbol -->
                {{($deal['price'])}}
            </div>
        </td>
        <td>
            <div class="d-flex">
                <div class="mx-auto">
                    <label class="toggle-switch toggle-switch-sm mr-2" for="stocksCheckbox{{$deal->id}}">
                        <input type="checkbox" onclick="location.href='{{route('vendor.deal.status',[$deal['id'],$deal->status=='Active'?0:1])}}'"class="toggle-switch-input" id="stocksCheckbox{{$deal->id}}" {{$deal->status=='Active'?'checked':''}}>
                        <span class="toggle-switch-label">
                            <span class="toggle-switch-indicator"></span>
                        </span>
                    </label>
                </div>
            </div>
        </td>
        <td>
            <div class="btn--container justify-content-center">
                <a class="btn action-btn btn--primary btn-outline-primary"
                    href="{{route('vendor.deal.edit',[$deal['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.deal')}}"><i class="tio-edit"></i>
                </a>
                <a class="btn action-btn btn--danger btn-outline-danger" href="javascript:"
                    onclick="form_alert('deal-{{$deal['id']}}','{{ translate('messages.Want to delete this item ?') }}')" title="{{translate('messages.delete')}} {{translate('messages.Deal')}}"><i class="tio-delete-outlined"></i>
                </a>
                <form action="{{route('vendor.deal.delete',[$deal['id']])}}"
                        method="post" id="deal-{{$deal['id']}}">
                    @csrf @method('delete')
                </form>
            </div>
        </td>
    </tr>
@endforeach
