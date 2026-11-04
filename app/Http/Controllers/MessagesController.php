<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\ChMessage as Message;
use Illuminate\Support\Facades\Auth;
use App\Models\ChFavorite as Favorite;
use Illuminate\Support\Facades\Response;
use Chatify\Facades\ChatifyMessenger as Chatify;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MessagesController extends Controller
{
    protected $perPage = 30;

    /**
     * Authenticate the connection for pusher
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function pusherAuth(Request $request)
    {
        return Chatify::pusherAuth(
            $request->user(),
            Auth::user(),
            $request['channel_name'],
            $request['socket_id']
        );
    }

    /**
     * Returning the view of the app with the required data.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index($id = null)
    {
        $pageTitle = "Messages";
        $messenger_color = Auth::user()->messenger_color;
        return view('Chatify::pages.app', [
            'id' => $id ?? 0,
            'messengerColor' => $messenger_color ? $messenger_color : Chatify::getFallbackColor(),
            'dark_mode' => Auth::user()->dark_mode < 1 ? 'light' : 'dark',
            'pageTitle' =>  $pageTitle
        ]);
    }
    public function messsagesIndex($encJobId = null)
    {
        $pageTitle = "Messages";
        $jobId = decrypt($encJobId);
        $messenger_color = Auth::user()->messenger_color;
        $booking = Booking::where(function ($query) use ($jobId) {
            $query->where('job_id', $jobId)
                ->orWhere('service_id', $jobId);
        })->first();

        $jobTitle = $booking->job->title ?? 'Unknown Job';
        $jobDescription = $booking->job->description ?? 'No Description';
        $job_type = $booking->booking_type ?? 'Unknown';

        $seller = $booking->seller ?? null;
        $buyer = $booking->buyer ?? null;
        $isCompleted = $booking->job->show_completed ?? false;

        $buyerId = $buyer->id ?? null;
        $sellerId = $seller->id ?? null;
        $encUserBidingId = encrypt($booking->job->buyerCounterOffer->bid_id) ?? null;

        if (request()->ajax()) {
            return view('Chatify::layouts.customMiddlePart', [
                'id' => $buyerId ?? 0,
                'job_id'=>$jobId ?? NULL,
                'booking_id' => $booking->id ?? null,
                'messengerColor' => $messenger_color ? $messenger_color : Chatify::getFallbackColor(),
                'dark_mode' => Auth::user()->dark_mode < 1 ? 'light' : 'dark',
                'pageTitle' =>  $pageTitle,

            ]);
        }

        // Start

        // End
        return view('Chatify::pages.seller-full-custom-app', [
            'id' => $buyerId ?? 0,
            'job_id'=>$jobId ?? NULL,
            'booking_id' => $booking->id ?? null,
            'messengerColor' => $messenger_color ? $messenger_color : Chatify::getFallbackColor(),
            'dark_mode' => Auth::user()->dark_mode < 1 ? 'light' : 'dark',
            'pageTitle' =>  $pageTitle,
            'jobTitle' => $jobTitle,
            'jobDescription' => $jobDescription,
            'seller' => $seller,
            'isCompleted' => $isCompleted,
            'buyerId' => $buyerId,
            'sellerId' => $sellerId,
            'encJobId' => $encJobId,
            'encUserBidingId' => $encUserBidingId,
            'job_type' => $job_type,
            'buyer'=>$buyer
        ]);
    }

    public function messsagesBuyerIndex(Request $request, $encJobId = null)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $pageTitle = "Messages";
        $jobId = decrypt($encJobId);
        $messenger_color = Auth::user()->messenger_color;
        $booking = Booking::where('job_id', $jobId)->first();
        $jobTitle = $booking->job->title ?? 'Unknown Job';
        $jobDescription = $booking->job->description ?? 'No Description';
        $job_type = $booking->booking_type ?? 'Unknown';

        $seller = $booking->seller ?? null;
        $buyer = $booking->buyer ?? null;
        $isCompleted = $booking->job->show_completed ?? false;

        $buyerId = $buyer->id ?? null;
        $sellerId = $seller->id ?? null;
        $encUserBidingId = encrypt($booking->job->buyerCounterOffer->bid_id) ?? null;

        if (request()->ajax()) {
            return view('Chatify::layouts.customMiddlePart', [
                'id' => $sellerId ?? 0,
                'job_id'=>$jobId ?? null,
                'booking_id' => $booking->id ?? null,
                'messengerColor' => $messenger_color ? $messenger_color : Chatify::getFallbackColor(),
                'dark_mode' => Auth::user()->dark_mode < 1 ? 'light' : 'dark',
                'pageTitle' =>  $pageTitle,

            ]);
        }


        return view('Chatify::pages.full-custom-app', [
            'id' => $sellerId ?? 0,
            'job_id'=>$jobId ?? null,
            'booking_id' => $booking->id ?? null,
            'messengerColor' => $messenger_color ? $messenger_color : Chatify::getFallbackColor(),
            'dark_mode' => Auth::user()->dark_mode < 1 ? 'light' : 'dark',
            'pageTitle' =>  $pageTitle,
            'jobTitle' => $jobTitle,
            'jobDescription' => $jobDescription,
            'seller' => $seller,
            'isCompleted' => $isCompleted,
            'buyerId' => $buyerId,
            'sellerId' => $sellerId,
            'encJobId' => $encJobId,
            'encUserBidingId' => $encUserBidingId,
            'job_type' => $job_type,
        ]);
    }

    /**
     * Fetch data (user, favorite.. etc).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function idFetchData(Request $request)
    {
        $favorite = Chatify::inFavorite($request['id']);
        $fetch = User::where('id', $request['id'])->first();

        if ($fetch) {
            $userAvatar = Chatify::getUserWithAvatar($fetch)->avatar;

            // Dynamic data
            if ($fetch->is_freelancer == 1) {
                $completedJobs = Booking::where('status', 3)->where('working_status', 1)
                    ->where('seller_id', $fetch->id)
                    ->count();

                $jobsInProgress = Booking::where('status', 1)->where('working_status', 4)
                    ->where('seller_id', $fetch->id)
                    ->count();
            } else if ($fetch->is_employer == 1) {
                $completedJobs = Booking::where('status', 3)->where('working_status', 1)
                    ->where('buyer_id', $fetch->id)
                    ->count();

                $jobsInProgress = Booking::where('status', 1)->where('working_status', 4)
                    ->where('buyer_id', $fetch->id)
                    ->count();
            }

            $servicesInProgress = Service::where('user_id', $request['id'])
                ->whereHas('category', function ($q) {
                    $q->where('status', 1);
                })->count();

            $completedServices = Service::where('user_id', $request['id'])
                ->whereHas('category', function ($q) {
                    $q->where('status', 1);
                })->count();
        }

        return Response::json([
            'favorite' => $favorite,
            'fetch' => $fetch ?? null,
            'user_avatar' => $userAvatar ?? null,
            'counts' => [
                'jobs_in_progress' => $jobsInProgress ?? 0,
                'completed_jobs' => $completedJobs ?? 0,
                'services_in_progress' => $servicesInProgress ?? 0,
                'completed_services' => $completedServices ?? 0,
            ],
        ]);
    }

    /**
     * This method to make a links for the attachments
     * to be downloadable.
     *
     * @param string $fileName
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|void
     */
    public function download($fileName)
    {
        $filePath = config('chatify.attachments.folder') . '/' . $fileName;
        if (Chatify::storage()->exists($filePath)) {
            return Chatify::storage()->download($filePath);
        }
        return abort(404, "Sorry, File does not exist in our server or may have been deleted!");
    }

    /**
     * Send a message to database
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function send(Request $request)
    {
        // default variables
        $message = $request['message'];
        $sanitize_message = $this->sanitizeMessage($message);
        if ($sanitize_message != null && $sanitize_message != "blocked" && $sanitize_message == $message) {
            $error = (object)[
                'status' => 0,
                'message' => null
            ];
            $attachment = null;
            $attachment_title = null;

            // if there is attachment [file]
            if ($request->hasFile('file')) {
                // allowed extensions
                $allowed_images = Chatify::getAllowedImages();
                $allowed_files  = Chatify::getAllowedFiles();
                $allowed        = array_merge($allowed_images, $allowed_files);

                $file = $request->file('file');
                // check file size
                if ($file->getSize() < Chatify::getMaxUploadSize()) {
                    if (in_array(strtolower($file->extension()), $allowed)) {
                        // get attachment name
                        $attachment_title = $file->getClientOriginalName();
                        // upload attachment and store the new name
                        $attachment = Str::uuid() . "." . $file->extension();
                        $file->storeAs(config('chatify.attachments.folder'), $attachment, config('chatify.storage_disk_name'));
                    } else {
                        $error->status = 1;
                        $error->message = "File extension not allowed!";
                    }
                } else {
                    $error->status = 1;
                    $error->message = "File size you are trying to upload is too large!";
                }
            }

            if (!$error->status) {
                $message = Chatify::newMessage([
                    'from_id' => Auth::user()->id,
                    'to_id' => $request['id'],
                    'job_id' => $request['job_id'] ?? NULL,
                    'body' => htmlentities(trim($sanitize_message), ENT_QUOTES, 'UTF-8'),
                    'attachment' => ($attachment) ? json_encode((object)[
                        'new_name' => $attachment,
                        'old_name' => htmlentities(trim($attachment_title), ENT_QUOTES, 'UTF-8'),
                    ]) : null,
                ]);
                $messageData = Chatify::parseMessage($message);
                if (Auth::user()->id != $request['id']) {
                    Chatify::push("private-chatify." . $request['id'], 'messaging', [
                        'from_id' => Auth::user()->id,
                        'to_id' => $request['id'],
                        'job_id' => $request['job_id'] ?? NULL,
                        'message' => Chatify::messageCard($messageData, true),
                        'count'=>0
                    ]);
                }
            }

            // send the response
            return Response::json([
                'status' => '200',
                'error' => $error,
                'message' => Chatify::messageCard(@$messageData),
                'tempID' => $request['temporaryMsgId'],
                'count'=>0,
            ]);
        }else {

            $error = (object)[
                'status' => 0,
                'message' => null
            ];

            $attachment = null;
            $attachment_title = null;

            if ($request->hasFile('file')) {
                // allowed extensions
                $allowed_images = Chatify::getAllowedImages();
                $allowed_files  = Chatify::getAllowedFiles();
                $allowed        = array_merge($allowed_images, $allowed_files);

                $file = $request->file('file');
                // check file size
                if ($file->getSize() < Chatify::getMaxUploadSize()) {
                    if (in_array(strtolower($file->extension()), $allowed)) {
                        // get attachment name
                        $attachment_title = $file->getClientOriginalName();
                        // upload attachment and store the new name
                        $attachment = Str::uuid() . "." . $file->extension();
                        $file->storeAs(config('chatify.attachments.folder'), $attachment, config('chatify.storage_disk_name'));
                    } else {
                        $error->status = 1;
                        $error->message = "File extension not allowed!";
                    }
                } else {
                    $error->status = 1;
                    $error->message = "File size you are trying to upload is too large!";
                }
            }

            $hasEmail = Str::contains($message, '@');

            $hasPhone = preg_match('/\d{10,}/', $message);

            $message = Chatify::newMessage([
                'from_id' => Auth::user()->id,
                'to_id' => $request['id'],
                'job_id' => $request['job_id'] ?? NULL,
                'body' => !is_null($sanitize_message) ? htmlentities(trim($sanitize_message), ENT_QUOTES, 'UTF-8') : $attachment,
                'attachment' => ($attachment) ? json_encode((object)[
                    'new_name' => $attachment,
                    'old_name' => htmlentities(trim($attachment_title), ENT_QUOTES, 'UTF-8'),
                ]) : null,
            ]);
            Log::info($message);
            if ($hasEmail) {
                $message->increment('email_included');
            }

            if ($hasPhone) {
                $message->increment('phone_included');
            }

            $user = Auth::user();
            $blocked_count = DB::table('ch_messages')->where('from_id', $user->id)
            ->where(function ($q) {
                $q->where('email_included', '>',0)
                ->orWhere('phone_included', '>',0);
            })
            ->count();
            if($blocked_count >= 2 && $user->status != 0) {
                $user->status = 0;
                $user->save();
            }

            if($message && !is_null($message->attachment)) {
                $messageData = Chatify::parseMessage($message);

                if (Auth::user()->id != $request['id']) {
                    Chatify::push("private-chatify." . $request['id'], 'messaging', [
                        'from_id' => Auth::user()->id,
                        'to_id' => $request['id'],
                        'job_id' => $request['job_id'] ?? NULL,
                        'message' => Chatify::messageCard($messageData, true),
                        'count' => 0
                    ]);
                }

                return Response::json([
                    'status' => '200',
                    'error' => $error,
                    'message' => Chatify::messageCard($messageData),
                    'tempID' => $request['temporaryMsgId'],
                    'count'=>0,
                ]);
            }
 
            return Response::json([
                'status' => '400',
                'error' => "blocked",
                'message' => "Contact info detected. Sharing email or phone numbers off-platform may lead to a TOS warning",
                'tempID' => $request['temporaryMsgId'],
                'count'=>$blocked_count
            ]);
        }
    }

    /**
     * fetch [user/group] messages from database
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetch(Request $request)
    {
        $query = Chatify::fetchMessagesQuery($request['id'])->latest();
        $messages = $query->paginate($request->per_page ?? $this->perPage);
        $totalMessages = $messages->total();
        $lastPage = $messages->lastPage();
        $response = [
            'total' => $totalMessages,
            'last_page' => $lastPage,
            'last_message_id' => collect($messages->items())->last()->id ?? null,
            'messages' => '',
        ];

        // if there is no messages yet.
        if ($totalMessages < 1) {
            $response['messages'] = '<p class="message-hint center-el"><span>Say \'hi\' and start messaging</span></p>';
            return Response::json($response);
        }
        if (count($messages->items()) < 1) {
            $response['messages'] = '';
            return Response::json($response);
        }
        $allMessages = null;
        foreach ($messages->reverse() as $message) {
            $allMessages .= Chatify::messageCard(
                Chatify::parseMessage($message)
            );
        }

        $response['messages'] = $allMessages;
        return Response::json($response);
    }

    /**
     * Make messages as seen
     *
     * @param Request $request
     * @return JsonResponse|void
     */
    public function seen(Request $request)
    {
        // make as seen
        $seen = Chatify::makeSeen($request['id']);
        // send the response
        return Response::json([
            'status' => $seen,
        ], 200);
    }

    /**
     * Get contacts list
     *
     * @param Request $request
     * @return JsonResponse
     */
    // public function getContacts(Request $request)
    // {
    //     // get all users that received/sent message from/to [Auth user]
    //     $users = Message::join('users',  function ($join) {
    //         $join->on('ch_messages.from_id', '=', 'users.id')
    //             ->orOn('ch_messages.to_id', '=', 'users.id');
    //     })
    //         ->where(function ($q) {
    //             $q->where('ch_messages.from_id', Auth::user()->id)
    //                 ->orWhere('ch_messages.to_id', Auth::user()->id);
    //         })
    //         ->where('users.id', '!=', Auth::user()->id)
    //         ->select('users.*', DB::raw('MAX(ch_messages.created_at) max_created_at'))
    //         ->orderBy('max_created_at', 'desc')
    //         ->groupBy('users.id')
    //         ->paginate($request->per_page ?? $this->perPage);

    //     $usersList = $users->items();
    //     if (count($usersList) > 0) {
    //         $contacts = '';
    //         foreach ($usersList as $index => $user) {
    //             $user->is_first = ($index === 0);
    //             $contacts .= Chatify::getContactItem($user);
    //         }
    //     } else {
    //         $contacts = '<p class="message-hint center-el"><span>Your contact list is empty</span></p>';
    //     }

    //     return Response::json([
    //         'contacts' => $contacts,
    //         'total' => $users->total() ?? 0,
    //         'last_page' => $users->lastPage() ?? 1,
    //     ], 200);
    // }

    public function getContacts(Request $request)
    {
        $search = trim($request->input('search', ''));

        $users = Message::join('users', function ($join) {
            $join->on('ch_messages.from_id', '=', 'users.id')
                ->orOn('ch_messages.to_id', '=', 'users.id');
        })
            ->where(function ($q) {
                $q->where('ch_messages.from_id', Auth::user()->id)
                    ->orWhere('ch_messages.to_id', Auth::user()->id);
            })
            ->where('users.id', '!=', Auth::user()->id)
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('users.firstname', 'LIKE', "%{$search}%")
                        ->orWhere('users.lastname', 'LIKE', "%{$search}%")
                        ->orWhere('users.username', 'LIKE', "%{$search}%")
                        ->orWhere('users.email', 'LIKE', "%{$search}%");
                });
            })
            ->select('users.*', DB::raw('MAX(ch_messages.created_at) max_created_at'))
            ->orderBy('max_created_at', 'desc')
            ->groupBy('users.id')
            ->paginate($request->per_page ?? $this->perPage);

        $usersList = $users->items();
        if (count($usersList) > 0) {
            $contacts = '';
            foreach ($usersList as $index => $user) {
                $user->is_first = ($index === 0);
                $contacts .= Chatify::getContactItem($user);
            }
        } else {
            $contacts = $search !== ''
                ? '<p class="message-hint center-el"><span>No contacts match "' . e($search) . '"</span></p>'
                : '<p class="message-hint center-el"><span>Your contact list is empty</span></p>';
        }

        return Response::json([
            'contacts' => $contacts,
            'total' => $users->total() ?? 0,
            'last_page' => $users->lastPage() ?? 1,
        ], 200);
    }

    /**
     * Update user's list item data
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateContactItem(Request $request)
    {
        // Get user data
        $user = User::where('id', $request['user_id'])->first();
        if (!$user) {
            return Response::json([
                'message' => 'User not found!',
            ], 401);
        }
        $contactItem = Chatify::getContactItem($user);

        // send the response
        return Response::json([
            'contactItem' => $contactItem,
        ], 200);
    }

    /**
     * Put a user in the favorites list
     *
     * @param Request $request
     * @return JsonResponse|void
     */
    public function favorite(Request $request)
    {
        $userId = $request['user_id'];
        // check action [star/unstar]
        $favoriteStatus = Chatify::inFavorite($userId) ? 0 : 1;
        Chatify::makeInFavorite($userId, $favoriteStatus);

        // send the response
        return Response::json([
            'status' => @$favoriteStatus,
        ], 200);
    }

    /**
     * Get favorites list
     *
     * @param Request $request
     * @return JsonResponse|void
     */
    public function getFavorites(Request $request)
    {
        $search = trim($request->input('search', ''));

        $favoriteIds = Favorite::where('user_id', Auth::user()->id)
            ->pluck('favorite_id')
            ->toArray();

        $favorites = User::whereIn('id', $favoriteIds)
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('users.firstname', 'LIKE', "%{$search}%")
                        ->orWhere('users.lastname', 'LIKE', "%{$search}%")
                        ->orWhere('users.username', 'LIKE', "%{$search}%")
                        ->orWhere('users.email', 'LIKE', "%{$search}%");
                });
            })
            ->get();

        $favoritesList = null;
        foreach ($favorites as $favorite) {
            $lastMessage = Chatify::getLastMessageQuery($favorite->id);
            if (!$lastMessage) {
                continue;
            }
            $favoritesList .= Chatify::getContactItem($favorite);
        }

        return Response::json([
            'count' => count($favorites),
            'favorites' => $favoritesList ?: 0,
        ], 200);
    }

    /**
     * Search in messenger
     *
     * @param Request $request
     * @return JsonResponse|void
     */
    public function search(Request $request)
    {
        $getRecords = null;
        $input = trim(filter_var($request['input']));
        $records = User::where('id', '!=', Auth::user()->id)
            ->where('name', 'LIKE', "%{$input}%")
            ->paginate($request->per_page ?? $this->perPage);
        foreach ($records->items() as $record) {
            $getRecords .= view('Chatify::layouts.listItem', [
                'get' => 'search_item',
                'user' => Chatify::getUserWithAvatar($record),
            ])->render();
        }
        if ($records->total() < 1) {
            $getRecords = '<p class="message-hint center-el"><span>Nothing to show.</span></p>';
        }
        // send the response
        return Response::json([
            'records' => $getRecords,
            'total' => $records->total(),
            'last_page' => $records->lastPage()
        ], 200);
    }

    /**
     * Get shared photos
     *
     * @param Request $request
     * @return JsonResponse|void
     */
    public function sharedPhotos(Request $request)
    {
        $shared = Chatify::getSharedPhotos($request['user_id']);
        $sharedPhotos = null;

        // shared with its template
        for ($i = 0; $i < count($shared); $i++) {
            $sharedPhotos .= view('Chatify::layouts.listItem', [
                'get' => 'sharedPhoto',
                'image' => Chatify::getAttachmentUrl($shared[$i]),
            ])->render();
        }
        // send the response
        return Response::json([
            'shared' => count($shared) > 0 ? $sharedPhotos : '<p class="message-hint"><span>Nothing shared yet</span></p>',
        ], 200);
    }

    /**
     * Delete conversation
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteConversation(Request $request)
    {
        // delete
        $delete = Chatify::deleteConversation($request['id']);

        // send the response
        return Response::json([
            'deleted' => $delete ? 1 : 0,
        ], 200);
    }

    /**
     * Delete message
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteMessage(Request $request)
    {
        // delete
        $delete = Chatify::deleteMessage($request['id']);

        // send the response
        return Response::json([
            'deleted' => $delete ? 1 : 0,
        ], 200);
    }

    public function updateSettings(Request $request)
    {
        $msg = null;
        $error = $success = 0;

        // dark mode
        if ($request['dark_mode']) {
            $request['dark_mode'] == "dark"
                ? User::where('id', Auth::user()->id)->update(['dark_mode' => 1])  // Make Dark
                : User::where('id', Auth::user()->id)->update(['dark_mode' => 0]); // Make Light
        }

        // If messenger color selected
        if ($request['messengerColor']) {
            $messenger_color = trim(filter_var($request['messengerColor']));
            User::where('id', Auth::user()->id)
                ->update(['messenger_color' => $messenger_color]);
        }
        // if there is a [file]
        if ($request->hasFile('avatar')) {
            // allowed extensions
            $allowed_images = Chatify::getAllowedImages();

            $file = $request->file('avatar');
            // check file size
            if ($file->getSize() < Chatify::getMaxUploadSize()) {
                if (in_array(strtolower($file->extension()), $allowed_images)) {
                    // delete the older one
                    if (Auth::user()->avatar != config('chatify.user_avatar.default')) {
                        $avatar = Auth::user()->avatar;
                        if (Chatify::storage()->exists($avatar)) {
                            Chatify::storage()->delete($avatar);
                        }
                    }
                    // upload
                    $avatar = Str::uuid() . "." . $file->extension();
                    $update = User::where('id', Auth::user()->id)->update(['avatar' => $avatar]);
                    $file->storeAs(config('chatify.user_avatar.folder'), $avatar, config('chatify.storage_disk_name'));
                    $success = $update ? 1 : 0;
                } else {
                    $msg = "File extension not allowed!";
                    $error = 1;
                }
            } else {
                $msg = "File size you are trying to upload is too large!";
                $error = 1;
            }
        }

        // send the response
        return Response::json([
            'status' => $success ? 1 : 0,
            'error' => $error ? 1 : 0,
            'message' => $error ? $msg : 0,
        ], 200);
    }

    /**
     * Set user's active status
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setActiveStatus(Request $request)
    {
        $activeStatus = $request['status'] > 0 ? 1 : 0;
        $status = User::where('id', Auth::user()->id)->update(['active_status' => $activeStatus]);
        return Response::json([
            'status' => $status,
        ], 200);
    }

    protected function sanitizeMessage($message)
    {
        if (preg_match('/[a-z0-9_\.\-]+@[a-z0-9\-]+\.[a-z]{2,6}/i', $message)) {
            return "blocked";
        }

        if (preg_match('/\+?\d[\d\s\-]{8,}/', $message)) {
            return "blocked";
        }

        $numberWords = [
            'zero',
            'one',
            'two',
            'three',
            'four',
            'five',
            'six',
            'seven',
            'eight',
            'nine',
            'ten',
            'eleven',
            'twelve',
            'thirteen',
            'fourteen',
            'fifteen',
            'sixteen',
            'seventeen',
            'eighteen',
            'nineteen',
            'twenty',
            'thirty',
            'forty',
            'fifty',
            'sixty',
            'seventy',
            'eighty',
            'ninety',
            'hundred',
            'thousand'
        ];

        foreach ($numberWords as $word) {
            if (Str::contains(Str::lower($message), $word)) {
                return "blocked";
            }
        }
        return $message;
    }
}