@if(isset($pageData))
    @if(!empty($pageData['skillsData']))
        @foreach($pageData['skillsData'] as $skill)
            <div class="ms-2">
                <input id="skills-box-{{ $skill->id }}" class="form-check-input skills-checkbox" type="checkbox" value="{{ $skill->id }}" data-id="{{ $skill->id }}">
                <label class="form-check-label ms-1" for="skills-box-{{ $skill->id }}">
                    {{ Str::limit($skill->name, 20, '...') }}
                </label>
            </div>
        @endforeach
    @endif
@endif
