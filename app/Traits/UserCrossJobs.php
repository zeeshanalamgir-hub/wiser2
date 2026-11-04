<?php

namespace App\Traits;

use App\Models\Jobskill;
use App\Models\Skill;
use App\Models\UserProfileSkill;

trait UserCrossJobs
{

    /**
     * Calculate platform fee percentage based on matched skills.
     *
     * @param  int  $userId
     * @param  int  $jobId
     * @return \Illuminate\Support\Collection
     */
    public function getMatchedSkills($userId, $jobId)
    {
        // Get all skills required for the job
        $jobSkills = Jobskill::where('job_id', $jobId)->with('Skill')->get();

        // Get the IDs of the user's skills that match the job skills
        $matchingSkills = UserProfileSkill::whereIn('skill_id', $jobSkills->pluck('skill_id'))
                                           ->where('user_id', $userId)
                                           ->pluck('skill_id')
                                           ->toArray();

        // Iterate through job skills and add the matched key
        $jobSkills->transform(function ($jobSkill) use ($matchingSkills) {
            $jobSkill->matched = in_array($jobSkill->skill_id, $matchingSkills);
            return $jobSkill;
        });

        return $jobSkills;
    }


}
