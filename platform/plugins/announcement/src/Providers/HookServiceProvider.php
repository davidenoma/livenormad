<?php

namespace ArchiElite\Announcement\Providers;

use ArchiElite\Announcement\AnnouncementHelper;
use Botble\Base\Supports\ServiceProvider;
use Botble\Theme\Facades\Theme;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! defined('THEME_FRONT_BODY')) {
            return;
        }

        Theme::asset()
            ->add('announcement-css', asset('vendor/core/plugins/announcement/css/announcement.css'));
        Theme::asset()
            ->container('footer')
            ->add('announcement-js', asset('vendor/core/plugins/announcement/js/announcement.js'));

        add_filter(THEME_FRONT_BODY, function (string|null $html): string|null {
            $announcements = AnnouncementHelper::getAnnouncements();

            $html .= apply_filters(
                'announcement_display',
                view('plugins/announcement::announcements', compact('announcements')),
                $announcements
            );

            return $html;
        });
    }
}
