@foreach($business as $key=>$slider)
                                <tr>
                                    <td>{{$key+$business->firstItem()}}</td>
                                    <td> <img onerror="this.src='{{asset('public/assets/admin/img/160x160/img1.jpg')}}'"
                                                    src="{{asset('storage/app/public/campaign')}}/{{$slider['image']}}" style="width:70px"></td>
                                    <td>
                                        <span class="d-block text-body">{{Str::limit($slider['title'],25, '...')}}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="d-block text-body">{{Str::limit($slider['description'],25, '...')}}
                                        </span>
                                    </td>
                                    <td>
                                        <label class="toggle-switch toggle-switch-sm" for="statusCheckbox{{$slider->id}}">
                                            <input type="checkbox" name="status" onclick="location.href='{{route('admin.business_slider.status',[$slider['id'],$slider->status?0:1])}}'" class="toggle-switch-input" id="statusCheckbox{{$slider->id}}" {{$slider->status?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="btn--container justify-content-center">
                                            <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                                                href="{{route('vendor.business_slider.edit',[$slider['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.business_slider')}}"><i class="tio-edit"></i>
                                            </a>
                                            <a class="btn btn-sm btn--danger btn-outline-danger action-btn" href="javascript:"
                                                onclick="form_alert('slider-{{$slider['id']}}','{{translate('messages.Want_to_delete_this_item')}}')" title="{{translate('messages.delete')}} {{translate('messages.business_slider')}}"><i class="tio-delete-outlined"></i>
                                            </a>
                                            <form action="{{route('vendor.business_slider.delete',[$slider['id']])}}"
                                                        method="post" id="slider-{{$slider['id']}}">
                                                @csrf @method('delete')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach