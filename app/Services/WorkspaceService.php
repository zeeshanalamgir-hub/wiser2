<?php

namespace App\Services;

use App\Models\Userbiding;
use App\Models\Acceptedbid;
use App\Models\Buyercounteroffer;
use App\Models\BuyerCounterOfferData;

class WorkspaceService
{
    /**
     * Get accepted milestones for a given job based on the accepted offer.
     *
     * @param int $bidId
     * @return array
     */
    public function getAcceptedMilestones($bidId)
    {
        // Find the accepted bid record
        $acceptedBid = Acceptedbid::where('bid_id', $bidId)->first();

        if (!$acceptedBid) {
            return ['milestones' => [], 'type' => null]; // Return empty milestones and null type if no accepted bid is found
        }

        // Find the associated user bidding to get seller_offer_responce
        $userBidding = Userbiding::where('id', $bidId)->where('seller_offer_responce', 2)->first();

        if (!$userBidding) {
            return ['milestones' => [], 'type' => null]; // Return empty milestones and null type if no matching user bidding is found
        }

        // Get the buyer counter offer associated with this bid
        $buyerCounterOffer = Buyercounteroffer::where('job_id', $userBidding->job_id)->first();

        if (!$buyerCounterOffer) {
            return ['milestones' => [], 'type' => null]; // Return empty milestones and null type if no buyer counter offer is found
        }

        // Fetch the buyer counter offer data (will be used in all cases)
        $buyerCounterOfferData = Buyercounterofferdata::where('counter_offer_id', $buyerCounterOffer->id)
            ->where('status', 1)
            ->first(); // Fetch only the first row

        // If no buyer counter offer data found, return empty array
        if (!$buyerCounterOfferData) {
            return ['milestones' => [], 'type' => $buyerCounterOffer->type];
        }

        // Initialize an array to store milestones
        $milestones = [];

        // Handle milestones based on the buyer counter offer type
        if ($buyerCounterOffer->type === 'bymilestone') {
            // Decode JSON arrays for bymilestone
            $titles = json_decode($buyerCounterOfferData->title, true);
            $amounts = json_decode($buyerCounterOfferData->amount, true);
            $days = json_decode($buyerCounterOfferData->days, true);

            if (is_array($titles) && is_array($amounts) && is_array($days)) {
                // Loop through each title, amount, and days to create milestone objects
                for ($i = 0; $i < count($titles); $i++) {
                    $milestones[] = [
                        'title' => $titles[$i] ?? 'N/A',
                        'amount' => $amounts[$i] ?? 0,
                        'days' => $days[$i] ?? 'N/A',
                        'milestone_status' => $buyerCounterOfferData->milestone_status ?? null, // Original status for reference
                        'milestone_status_text' => $buyerCounterOfferData->milestone_status_text, // Accessor method call
                    ];
                }
            }
        } elseif ($buyerCounterOffer->type === 'byproject') {
            // Handle 'byproject' case
            $milestones[] = [
                'title' => 'Project Payment',
                'amount' => $buyerCounterOfferData->amount ?? 0,
                'days' => $buyerCounterOfferData->days ?? 'N/A', // Use single days if no array
                'milestone_status' => $buyerCounterOfferData->milestone_status ?? null, // No milestone status for a project-based payment
                'milestone_status_text' => $buyerCounterOfferData->milestone_status_text, // No milestone status text for a project-based payment
            ];
        } elseif ($buyerCounterOffer->type === 'per_hour') {
            // Handle 'per_hour' case
            return ['milestones' => [], 'type' => $buyerCounterOffer->type]; // Return empty milestones and per_hour type
        }

        // Return milestones and type
        return ['milestones' => $milestones, 'type' => $buyerCounterOffer->type];
    }
    public function getAcceptedMilestonesByBuyerCounterOffer($buyerCounterOfferDataId)
    {
        // Fetch the buyer counter offer data
        $buyerCounterOfferData = Buyercounterofferdata::where('id', $buyerCounterOfferDataId)->where('status', 1)->first();

        if (!$buyerCounterOfferData) {
            return ['milestones' => [], 'type' => null];
        }

        // Fetch the buyer counter offer
        $buyerCounterOffer = Buyercounteroffer::where('id', $buyerCounterOfferData->counter_offer_id)->first();

        if (!$buyerCounterOffer) {
            return ['milestones' => [], 'type' => null];
        }

        // Initialize milestones array
        $milestones = [];

        // Handle milestones based on type
        if ($buyerCounterOffer->type === 'bymilestone') {
            $titles = json_decode($buyerCounterOfferData->title, true);
            $amounts = json_decode($buyerCounterOfferData->amount, true);
            $days = json_decode($buyerCounterOfferData->days, true);

            if (is_array($titles) && is_array($amounts) && is_array($days)) {
                for ($i = 0; $i < count($titles); $i++) {
                    $milestones[] = [
                        'title' => $titles[$i] ?? 'N/A',
                        'amount' => $amounts[$i] ?? 0,
                        'days' => $days[$i] ?? 'N/A',
                        'milestone_status' => $buyerCounterOfferData->milestone_status ?? null,
                        'milestone_status_text' => $buyerCounterOfferData->milestone_status_text,
                    ];
                }
            }
        } elseif ($buyerCounterOffer->type === 'byproject') {
            $milestones[] = [
                'title' => 'Project Payment',
                'amount' => $buyerCounterOfferData->amount ?? 0,
                'days' => $buyerCounterOfferData->days ?? 'N/A',
                'milestone_status' => $buyerCounterOfferData->milestone_status ?? null,
                'milestone_status_text' => $buyerCounterOfferData->milestone_status_text,
            ];
        } elseif ($buyerCounterOffer->type === 'hourly') {
            return ['milestones' => [], 'type' => 'hourly']; // For hourly projects, no milestones
        }

        return ['milestones' => $milestones, 'type' => $buyerCounterOffer->type];
    }
}
