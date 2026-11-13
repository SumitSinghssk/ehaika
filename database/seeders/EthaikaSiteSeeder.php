<?php

namespace Database\Seeders;

use App\Helpers\Settings;
use App\Models\Setting;
use App\Services\Menus\MenuBuilder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class EthaikaSiteSeeder extends Seeder
{
    public function run(MenuBuilder $menus): void
    {
        $setting = Setting::firstOrCreate(['key' => 'basic_settings'], ['value' => []]);

        Storage::disk('local')->put('ethaika-seed-backup-'.now()->format('Ymd-His').'.json', json_encode([
            'basic_settings' => $setting->value,
            'header' => $menus->editorRows($menus->menu('header')),
            'footer' => $menus->editorRows($menus->menu('footer')),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $row = fn (string $label, string $url, int $depth = 0, bool $newTab = false) => [
            'label' => $label, 'url' => $url, 'depth' => $depth, 'style' => 'dropdown',
            'image' => '', 'description' => '', 'new_tab' => $newTab, 'is_active' => true,
        ];

        $this->save($menus, 'header', [
            $row('Home', '/'),
            $row('Platform', '/platform'),
            $row('Blog', '/blog'),
            $row('Features', '/features'),
            $row('About', '/about'),
            $row('Contact Us', '/contact-us'),
        ]);

        $this->save($menus, 'footer', [
            $row('Company', '', 0),
            $row('Home', '/', 1),
            $row('About', '/about', 1),
            $row('Features', '/features', 1),
            $row('Contact Us', '/contact-us', 1),
            $row('Product', '', 0),
            $row('Platform', '/platform', 1),
            $row('Blog', '/blog', 1),
            $row('FAQ', '/#faq', 1),
            $row('Utility Pages', '', 0),
            $row('Terms & Condition', '/terms-of-service', 1),
            $row('Privacy Policy', '/privacy-policy', 1),
        ]);

        $value = (array) $setting->value;
        if (blank($value['app_name'] ?? null) || $value['app_name'] === 'Laravel') {
            $value['app_name'] = 'Ethaika';
        }
        if (empty($value['social_links'])) {
            $value['social_links'] = [['platform' => 'LinkedIn', 'url' => 'https://www.linkedin.com/company/ethaika/']];
        }
        $setting->value = $value;
        $setting->save();

        Settings::flush();
        $menus->flush();
    }

    private function save(MenuBuilder $menus, string $key, array $items): void
    {
        $menu = $menus->menu($key);
        if (! empty($menus->editorRows($menu))) {
            return;
        }
        [$clean, $errors] = $menus->validate($menu, ['items' => $items]);

        if ($errors) {
            throw new RuntimeException("Ethaika {$key} menu is invalid: ".json_encode($errors));
        }

        $menus->save($menu, $clean);
    }
}
