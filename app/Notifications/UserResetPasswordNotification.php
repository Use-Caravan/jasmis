<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\User;
use DB;

class UserResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
          $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {        
        $user = User::select([
                DB::raw(" CONCAT(first_name, ' ', last_name) as username")
                ])->where('email',request('email'))->first();
        // return (new MailMessage)
        //             ->line('The introduction to the notification.')
        //             ->action('Notification Action', route('reset-password',['token' => $this->token]))
        //             ->line('Thank you for using our application!');                
        return ( new MailMessage )
                    ->view('email.forget-password',[
                        'url' => route('frontend.reset-password',['token' => $this->token]),
                        'user' => $user->username
                    ])
                    // ->from('info@example.com')
                    ->subject( 'Reset your password');                    
                    // ->line( "Hey, We've successfully changed the text " )
                    // ->action( 'Reset Password')
                    // ->attach('reset.attachment')
                    // ->line( 'Thank you!' );            
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
