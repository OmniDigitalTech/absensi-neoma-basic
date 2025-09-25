<?php

namespace App\Services;

use App\Events\NotifApproval;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class NotifyService
{
    public function sendNotifies($schedule)
    {
        try {
            // Check if users collection is empty
            if (!$schedule) {
                Log::info("No users to notify");
            }

            foreach ($schedule as $role => $details) {
                $type = $details['type'];
                $sender = $details['sender'];
                $messageTemplate = $details['messageTemplate'];
                $url = $details['url'];

                // Ensure receivers is an array of objects
                if ($details['dataType'] !== 'arrays of object') {
                    $details['receivers'] = [$details['receivers']];
                }

                foreach ($details['receivers'] as $user) {
                    // Validate that the user exists
                    if ($user && isset($user->id)) {
                        try {
                            // Prepare notification message
                            $notif = str_replace('{name}', $user->name, $messageTemplate);

                            // Set additional data for the notification
                            $user->messages = [
                                'user_id' => $sender->id,
                                'from'    => $sender->name,
                                'message' => $notif,
                                'action'  => $url
                            ];

                            // Send notification using the provided callback
                            $user->notify(new \App\Notifications\UserNotification);

                            // Log notification info
                            Log::info("Notification sent to user ID: {$user->id}, type: {$type}");

                            // Dispatch event
                            NotifApproval::dispatch($type, $user->id, $notif, $url);

                        } catch (\Exception $e) {
                            // Handle notification-specific errors (e.g., notify, dispatch issues)
                            Log::error("Error sending notification to user ID {$user->id}: " . $e->getMessage());
                        }
                    } else {
                        // Log when user is not found for a notification
                        Log::warning("User not found for Data ID {$user->id}");
                    }
                }
            }

        } catch (\Exception $e) {
            // Handle any unexpected error in the main notifyUsers loop
            Log::error("Error in notifyUsers: " . $e->getMessage());
        }
    }
}
