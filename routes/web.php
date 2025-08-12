<?php

use App\Helpers\CommonHelpers;
use App\Models\Outgoing;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});


Route::get('/outgoings/{outgoing}/print', function (Outgoing $outgoing) {
    return CommonHelpers::downloadOutgoing($outgoing->id);
})->name('outgoings.print');

