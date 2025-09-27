<?php

namespace Jakubcesar\EmailNotifier\DI;

use Jakubcesar\EmailNotifier\EmailNotificationService;
use Nette\DI\CompilerExtension;

class EmailNotifierExtension extends CompilerExtension
{
    public function loadConfiguration()
    {
        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix('service'))
            ->setFactory(EmailNotificationService::class, [
                $builder->getDefinition('nette.mailer') ?? null
            ]);
    }
}