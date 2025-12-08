<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\FortifyServiceProvider::class,
    App\Modules\Notification\Infrastructure\Providers\NotificationServiceProvider::class,
    App\Modules\UserManagement\Infrastructure\Providers\UserManagementServiceProvider::class,
    App\Modules\Dashboard\Infrastructure\Providers\DashboardServiceProvider::class,
];