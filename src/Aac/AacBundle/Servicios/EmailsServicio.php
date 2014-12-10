<?php

/**
 * Description of Hash
 * @author ©-2014 Antonio Lorenzo Esparza 25-jul-2014
 */
namespace Aac\AacBundle\Servicios;

use Symfony\Component\DependencyInjection\ContainerInterface;

class EmailsServicio
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function envioEmails($data, $user, $envio)
    {
        $transport = \Swift_SmtpTransport::newInstance($this->container->getParameter('mailer_host'))
            ->setUsername($this->container->getParameter('mailer_user'))
            ->setPassword($this->container->getParameter('mailer_password'))
            ;
        
        $mailer = \Swift_Mailer::newInstance($transport);
        
        foreach ($envio as $key => $value) {
            $message = \Swift_Message::newInstance();
            $message->setSubject($data->getAsunto());
            $message->setFrom($user);

            $message->setTo(array($key => $value));

            $message->setBody(
                '<html>' .
                ' <head></head>' .
                ' <body>' .
                '<p><strong>' . $data->getCabecera() . '</strong></p><br>' .
                '<p>' . $data->getTexto() . '</p><br>' .
                ' </body>' .
                '</html>',
                  'text/html' 
                );

            $result = $mailer->send($message);    
        }
        return $result;
    }

}
