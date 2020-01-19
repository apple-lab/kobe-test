<?php
// 檔案位置：routes/web.php
// 首頁
Route::get('/', 'MerchandiseController@indexPage');
Route::get('/abc',function(){
    return '123';
});

// 使用者
Route::group(['prefix' => 'user'], function(){
    // 使用者驗證
    Route::group(['prefix' => 'auth'], function(){
        Route::get('/sign-up', 'UserAuthController@signUpPage');
        Route::post('/sign-up', 'UserAuthController@signUpProcess');
        Route::get('/sign-in', 'UserAuthController@signInPage');
        Route::post('/sign-in', 'UserAuthController@signInProcess');
        Route::get('/sign-out', 'UserAuthController@signOut');
        // Facebook 登入
        Route::get('/facebook-sign-in', 'UserAuthController@facebookSignInProcess');
        // Facebook 登入重新導向授權資料處理
        Route::get('/facebook-sign-in-callback', 'UserAuthController@facebookSignInCallbackProcess');
        // Github 登入
        Route::get('/github-sign-in', 'UserAuthController@githubSignInProcess');
        // Github 登入重新導向授權資料處理
        Route::get('/github-sign-in-callback', 'UserAuthController@githubSignInCallbackProcess');
    });
});

// 商品
Route::group(['prefix' => 'merchandise'], function(){
    Route::get('/', 'MerchandiseController@merchandiseListPage');
    
    Route::get('/create', 'MerchandiseController@merchandiseCreateProcess')//参数，路由，方法
        ->middleware(['user.auth.admin']);
    Route::get('/manage', 'MerchandiseController@merchandiseManageListPage')
        ->middleware(['user.auth.admin']);
    
    // 指定商品
    Route::group(['prefix' => '{merchandise_id}'], function(){
        Route::get('/', 'MerchandiseController@merchandiseItemPage')
            ->where([
                'merchandise_id' => '[0-9]+',
            ]);
        
        Route::group(['middleware' => ['user.auth.admin']], function(){
            Route::get('/edit', 'MerchandiseController@merchandiseItemEditPage');
            Route::put('/', 'MerchandiseController@merchandiseItemUpdateProcess');
        });
        
        Route::post('/buy', 'MerchandiseController@merchandiseItemBuyProcess')
            ->middleware(['user.auth']);
    });
});

// 交易
Route::get('/transaction', 'TransactionController@transactionListPage')
    ->middleware(['user.auth']);