<?php

namespace App\Observers;

use App\Models\Banner;

class BannerObserver
{
    public function created(Banner $banner): void
    {
        logActivity('Uploaded', 'Banner', "Banner '{$banner->title}' was uploaded.");
    }

    public function updated(Banner $banner): void
    {
        $changed = $banner->getChanges();
        unset($changed['updated_at']);
        if (empty($changed)) return;

        $ns = app(\App\Services\NotificationService::class);

        if (isset($changed['image'])) {
            logActivity('Replaced', 'Banner', "Banner '{$banner->title}' image was replaced.");
            $ns->create('banner_updated', 'Banner', "Banner Updated: {$banner->title}", "Banner '{$banner->title}' image was replaced.");
        } else {
            $fields = array_keys($changed);
            logActivity('Updated', 'Banner', "Banner '{$banner->title}' was updated. (" . implode(', ', $fields) . ')');
            $ns->create('banner_updated', 'Banner', "Banner Updated: {$banner->title}", "Banner '{$banner->title}' was updated.");
        }
    }

    public function deleted(Banner $banner): void
    {
        logActivity('Deleted', 'Banner', "Banner '{$banner->title}' was deleted.");
    }
}
