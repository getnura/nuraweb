<?php 

Route::get('hello', [TestController::class, 'test'])->name('test');

Route::get('/test', function () {
    echo "This is a test plugin!!!";    
});


