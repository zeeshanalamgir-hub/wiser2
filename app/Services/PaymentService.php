<?php

namespace App\Services;
use App\Models\PaymentSession;

class PaymentService
{
    public function calculateFees($payableId, $payableType)
    {
        // Fetch the related payment session
        $paymentSession = PaymentSession::where('id', $payableId)
            ->where('payable_type', $payableType)
            ->first();
        
        $totalAmount = 0;
        $paymentProcessingFee = 0;
        $calculatedBuyerServiceFee = 0;
        $total = 0;
        $actualReceivable = 0;
        $packagePrice = 0;
        $addonPricesSum = 0;
        $calculatedSellerServiceFee = 0;
        $extraServicesTotal = 0;
        $platform_fee_amount = 0;

        if ($paymentSession && $paymentSession->metadata) {
            $metadata = json_decode($paymentSession->metadata ?? '{}', true);
            $extraServices = json_decode($metadata['extra_services_data'] ?? '[]', true);

            $extraServicesTotal = collect($extraServices)->sum(function ($service) {
                return (float) ($service['price'] ?? 0);
            });
            // Decode the JSON stored in the meta_data column
            $metaData = json_decode($paymentSession->metadata, true);
            // Check if 'payment_processing_fee' and 'buyer_service_fee' exist in the decoded meta data
            if (isset($metaData['payment_processing_fee'])) {
                $paymentProcessingFee = $metaData['payment_processing_fee'];
            }

            if (isset($metaData['platform_fee_amount'])) {
                $platform_fee_amount = $metaData['platform_fee_amount'];
            }

            if (isset($metaData['package_price'])) {
                $buyerServiceFeePercentage = $metaData['buyer_service_fee'];
                $packagePrice = floatval($metaData['package_price']);

                // Default 0 if no addons
                $addonPricesSum = 0;
                if (!empty($metaData['addon_prices']) && is_array($metaData['addon_prices'])) {
                    $addonPricesSum = array_sum($metaData['addon_prices']);
                }

                $totalAmount = $packagePrice + $addonPricesSum;
                // $calculatedBuyerServiceFee = ($buyerServiceFeePercentage / 100) * $totalAmount;
                $calculatedBuyerServiceFee = $buyerServiceFeePercentage;
               

                // Seller Platform Fee
                $sellerServiceFeePercentage = $metaData['seller_platform_fee'] ?? 0;
                $calculatedSellerServiceFee = ($sellerServiceFeePercentage / 100) * $totalAmount;
                $actualReceivable = $totalAmount - $calculatedSellerServiceFee;
            }

            if (isset($metaData['total'])) {
                $total = $metaData['total'];
            }
        }

        return [
            'paymentProcessingFee' => $paymentProcessingFee,
            'calculatedBuyerServiceFee' => $calculatedBuyerServiceFee,
            'packagePrice' => $packagePrice,
            'addOnsPrice' => $addonPricesSum,
            'total' => $total,
            'totalBeforeFee' => $totalAmount,
            'calculatedSellerServiceFee' => $calculatedSellerServiceFee,
            'actualReceivable' => $actualReceivable,
            'extraServicesTotal'=>$extraServicesTotal,
            'platform_fee_amount'=>$platform_fee_amount ?? 0,
        ];
    }

}
