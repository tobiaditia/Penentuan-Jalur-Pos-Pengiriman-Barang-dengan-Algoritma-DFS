<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PublicController@index');

Route::resource('barang', 'BarangController');
Route::get('kurir/getMaxId', 'KurirController@getMaxId');
Route::post('kurir/store-pengiriman', 'KurirController@storePengiriman');
Route::get('kurir/tambah-pengiriman/{id}', 'KurirController@tambahPengiriman');
Route::get('kurir/lihat-rute/{id}', 'KurirController@lihatRute');
Route::get('kurir/lihat-pesanan/{id}', 'KurirController@lihatPesanan');
Route::get('kurir/get-nama-areacode/{areacode}', 'KurirController@getNamaAreacode');
Route::get('kurir/im-kurir', 'KurirController@imKurir');
Route::get('kurir/im-kurir/{id}', 'KurirController@imKurirId');
Route::get('kurir/update-pengiriman/{id}/{kode}', 'KurirController@updatePengiriman');
Route::resource('kurir', 'KurirController');


Route::get('pesanan/belum-dikirim', 'PesananController@belumDiKirim')->name('pesanan.belum-dikirim');
Route::get('pesanan/sedang-dikirim', 'PesananController@sedangDiKirim')->name('pesanan.sedang-dikirim');
Route::resource('pesanan', 'PesananController');

Route::get('/public/get-kecamatan/{id_kab}', 'PublicController@getKecamatan');
Route::get('/public/lacak', 'PublicController@lacak');
Route::get('/public/get-lacak/{resi}', 'PublicController@getLacak');
Route::get('/public/get-kelurahan/{id_kab}/{id_kec}', 'PublicController@getKelurahan');
// Route::post('/public/select-fetch', 'PublicController@select-fetch')->name('public.selectfetch');

Route::resource('public', 'PublicController');
