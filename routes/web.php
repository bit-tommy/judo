<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Main pages
Volt::route('/', 'pages.home-page')->name('home');

// Samostatná stránka „Pobyt japonských mistrů u nás"
Volt::route('/pobyt-japonskych-mistru', 'pages.kodokan.pobyt-mistru')->name('kodokan.masters-stay');

// Stránka „Klub – ke stažení"
Volt::route('/ke-stazeni', 'pages.ke-stazeni')->name('downloads');

// Stránka „Instruktoři"
Volt::route('/instruktori', 'pages.instruktori')->name('instructors');

// Other pages temporarily disabled — redirect to home
Route::get('/kontakt', fn() => redirect('/'))->name('contact');
Route::get('/galerie', fn() => redirect('/'))->name('gallery');
Route::get('/odkazy', fn() => redirect('/'))->name('links');
Route::get('/sebeobrana', fn() => redirect('/'))->name('self-defense');
Route::get('/o-klubu', fn() => redirect('/'))->name('club');
Route::get('/kodokan-judo', fn() => redirect('/'))->name('kodokan.index');
Route::get('/kodokan-judo/historie', fn() => redirect('/'))->name('kodokan.history');
Route::get('/kodokan-judo/kata', fn() => redirect('/'))->name('kodokan.kata');
Route::get('/kodokan-judo/techniky', fn() => redirect('/'))->name('kodokan.techniques');
Route::get('/kodokan-judo/japonsti-mistri', fn() => redirect('/'))->name('kodokan.japanese-masters');
Route::get('/kodokan-judo/japonsko-2016-2019', fn() => redirect('/'))->name('kodokan.japan-trips');
Route::get('/treninky', fn() => redirect('/'))->name('training.index');
Route::get('/treninky/cenik', fn() => redirect('/'))->name('training.pricing');
Route::get('/treninky/pripravka', fn() => redirect('/'))->name('training.preparatory');
Route::get('/treninky/pokrocili', fn() => redirect('/'))->name('training.advanced');
Route::get('/treninky/dospeli', fn() => redirect('/'))->name('training.adults');
Route::get('/treninky/hiko-ryu-taijutsu', fn() => redirect('/'))->name('training.hikoryu');
Route::get('/treninky/kondicni-cviceni-randori', fn() => redirect('/'))->name('training.randori');
Route::get('/aktuality', fn() => redirect('/'))->name('news.index');
Route::get('/aktuality/plan-akci', fn() => redirect('/'))->name('news.plan-akci');
Route::get('/aktuality/napsali-o-nas', fn() => redirect('/'))->name('news.napsali-o-nas');
Route::get('/aktuality/probehle-akce', fn() => redirect('/'))->name('news.probehle-akce');

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
