<?php
$router->group(
    ['prefix' => 'admin'],
    function ($router) {
        $router->group(
            ['middleware' => 'admin.auth'],
            function ($router) {
                //--standard routes
                $router->any('/', ['as' => 'admin.home', 'uses' => 'Backend\BackendController@getIndex']);
                $router->any('/home', 'Backend\BackendController@getIndex');
                
                // users
                $router->get(
                    'user/{id}/coupons',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.user.coupons',
                        'uses'       => 'Backend\UserController@coupons',
                    ]
                );
                $router->post(
                    'user/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.user.ajax_field',
                        'uses'       => 'Backend\UserController@ajaxFieldChange',
                    ]
                );
                $router->get(
                    'user/new_password/{id}',
                    ['as' => 'admin.user.new_password.get', 'uses' => 'Backend\UserController@getNewPassword']
                );
                $router->post(
                    'user/new_password/{id}',
                    ['as' => 'admin.user.new_password.post', 'uses' => 'Backend\UserController@postNewPassword']
                );
                $router->post(
                    'user/coupon',
                    ['as' => 'admin.user.coupon.store', 'uses' => 'Backend\UserController@storeCoupon']
                );
                $router->post(
                    'user/{user_id}/coupon/{coupon_id}/default',
                    ['as' => 'admin.user.coupon.default', 'uses' => 'Backend\UserController@makeDefaultCoupon']
                );
                $router->resource('user', 'Backend\UserController');
                
                // groups
                $router->resource('group', 'Backend\GroupController');
                
                // pages
                $router->post(
                    'page/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.page.ajax_field',
                        'uses'       => 'Backend\PageController@ajaxFieldChange',
                    ]
                );
                $router->resource('page', 'Backend\PageController');
                
                // tag
                $router->post(
                    'tag/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.tag.ajax_field',
                        'uses'       => 'Backend\TagController@ajaxFieldChange',
                    ]
                );
                $router->resource('tag', 'Backend\TagController');
                
                // blog
                $router->post(
                    'news/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.news.ajax_field',
                        'uses'       => 'Backend\NewsController@ajaxFieldChange',
                    ]
                );
                $router->resource('news', 'Backend\NewsController');
                
                // articles
                $router->post(
                    'article/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.article.ajax_field',
                        'uses'       => 'Backend\ArticleController@ajaxFieldChange',
                    ]
                );
                $router->resource('article', 'Backend\ArticleController');
                
                // comments
                $router->post(
                    'comment/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.comment.ajax_field',
                        'uses'       => 'Backend\CommentController@ajaxFieldChange',
                    ]
                );
                $router->resource('comment', 'Backend\CommentController');
                
                // questions
                $router->post(
                    'question/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.question.ajax_field',
                        'uses'       => 'Backend\QuestionController@ajaxFieldChange',
                    ]
                );
                $router->resource('question', 'Backend\QuestionController');
                
                // menus
                $router->post(
                    'menu/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.menu.ajax_field',
                        'uses'       => 'Backend\MenuController@ajaxFieldChange',
                    ]
                );
                $router->resource('menu', 'Backend\MenuController');
                
                // banners
                $router->post(
                    'banner/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.banner.ajax_field',
                        'uses'       => 'Backend\BannerController@ajaxFieldChange',
                    ]
                );
                $router->resource('banner', 'Backend\BannerController');
                
                // text_widgets
                $router->post(
                    'text_widget/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.text_widget.ajax_field',
                        'uses'       => 'Backend\TextWidgetController@ajaxFieldChange',
                    ]
                );
                $router->resource('text_widget', 'Backend\TextWidgetController');
                
                // variables
                $router->post(
                    'variable/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.variable.ajax_field',
                        'uses'       => 'Backend\VariableController@ajaxFieldChange',
                    ]
                );
                $router->get(
                    'variable/value/index',
                    ['as' => 'admin.variable.value.index', 'uses' => 'Backend\VariableController@indexValues']
                );
                $router->post(
                    'variable/value/update',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.variable.value.update',
                        'uses'       => 'Backend\VariableController@updateValue',
                    ]
                );
                $router->resource('variable', 'Backend\VariableController');
                
                // translations
                $router->get(
                    'translation/{group}',
                    ['as' => 'admin.translation.index', 'uses' => 'Backend\TranslationController@index']
                );
                $router->post(
                    'translation/{group}',
                    ['as' => 'admin.translation.update', 'uses' => 'Backend\TranslationController@update']
                );
                
                //--lets cook routes
                
                //files download
                $router->get(
                    'download-file',
                    ['as' => 'admin.download.file', 'uses' => 'Backend\BackendController@downloadFile']
                );
                
                //category
                $router->get(
                    'category/{id}/get-delete-form',
                    [
                        'as'         => 'category.get_delete_form',
                        'middleware' => 'ajax',
                        'uses'       => 'Backend\CategoryController@getDeleteForm',
                    ]
                );
                $router->get(
                    'category/{id}/completed-ingredients',
                    [
                        'as'         => 'category.completed_ingredients',
                        'middleware' => 'ajax',
                        'uses'       => 'Backend\CategoryController@completedIngredients',
                    ]
                );
                $router->resource('category', 'Backend\CategoryController');
                
                //units
                $router->get(
                    'unit/{id}/get-delete-form',
                    [
                        'as'         => 'unit.get_delete_form',
                        'middleware' => 'ajax',
                        'uses'       => 'Backend\UnitController@getDeleteForm',
                    ]
                );
                $router->resource('unit', 'Backend\UnitController');
                
                //parameters
                $router->resource('parameter', 'Backend\ParameterController');
                
                //nutritional_values
                $router->resource('nutritional_value', 'Backend\NutritionalValueController');
                
                //ingredients
                $router->post(
                    'ingredient/{id}/ajax_field',
                    [
                        'middleware' => 'ajax',
                        'as'         => 'admin.ingredient.ajax_field',
                        'uses'       => 'Backend\IngredientController@ajaxFieldChange',
                    ]
                );
                $router->get(
                    'ingredient/incomplete',
                    [
                        'as'   => 'admin.ingredient.incomplete',
                        'uses' => 'Backend\IngredientController@indexIncomplete',
                    ]
                );
                $router->get(
                    'ingredient/find',
                    [
                        'middleware' => 'ajax',
                        'as'         => 'admin.ingredient.find',
                        'uses'       => 'Backend\IngredientController@find',
                    ]
                );
                $router->get(
                    'ingredient/quick-create',
                    [
                        'middleware' => 'ajax',
                        'as'         => 'admin.ingredient.quick_create',
                        'uses'       => 'Backend\IngredientController@quickCreate',
                    ]
                );
                $router->post(
                    'ingredient/quick-store',
                    [
                        'middleware' => 'ajax',
                        'as'         => 'admin.ingredient.quick_store',
                        'uses'       => 'Backend\IngredientController@quickStore',
                    ]
                );
                $router->resource('ingredient', 'Backend\IngredientController');
                
                //recipe
                $router->post(
                    'recipe/{id}/ajax_field',
                    [
                        'middleware' => 'ajax',
                        'as'         => 'admin.recipe.ajax_field',
                        'uses'       => 'Backend\RecipeController@ajaxFieldChange',
                    ]
                );
                $router->get(
                    'recipe/get-ingredient-row/{ingredient_id}/{type}',
                    [
                        'middleware' => 'ajax',
                        'as'         => 'admin.recipe.get_ingredient_row',
                        'uses'       => 'Backend\RecipeController@getIngredientRow',
                    ]
                );
                $router->get(
                    'recipe/{id}/get-delete-form',
                    [
                        'as'         => 'recipe.get_delete_form',
                        'middleware' => 'ajax',
                        'uses'       => 'Backend\RecipeController@getDeleteForm',
                    ]
                );
                $router->get(
                    'recipe/{id}/get-copy-form',
                    [
                        'as'         => 'admin.recipe.get_copy_form',
                        'middleware' => 'ajax',
                        'uses'       => 'Backend\RecipeController@getCopyForm',
                    ]
                );
                $router->get(
                    'recipe/{id}/copy',
                    ['as' => 'admin.recipe.copy', 'uses' => 'Backend\RecipeController@copy']
                );
                $router->get(
                    'recipe/index_find',
                    [
                        'as'         => 'admin.recipe.index_find',
                        'middleware' => 'ajax',
                        'uses'       => 'Backend\RecipeController@indexFind',
                    ]
                );
                $router->resource('recipe', 'Backend\RecipeController');
                
                //suppliers
                $router->get(
                    'supplier/{id}/get-delete-form',
                    [
                        'as'         => 'supplier.get_delete_form',
                        'middleware' => 'ajax',
                        'uses'       => 'Backend\SupplierController@getDeleteForm',
                    ]
                );
                $router->resource('supplier', 'Backend\SupplierController');
                
                //baskets
                $router->get(
                    'basket/get-recipe-row/{recipe_id}',
                    [
                        'middleware' => 'ajax',
                        'as'         => 'admin.basket.get_recipe_row',
                        'uses'       => 'Backend\BasketController@getRecipeRow',
                    ]
                );
                $router->resource('basket', 'Backend\BasketController');
                
                //weekly menu
                $router->get(
                    'weekly_menu/current',
                    ['as' => 'admin.weekly_menu.current', 'uses' => 'Backend\WeeklyMenuController@current']
                );
                $router->get(
                    'weekly_menu/get-basket-select-popup',
                    [
                        'middleware' => 'ajax',
                        'as'         => 'admin.weekly_menu.get_basket_select_popup',
                        'uses'       => 'Backend\WeeklyMenuController@getBasketSelectPopup',
                    ]
                );
                $router->get(
                    'weekly_menu/add-basket',
                    [
                        'middleware' => 'ajax',
                        'as'         => 'admin.weekly_menu.add_basket',
                        'uses'       => 'Backend\WeeklyMenuController@addBasket',
                    ]
                );
                $router->get(
                    'weekly_menu/{basket_id}/{portions}/get-recipe-item/{recipe_id}/{copy}',
                    [
                        'middleware' => 'ajax',
                        'as'         => 'admin.weekly_menu.get_recipe_item',
                        'uses'       => 'Backend\WeeklyMenuController@getRecipeItem',
                    ]
                )->where('basket_id', '[0-9]+')->where('portions', '[0-9]+');
                $router->get(
                    'weekly_menu/{basket_id}/{portions}/get-basket-available-recipes',
                    [
                        'middleware' => 'ajax',
                        'as'         => 'admin.weekly_menu.get_basket_available_recipes',
                        'uses'       => 'Backend\WeeklyMenuController@getBasketAvailableRecipes',
                    ]
                )->where('basket_id', '[0-9]+')->where('portions', '[0-9]+');
                $router->get(
                    'weekly_menu/get-basket-copy-form/{basket_id}/{portions}',
                    [
                        'as'         => 'admin.weekly_menu.get_basket_copy_form',
                        'middleware' => 'ajax',
                        'uses'       => 'Backend\WeeklyMenuController@getBasketCopyForm',
                    ]
                );
                $router->resource('weekly_menu', 'Backend\WeeklyMenuController', ['except' => ['destroy']]);
                
                //cities
                $router->resource('city', 'Backend\CityController');
                
                //coupons
                $router->resource('coupon', 'Backend\CouponController');
                
                //orders
                $router->post(
                    'order/{order_id}/update_status',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.order.update_status',
                        'uses'       => 'Backend\OrderController@updateStatus',
                    ]
                )->where('order_id', '[0-9]+');
                $router->get(
                    'order/get-weekly-menu-baskets/{weekly_menu_id}',
                    [
                        'middleware' => 'ajax',
                        'as'         => 'admin.order.get_weekly_menu_baskets',
                        'uses'       => 'Backend\OrderController@getWeeklyMenuBaskets',
                    ]
                );
                $router->get(
                    'order/get-basket-recipes/{basket_id}',
                    [
                        'middleware' => 'ajax',
                        'as'         => 'admin.order.get_basket_recipes',
                        'uses'       => 'Backend\OrderController@getBasketRecipes',
                    ]
                );
                $router->get(
                    'order/get-basket-recipes-ingredients/{basket_id}',
                    [
                        'middleware' => 'ajax',
                        'as'         => 'admin.order.get_basket_recipes_ingredients',
                        'uses'       => 'Backend\OrderController@getBasketRecipesIngredients',
                    ]
                );
                $router->get(
                    'order/get-recipe-row/{recipe_id}',
                    [
                        'middleware' => 'ajax',
                        'as'         => 'admin.order.get_recipe_row',
                        'uses'       => 'Backend\OrderController@getRecipeRow',
                    ]
                );
                $router->get(
                    'order/get-ingredient-row/{basket_recipe_id}/{ingredient_id}',
                    [
                        'middleware' => 'ajax',
                        'as'         => 'admin.order.get_ingredient_row',
                        'uses'       => 'Backend\OrderController@getIngredientRow',
                    ]
                );
                $router->post(
                    'order/comment',
                    [
                        'middleware' => 'ajax',
                        'as'         => 'admin.order.comment.store',
                        'uses'       => 'Backend\OrderController@storeComment',
                    ]
                );
                $router->get(
                    'order/history',
                    ['as' => 'admin.order.history', 'uses' => 'Backend\OrderController@history']
                );
                $router->get(
                    'order/basket-recipe/{basket_recipe_id}/count',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.order.basket_recipe_orders_count',
                        'uses'       => 'Backend\OrderController@basketRecipesOrdersCount',
                    ]
                )->where('recipe_id', '[0-9]+');
                $router->get(
                    'order/weekly-menu-basket/{weekly_menu_basket_id}/count',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.order.weekly_menu_basket_orders_count',
                        'uses'       => 'Backend\OrderController@weeklyMenuBasketOrdersCount',
                    ]
                )->where('weekly_menu_basket_id', '[0-9]+');
                $router->resource('order', 'Backend\OrderController', ['except' => ['destroy']]);
                
                //purchase
                $router->group(
                    ['prefix' => 'purchase'],
                    function ($router) {
                        $router->post(
                            '{id}/ajax_field',
                            [
                                'middleware' => ['ajax'],
                                'as'         => 'admin.purchase.ajax_field',
                                'uses'       => 'Backend\PurchaseController@ajaxFieldChange',
                            ]
                        );
                        $router->post(
                            '{id}/set-ingredient-price',
                            [
                                'middleware' => ['ajax'],
                                'as'         => 'admin.purchase.set_ingredient_price',
                                'uses'       => 'Backend\PurchaseController@setIngredientPrice',
                            ]
                        );
                        
                        $router->get(
                            'index',
                            ['as' => 'admin.purchase.index', 'uses' => 'Backend\PurchaseController@index']
                        );
                        
                        $router->get(
                            'show/{year}/{week}',
                            ['as' => 'admin.purchase.show', 'uses' => 'Backend\PurchaseController@show']
                        );
                        
                        $router->get(
                            'edit/{year}/{week}',
                            ['as' => 'admin.purchase.edit', 'uses' => 'Backend\PurchaseController@edit']
                        );
                        
                        $router->get(
                            'generate/{year}/{week}',
                            ['as' => 'admin.purchase.generate', 'uses' => 'Backend\PurchaseController@generate']
                        );
                        
                        $router->get(
                            'update/{id}',
                            ['as' => 'admin.purchase.update', 'uses' => 'Backend\PurchaseController@index']
                        );
                        
                        $router->get(
                            '{year}/{week}/download/{supplier_id?}',
                            ['as' => 'admin.purchase.download', 'uses' => 'Backend\PurchaseController@download']
                        );
                        
                        $router->get(
                            '{year}/{week}/download-pre-report/{supplier_id?}',
                            [
                                'as'   => 'admin.purchase.download_pre_report',
                                'uses' => 'Backend\PurchaseController@downloadPreReport',
                            ]
                        );
                    }
                );
                
                //packaging
                $router->group(
                    ['prefix' => 'packaging'],
                    function ($router) {
                        $router->get(
                            'index',
                            ['as' => 'admin.packaging.index', 'uses' => 'Backend\PackagingController@index']
                        );
                        
                        $router->get(
                            'current',
                            ['as' => 'admin.packaging.current', 'uses' => 'Backend\PackagingController@current']
                        );
                        
                        $router->get(
                            'show/{year}/{week}',
                            ['as' => 'admin.packaging.show', 'uses' => 'Backend\PackagingController@show']
                        );
                        
                        $router->get(
                            'tab/{tab}/{year}/{week}',
                            [
                                'middleware' => 'ajax',
                                'as'         => 'admin.packaging.tab',
                                'uses'       => 'Backend\PackagingController@tab',
                            ]
                        );
                        
                        $router->get(
                            'download/{tab}/{year}/{week}',
                            [
                                'as'   => 'admin.packaging.download',
                                'uses' => 'Backend\PackagingController@download',
                            ]
                        );
                        
                        $router->post(
                            'update_booklet',
                            [
                                'as'   => 'admin.packaging.update_booklet',
                                'uses' => 'Backend\PackagingController@updateBooklet',
                            ]
                        );
    
                        $router->get(
                            'all',
                            [
                                'as'   => 'admin.packaging.all',
                                'uses' => 'Backend\PackagingController@all',
                            ]
                        );
                    }
                );
                
                //tag categories
                $router->post(
                    'tag_category/{id}/ajax_field',
                    [
                        'middleware' => ['ajax'],
                        'as'         => 'admin.tag_category.ajax_field',
                        'uses'       => 'Backend\TagCategoryController@ajaxFieldChange',
                    ]
                );
                $router->resource('tag_category', 'Backend\TagCategoryController');
                
                //subscribes
                $router->get(
                    'subscribe',
                    ['as' => 'admin.subscribe.index', 'uses' => 'Backend\SubscribeController@index']
                );
                $router->get(
                    'subscribe/export',
                    ['as' => 'admin.subscribe.export', 'uses' => 'Backend\SubscribeController@export']
                );
            }
        );
        
        $router->group(
            ['prefix' => 'auth'],
            function ($router) {
                $router->get('login', ['as' => 'admin.login', 'uses' => 'Backend\AuthController@getLogin']);
                $router->post('login', ['as' => 'admin.login.post', 'uses' => 'Backend\AuthController@postLogin']);
                $router->get('logout', ['as' => 'admin.logout', 'uses' => 'Backend\AuthController@getLogout']);
            }
        );
    }
);
