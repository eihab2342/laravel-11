<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    // تعريف متغير لحفظ الـ OTP
    // وهذا المتغير يتم تمريره إلى الـ view
    // عشان يتم عرضه في البريد الإلكتروني
    public $otp;  

    /**
     * Create a new message instance.
     *
     * @param string $otp
     */
    // هنا يتم تمرير الـ OTP إلى الـ constructor
    // اهميه الكونستراكتور هو تمرير البيانات الى الكلاس الذي يرث منه الكلاس الحالي
    // ودا يتم عن طريق الكونستراكتور
    // وهنا يتم تمرير الـ OTP إلى الـ constructor
    // عشان يتم حفظه في متغير الكلاس
    // وبعدين يتم استخدامه في الـ view
    public function __construct($otp)
    {
        $this->otp = $otp;  // حفظ الـ OTP في المتغير
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'رمز التحقق OTP',  // تعديل العنوان ليكون مناسبًا
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',  // استخدم الـ view لعرض محتوى البريد
            with: [
                'otp' => $this->otp,  // تمرير الـ OTP إلى الـ view
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
