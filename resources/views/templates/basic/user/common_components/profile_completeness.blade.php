@php
    $user = Auth::user();
    $userId = $user->id;
    $percentageComplete = 0;
    $percentageIncomplete = 100;
    if ($user->is_freelancer == 1 && $user?->completed_step) {
        //$percentageComplete = $user?->completed_step * 25;
        $percentageComplete = $user?->completed_step * 11.11;
        $percentageIncomplete = 100 - $percentageComplete;
        $percentageComplete = round($percentageComplete);
    }
    if ($user->is_employer == 1) {
        $comp_steps = $user->completed_step;
        if($comp_steps == 5){
            $comp_steps = 5;
        }
        $percentageComplete = $comp_steps * 20;
        $percentageIncomplete = 100 - $percentageComplete;
    }
@endphp
<div class="progress">
    <div class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-label="Success striped example"
        style="width: {{ $percentageComplete }}%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100">
        {{ (int) $percentageComplete }} %</div>
</div>
