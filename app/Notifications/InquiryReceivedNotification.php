<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\CustomerInquiry;

class InquiryReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $inquiry;

    /**
     * Create a new notification instance.
     */
    public function __construct(CustomerInquiry $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Product Inquiry - ' . $this->inquiry->product_name)
            ->greeting('Hello ' . $notifiable->company_name . '!')
            ->line('You have received a new product inquiry from a customer.')
            ->line('')
            ->line('**PRODUCT DETAILS:**')
            ->line('Product Name: ' . $this->inquiry->product_name)
            ->line('Product ID: #' . $this->inquiry->product_id)
            ->line('')
            ->line('**CUSTOMER INFORMATION:**')
            ->line('Name: ' . $this->inquiry->customer->name)
            ->line('Email: ' . $this->inquiry->customer->email)
            ->line('Phone: ' . ($this->inquiry->customer->phone ?? 'Not provided'))
            ->line('')
            ->line('**INQUIRY MESSAGE:**')
            ->line($this->inquiry->message)
            ->line('')
            ->line('**INQUIRY DETAILS:**')
            ->line('Inquiry ID: #' . $this->inquiry->id)
            ->line('Date: ' . $this->inquiry->created_at->format('F j, Y \a\t g:i A'))
            ->action('View Inquiry Details', route('supplier.inquiries'))
            ->line('')
            ->line('Please respond to the customer as soon as possible.')
            ->line('Thank you for using GNRAW platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'inquiry_id' => $this->inquiry->id,
            'product_name' => $this->inquiry->product_name,
            'customer_name' => $this->inquiry->customer->name,
            'customer_email' => $this->inquiry->customer->email,
            'message' => $this->inquiry->message,
            'created_at' => $this->inquiry->created_at
        ];
    }
}
