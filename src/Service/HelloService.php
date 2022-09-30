<?php

namespace App\Service;

class HelloService
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function hello($name)
    {
        $message = (new \Swift_Message('Hello Service'))
            ->setTo('me@example.com')
            ->setBody($name.' says hi!');

        $this->mailer->send($message);

        return 'Hello, '.$name;
    }
}

?>