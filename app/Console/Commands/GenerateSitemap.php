<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate public sitemap.xml';

    public function handle(): void
    {
        $base = config('app.url');

        Sitemap::create()
            ->add(Url::create('/')->setPriority(1.0)->setChangeFrequency('weekly'))
            ->add(Url::create('/pricing')->setPriority(0.8)->setChangeFrequency('monthly'))
            ->add(Url::create('/about')->setPriority(0.6)->setChangeFrequency('monthly'))
            ->add(Url::create('/login')->setPriority(0.5)->setChangeFrequency('yearly'))
            ->add(Url::create('/register')->setPriority(0.5)->setChangeFrequency('yearly'))
            ->writeToFile(public_path('sitemap.xml'));

        $this->info('sitemap.xml generated at public/sitemap.xml');
    }
}
