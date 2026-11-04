 @if(isset($pageData))
  @if(!empty($pageData['lngData'][0]))


 @if(!empty($pageData['lngData'])) 
                        @foreach($pageData['lngData'] as $key=>$lng)
                         
                    <!-- Single -->
                  <ul class=" no-ul-list filter-list ">
                    <li>
                       <input id="{{$lng->id}}" class="checkbox-custom otherlangbtn" name="{{$lng->name}}"  value="{{$lng->name}}" type="checkbox">
                        <label for="{{$lng->id}}" class="checkbox-custom-label">{{$lng->name}}</label>
                    </li>
                     </ul>                               
                    @endforeach
 @else
                   
                    @endif










                
      @endif
  @endif
