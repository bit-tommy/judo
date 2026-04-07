<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Main pages
Volt::route('/', 'pages.home-page')->name('home');
Volt::route('/kontakt', 'pages.contact-page')->name('contact');
Volt::route('/galerie', 'pages.gallery-page')->name('gallery');
Volt::route('/ke-stazeni', 'pages.downloads-page')->name('downloads');
Volt::route('/odkazy', 'pages.links-page')->name('links');
Volt::route('/sebeobrana', 'pages.self-defense-page')->name('self-defense');

// O klubu
Volt::route('/o-klubu', 'pages.club.club-page')->name('club');

// Kodokan Judo
Volt::route('/kodokan-judo', 'pages.kodokan.kodokan-page')->name('kodokan.index');
Volt::route('/kodokan-judo/historie', 'pages.kodokan.history-page')->name('kodokan.history');
Volt::route('/kodokan-judo/kata', 'pages.kodokan.kata-page')->name('kodokan.kata');
Volt::route('/kodokan-judo/techniky', 'pages.kodokan.techniques-page')->name('kodokan.techniques');
Volt::route('/kodokan-judo/japonsti-mistri', 'pages.kodokan.japanese-masters-page')->name('kodokan.japanese-masters');
Volt::route('/kodokan-judo/japonsko-2016-2019', 'pages.kodokan.japan-trips-page')->name('kodokan.japan-trips');

// Tréninky a ceník
Volt::route('/treninky', 'pages.training.trainings-page')->name('training.index');
Volt::route('/treninky/cenik', 'pages.training.pricing-page')->name('training.pricing');
Volt::route('/treninky/pripravka', 'pages.training.preparatory-page')->name('training.preparatory');
Volt::route('/treninky/pokrocili', 'pages.training.advanced-page')->name('training.advanced');
Volt::route('/treninky/dospeli', 'pages.training.adults-page')->name('training.adults');
Volt::route('/treninky/hiko-ryu-taijutsu', 'pages.training.hikoryu-page')->name('training.hikoryu');
Volt::route('/treninky/kondicni-cviceni-randori', 'pages.training.randori-page')->name('training.randori');

// Aktuality
Volt::route('/aktuality', 'pages.news.news-page')->name('news.index');
Volt::route('/aktuality/plan-akci', 'pages.news.plan-akci-page')->name('news.plan-akci');
Volt::route('/aktuality/napsali-o-nas', 'pages.news.napsali-o-nas-page')->name('news.napsali-o-nas');
Volt::route('/aktuality/probehle-akce', 'pages.news.probehle-akce-page')->name('news.probehle-akce');

// 301 Redirects from old URL structure
Route::redirect('/klub', '/o-klubu', 301);
Route::redirect('/instruktori', '/o-klubu', 301);
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
