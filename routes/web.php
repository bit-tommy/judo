<?php

use App\Http\Controllers\DownloadController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Main pages
Volt::route('/', 'pages.home-page')->name('home');

// Dynamický sitemap – jen živé (neredirektující) stránky.
Route::get('/sitemap.xml', function () {
    $pages = [
        ['route' => 'home',                 'priority' => '1.0', 'freq' => 'weekly'],
        ['route' => 'kodokan.masters-stay', 'priority' => '0.7', 'freq' => 'monthly'],
        ['route' => 'instructors',          'priority' => '0.7', 'freq' => 'monthly'],
        ['route' => 'children',             'priority' => '0.7', 'freq' => 'monthly'],
        ['route' => 'events',               'priority' => '0.6', 'freq' => 'weekly'],
        ['route' => 'gallery',              'priority' => '0.7', 'freq' => 'monthly'],
        ['route' => 'downloads',            'priority' => '0.5', 'freq' => 'monthly'],
    ];

    $urls = collect($pages)->map(fn ($p) => [
        'loc' => route($p['route']),
        'priority' => $p['priority'],
        'freq' => $p['freq'],
    ]);

    return response()
        ->view('sitemap', ['urls' => $urls])
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

// Samostatná stránka „Pobyt japonských mistrů u nás"
Volt::route('/pobyt-japonskych-mistru', 'pages.kodokan.pobyt-mistru')->name('kodokan.masters-stay');

// Stránka „Klub – ke stažení"
Volt::route('/ke-stazeni', 'pages.ke-stazeni')->name('downloads');

// Stránka „Instruktoři"
Volt::route('/instruktori', 'pages.instruktori')->name('instructors');

// Stránka „Galerie"
Volt::route('/galerie', 'pages.gallery-page')->name('gallery');

// Stránka „Tréninky dětí"
Volt::route('/treninky-deti', 'pages.deti')->name('children');

// Stránka „Akce" (akce klubu z databáze, spravované administrací)
Volt::route('/akce', 'pages.akce')->name('events');

// Stahování dokumentů — počítá stažení a vydá soubor / přesměruje na externí odkaz
Route::get('/stahnout/{document}', DownloadController::class)->name('documents.download');

// ─── Administrace ───
// Login je mimo auth skupinu; přihlášené přesměruje mount() komponenty.
Volt::route('/admin/login', 'pages.admin.login')->name('admin.login');

Route::middleware('auth')->group(function () {
    Volt::route('/admin', 'pages.admin.prehled')->name('admin.dashboard');
    Volt::route('/admin/clenove', 'pages.admin.clenove')->name('admin.members');
    Volt::route('/admin/rozvrh', 'pages.admin.rozvrh')->name('admin.schedule');
    Volt::route('/admin/akce', 'pages.admin.akce')->name('admin.events');
    Volt::route('/admin/galerie', 'pages.admin.galerie')->name('admin.gallery');
    Volt::route('/admin/dokumenty', 'pages.admin.dokumenty')->name('admin.documents');
    Volt::route('/admin/analytika', 'pages.admin.analytika')->name('admin.analytics');

    Route::post('/admin/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('admin.login');
    })->name('admin.logout');
});

// Other pages temporarily disabled — redirect to home
Route::get('/kontakt', fn () => redirect('/'))->name('contact');
Route::get('/odkazy', fn () => redirect('/'))->name('links');
Route::get('/sebeobrana', fn () => redirect('/'))->name('self-defense');
Route::get('/o-klubu', fn () => redirect('/'))->name('club');
Route::get('/kodokan-judo', fn () => redirect('/'))->name('kodokan.index');
Route::get('/kodokan-judo/historie', fn () => redirect('/'))->name('kodokan.history');
Route::get('/kodokan-judo/kata', fn () => redirect('/'))->name('kodokan.kata');
Route::get('/kodokan-judo/techniky', fn () => redirect('/'))->name('kodokan.techniques');
Route::get('/kodokan-judo/japonsti-mistri', fn () => redirect('/'))->name('kodokan.japanese-masters');
Route::get('/kodokan-judo/japonsko-2016-2019', fn () => redirect('/'))->name('kodokan.japan-trips');
Route::get('/treninky', fn () => redirect('/'))->name('training.index');
Route::get('/treninky/cenik', fn () => redirect('/'))->name('training.pricing');
Route::get('/treninky/pripravka', fn () => redirect('/'))->name('training.preparatory');
Route::get('/treninky/pokrocili', fn () => redirect('/'))->name('training.advanced');
Route::get('/treninky/dospeli', fn () => redirect('/'))->name('training.adults');
Route::get('/treninky/hiko-ryu-taijutsu', fn () => redirect('/'))->name('training.hikoryu');
Route::get('/treninky/kondicni-cviceni-randori', fn () => redirect('/'))->name('training.randori');
Route::get('/aktuality', fn () => redirect('/'))->name('news.index');
Route::get('/aktuality/plan-akci', fn () => redirect('/akce'))->name('news.plan-akci');
Route::get('/aktuality/napsali-o-nas', fn () => redirect('/'))->name('news.napsali-o-nas');
Route::get('/aktuality/probehle-akce', fn () => redirect('/akce'))->name('news.probehle-akce');

// 301 Redirects from old URL structure
Route::redirect('/klub', '/o-klubu', 301);
Route::redirect('/vnitrni-rad', '/o-klubu', 301);
Route::redirect('/judo-vodochody', '/o-klubu', 301);
Route::redirect('/kodokan-judo/historie-judo', '/kodokan-judo/historie', 301);
Route::redirect('/kodokan-judo/kata-judo', '/kodokan-judo/kata', 301);
Route::redirect('/kodokan-judo/techniky-judo', '/kodokan-judo/techniky', 301);
Route::redirect('/treninky-a-cenik', '/treninky', 301);
Route::redirect('/rozpis-treninku', '/treninky', 301);
Route::redirect('/cenik', '/treninky/cenik', 301);
Route::redirect('/pripravka', '/treninky/pripravka', 301);
Route::redirect('/pokrocili', '/treninky/pokrocili', 301);
Route::redirect('/dospeli', '/treninky/dospeli', 301);
Route::redirect('/nabidka-sluzeb', '/sebeobrana', 301);
Route::redirect('/fotogalerie', '/galerie', 301);
Route::redirect('/videogalerie', '/galerie', 301);
Route::redirect('/downloads', '/ke-stazeni', 301);
Route::redirect('/kontakty', '/kontakt', 301);
