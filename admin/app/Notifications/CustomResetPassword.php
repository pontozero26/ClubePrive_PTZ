<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\HtmlString;

class CustomResetPassword extends ResetPassword
{
    public function toMail($notifiable)
    {
        // Obtém o nome do usuário 
        $userName = $notifiable->name ?? 'Usuário';
        
        // Data e hora atual formatada
        $dateTime = Carbon::now()->format('d/m/Y \à\s H:i:s');
        
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
        
        $expireMinutes = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');
        
        // Usar o template personalizado
        return (new MailMessage)
            ->subject('Clube Privê | Redefinição de senha')
            ->view('emails.reset-password', [
                'userName' => $userName,
                'dateTime' => $dateTime,
                'resetUrl' => $resetUrl,
                'expireMinutes' => $expireMinutes
            ]);
    }
}