<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing admin notifications to have the new data structure
        $adminNotifications = DB::table('admin_notifications')->get();

        foreach ($adminNotifications as $notification) {
            $data = json_decode($notification->data, true) ?? [];

            // Only update if the data doesn't already have the new structure
            if (!isset($data['action'])) {
                $newData = $data;

                // Add action based on type
                switch ($notification->type) {
                    case 'product_added':
                        $newData['action'] = 'added';
                        $newData['action_icon'] = 'fa-plus-circle';
                        $newData['action_color'] = 'success';
                        break;
                    case 'product_updated':
                        $newData['action'] = 'updated';
                        $newData['action_icon'] = 'fa-edit';
                        $newData['action_color'] = 'warning';
                        break;
                    case 'product_deleted':
                        $newData['action'] = 'deleted';
                        $newData['action_icon'] = 'fa-trash';
                        $newData['action_color'] = 'danger';
                        break;
                    default:
                        $newData['action'] = 'unknown';
                        $newData['action_icon'] = 'fa-bell';
                        $newData['action_color'] = 'primary';
                        break;
                }

                // Update the notification
                DB::table('admin_notifications')
                    ->where('id', $notification->id)
                    ->update(['data' => json_encode($newData)]);
            }
        }

        // Update existing supplier notifications to have consistent data structure
        $supplierNotifications = DB::table('notifications')->get();

        foreach ($supplierNotifications as $notification) {
            $data = json_decode($notification->data, true) ?? [];

            // Only update if the data doesn't already have the new structure
            if (!isset($data['action'])) {
                $newData = $data;

                // Add action based on type
                switch ($notification->type) {
                    case 'comment_added':
                        $newData['action'] = 'commented';
                        $newData['action_icon'] = 'fa-comment';
                        $newData['action_color'] = 'info';
                        break;
                    default:
                        $newData['action'] = 'unknown';
                        $newData['action_icon'] = 'fa-bell';
                        $newData['action_color'] = 'primary';
                        break;
                }

                // Update the notification
                DB::table('notifications')
                    ->where('id', $notification->id)
                    ->update(['data' => json_encode($newData)]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration only adds data, so we don't need to reverse it
        // The new data structure is backward compatible
    }
};
