<?php

namespace App\Infrastructure\Mailer;

use App\Domain\User\User;
use Exception;
use Mailgun\Mailgun;
use Psr\Http\Client\ClientExceptionInterface;

class Mailer
{
    private Mailgun $mailer;
    private string $domain;
    private string $from;

    public function __construct($mailer, string $domain, string $from)
    {
        $this->mailer = $mailer;
        $this->domain = $domain;
        $this->from = $from;
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function sendActivationEmail(User $user, string $activationUrl)
    {
        $this->mailer->messages()->send($this->domain, [
            'from' => $this->from,
            'to' => $user->getFirstName() . ' ' . $user->getLastName() . "<" . $user->getEmail() . ">",
            'subject' => 'Activate Your Account',
            'text' => "Dear " . $user->getFirstName() . ",\n\n" .
                "Thank you for signing up for our service. To activate your account, please click on the following link: " . $activationUrl . "\n\n" .
                "If you did not sign up for an account, please ignore this message.\n\n" .
                "Best regards,\n" .
                "The Example App Team"
        ]);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function sendForgetPasswordEmail(User $user, string $forgetPasswordUrl)
    {
        $this->mailer->messages()->send($this->domain, [
            'from' => $this->from,
            'to' => $user->getFirstName() . ' ' . $user->getLastName() . "<" . $user->getEmail() . ">",
            'subject' => 'Reset Your Password',
            'text' => "Dear " . $user->getFirstName() . ",\n\n" .
                "We have received a request to reset your password. Please click on the following link to reset your password: " . $forgetPasswordUrl . "\n\n" .
                "If you did not request a password reset, please ignore this message and your password will remain unchanged.\n\n" .
                "Thank you for using our service.\n\n" .
                "Best regards,\n" .
                "The Example App Team"
        ]);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function sendNewPasswordCreatedEmail(User $user, string $string)
    {
        $this->mailer->messages()->send($this->domain, [
            'from' => $this->from,
            'to' => $user->getFirstName() . ' ' . $user->getLastName() . "<" . $user->getEmail() . ">",
            'subject' => 'Your New Password Has Been Created',
            'text' => "Dear " . $user->getFirstName() . ",\n\n" .
                "Your password has been successfully reset. \n\n" .
                "Thank you for using our service.\n\n" .
                "Best regards,\n" .
                "The Example App Team"
        ]);
    }
}