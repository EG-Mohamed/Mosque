<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View as ViewFactory;
use Illuminate\View\View;

class LegalController extends Controller
{
    public function privacy(): View
    {
        return $this->render('privacy', __('Privacy Policy'));
    }

    public function cookies(): View
    {
        return $this->render('cookies', __('Cookie Policy'));
    }

    public function terms(): View
    {
        return $this->render('terms', __('Terms of Use'));
    }

    private function render(string $document, string $title): View
    {
        $locale = app()->getLocale();
        $contentView = ViewFactory::exists("pages.legal.{$document}.{$locale}")
            ? "pages.legal.{$document}.{$locale}"
            : "pages.legal.{$document}.en";

        return view('pages.legal.show', [
            'title' => $title,
            'contentView' => $contentView,
            'updatedAt' => '2026-11-23',
        ]);
    }
}
