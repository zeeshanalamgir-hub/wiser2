@if (isset($pageData))
    @if(!empty($pageData['childData']))
        @foreach ($pageData['childData'] as $skillCategory)
            <div class="mt-1">
                <input id="skill-cat-{{ $skillCategory->id }}" class="form-check-input skill-cat-checkbox" type="checkbox" value="{{ $skillCategory->id }}" data-id="{{ $skillCategory->id }}">
                <label class="form-check-label ms-1" for="skill-cat-box-{{ $skillCategory->id }}">
                    {{ Str::limit($skillCategory->name, 25, '...') }}
                </label>
                <div id="skills-{{ $skillCategory->id }}" class="ms-1 skills-div" style="display: none;">
                    <!-- Skills will be dynamically loaded here -->
                </div>
            </div>
            {{-- <div class="single_search_boxed px-4 pt-0 br-bottom ">
                <div class="widget-boxed-header">
                    <h4>
                        <a href="#skillscat{{ $childcategory->id }}" data-toggle="collapse" aria-expanded="false"
                            role="button" class="ft-medium fs-md pb-0 collapsed"><?= $childcategory->name ?></a>
                    </h4>
                </div>
                <div class="widget-boxed-body collapse" id="skillscat{{ $childcategory->id }}" data-parent="#skills">
                    <div class="side-list no-border">
                        <!-- Single Filter Card -->
                        <div class="single_filter_card">
                            <div class="card-body p-0">
                                <div class="inner_widget_link">
                                    <ul class="no-ul-list filter-list ">
                                        @php
                                            $skills = DB::table('skills')
                                                ->where('skill_category_id', $childcategory->id)
                                                ->get();
                                        @endphp

                                        @foreach ($skills as $skill)
                                            <li>
                                                <input id="childcat-box-{{ $skill->id }}"
                                                    class="checkbox-custom skills-checkbox" type="checkbox"
                                                    value="{{ $skill->id }}" name="{{ $skill->name }}">
                                                <label for="childcat-box-{{ $skill->id }}"
                                                    class="checkbox-custom-label"> {{ $skill->name }}</label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        @endforeach
    @endif
@endif
