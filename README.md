# EmailNotifier
Jednoduchá knihovna pro odesílání emailových notifikací v Nette Frameworku.  
Podporuje **dev/prod režim**:
- V **developmentu** se emaily ukládají do `Nextras\MailPanel` (Tracy panel).
- V **produkcí** se odesílají skutečné emaily přes `SendmailMailer`.

---

## Instalace
```bash
composer require jakubcesar/email-notifier
composer require --dev nextras/mail-panel nette/tester
```

## Konfigurace (NEON)
Doporučený způsob je rozdělit konfiguraci podle prostředí:
```
app/config/
├── common.neon       # společné služby pro dev i prod
├── local.neon        # development (dev)
└── production.neon   # produkce (prod)
```
### common.neon
```neon
extensions:
    emailNotifier: Jakubcesar\EmailNotifier\DI\EmailNotifierExtension

services:
    emailNotifier:
        factory: Jakubcesar\EmailNotifier\EmailNotificationService
        arguments:
            - @nette.mailer
```

### local.neon (development)
```neon
services:
    # V dev se emaily ukládají do Tracy Mail Panelu
    nette.mailer:
        factory: Nextras\MailPanel\FileMailer(%tempDir%/mail-panel-mails)
```

### production.neon (produkce)
```neon
services:
    # V produkci se emaily posílají skutečně uživateli
    nette.mailer:
        factory: Nette\Mail\SendmailMailer
```

### Bootstrap.php
```php
use Tracy\Debugger;

$configDir = $this->rootDir . '/config';
// Načtení podle prostředí
if (Debugger::$productionMode) {
    $configurator->addConfig($configDir . '/production.neon');
} else {
    $configurator->addConfig($configDir . '/local.neon');
}
```

## Použití v Presenteru
```php
<?php

namespace App\Presenters;

use Nette;
use Jakubcesar\EmailNotifier\EmailNotificationService;

final class HomepagePresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private EmailNotificationService $notifier
    ) {}

    public function actionDefault(): void
    {
        $this->notifier->send(
            'no-reply@example.com',
            'user@example.com',
            'Welcome!',
            'Hello, this is a test email from EmailNotifier.'
        );
    }
}
```
- V dev → emaily skončí v Tracy Mail Panelu
- V prod → emaily se odešlou skutečně uživateli