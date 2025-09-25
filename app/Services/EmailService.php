<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class EmailService
{
    public function sendEmails($schedule, callable $emailCallback)
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
                $executionTime = $details['executionTime'];

                // Ensure receivers is an array of objects
                if ($details['dataType'] !== 'arrays of object') {
                    $details['receivers'] = [$details['receivers']];
                }

                // Determine greeting based on executionTime
                $hour = Carbon::parse($executionTime)->hour;

                if ($hour >= 5 && $hour < 11) {
                    $greeting = 'Selamat Pagi!';
                } elseif ($hour >= 11 && $hour < 15) {
                    $greeting = 'Selamat Siang!';
                } elseif ($hour >= 15 && $hour < 18) {
                    $greeting = 'Selamat Sore!';
                } else {
                    $greeting = 'Selamat Malam!';
                }

                foreach ($details['receivers'] as $user) {
                    Log::info("Sending email to user ID: {$user->name}");
                    // Validate that the user exists
                    if ($user && isset($user->id)) {
                        try {
                            // Prepare email content
                            $notif = str_replace('{name}', $user->name, $messageTemplate);

                            // Send email using the provided callback
                            $emailCallback($greeting, $user, $notif, $url);

                            // Log email info
                            Log::info("Email sent to user ID: {$user->id}, email: {$user->email}");

                        } catch (\Exception $e) {
                            Log::error("Error sending email to user ID {$user->id}: " . $e->getMessage());
                        }
                    } else {
                        Log::warning("User not found for Data ID {$user->id}");
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Error in sendEmails: " . $e->getMessage());
        }
    }
}
