@if(isset($pageData))
  @if(!empty($pageData['subsData'][0]))


 @if(!empty($pageData['subsData'])) 

                                                        <ul class="no-ul-list filter-list filteredsubs">
                                                            
                                                            @foreach($pageData['subsData'] as $key=>$subcategory)
                                                            <li>
                                                                <input id="subcat-box-<?= $subcategory->id?>" class="checkbox-custom subcategory-checkbox" type="checkbox" value="<?= $subcategory->id?>" name="<?= $subcategory->id?>">
                                                                <label for="subcat-box-<?= $subcategory->id?>" class="checkbox-custom-label"> <?= $subcategory->name?></label>
                                                            </li>
                                                                  
      @endforeach

                                                        </ul>

                                   @endif

      @endif
  @endif