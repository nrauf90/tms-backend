<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RolesController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\CurrencyController;
use App\Http\Controllers\API\OfflinePaymentController;
use App\Http\Controllers\API\TimeZoneController;
use App\Http\Controllers\API\LanguageController;
use App\Http\Controllers\API\WeekController;
use App\Http\Controllers\API\StripeController;
use App\Http\Controllers\API\PaypalController;
use App\Http\Controllers\API\PackageController;
use App\Http\Controllers\API\ModuleController;
use App\Http\Controllers\API\PermissionGroupController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\CountryController;
use App\Http\Controllers\API\ResponseTestingController;
use App\Http\Controllers\API\CompanyUserController;
use Illuminate\Support\Facades\Broadcast;
use Spatie\Permission\Contracts\Role;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// pusher authentication for private channels
Broadcast::routes(['middleware' => ['auth:sanctum']]);


Route::controller(RegisterController::class)->group(function () {
    Route::post('register',  'register');

    Route::post('login',  'login');
});
Route::controller(UserController::class)->group(function () {
    Route::get('role/{role}/permission/{permission}',  'assignPermissionToRole');
    Route::get('view-assign-permissions-to-roles',  'viewAssignPermissionToRole');

    Route::delete('role/{role}/permission/{permission}',  'removePermissionFromRole');
    Route::post('/resetPasswordLink', 'sendresetPasswordLink');
    Route::post('/reset-password', 'resetPassword');
});
Route::controller(PermissionController::class)->group(function () {
    Route::get('permission',  'index');

    Route::post('permission/{permission}',  'createPermission');

    Route::delete('permission/{permission}',  'destroy');

    Route::post('/assignPermissiontoRole/{id}', 'assignPermissionToRole');

    Route::post('/createPermissionGroup' , 'createPermissionGroup');

    Route::get('/getAllGroups', 'getGroups');

    Route::delete('/deletePermissionGroup/{id}', 'deletePermissionGroup');

    Route::post('/addGenericPermissions', 'createGenericPermissions');

    Route::get('/getGenericPermissions', 'getGenericPermissions');

    Route::get('/getGroupedPermissions', 'getGroupPermissions');

    Route::get('/getPermission/{id}', 'getPermission');
});
Route::controller(RolesController::class)->group(function () {

    Route::post('role/{role}', 'addRole');
    Route::get('role/{role}', 'showSingleRole');
    Route::get('user/role', 'index');
    Route::post('/user/role/{id}/edit', 'editRole');
    Route::get('role/{id}/permissions', 'rolePermissions');
    Route::get('/showRole/{id}', 'show');
});


Route::middleware(['auth:sanctum'])->group(function () {

    Route::controller(UserController::class)->group(function () {

        Route::put('user/{user}/profile', 'userProfile');

        Route::post('changepassword/{user}',  'changepassword');

        Route::post('/addNewUser', 'store');

        Route::get('/getAllUsers', 'index');

        Route::post('/updateUser/{id}', 'update');

        Route::delete('/deleteUser/{id}', 'destroy');

        Route::get('/loggedInUser', 'getSignedInUser');

        Route::get('/showUser/{id}', 'show');
    });

    Route::controller(PermissionController::class)->group(function () {
        Route::get('user/{user}/permission/{permission}', 'assignPermissionToUser');

       // Route::post('/assignPermissiontoRole/{id}', 'assignPermissionToRole');

        Route::get('user/{user}/permission', 'showPermissionsAssignedToUser');

        Route::delete('user/{user}/permission/{permission}', 'revokePermissionFromUser');

        Route::post('edit/permission/{id}', 'editPermission');
    });

    Route::controller(RolesController::class)->group(function () {
        Route::get('user/{user}/role', 'showUserWithRole');

        Route::post('role/{role}/user/{user}', 'rolesAddUser');

        Route::delete('role/{role}/user/{user}', 'rolesRemoveUser');
    });

    Route::controller(NotificationController::class)->group(function () {
        Route::get('/notifications', 'index');
        Route::get('/unreadNotifications', 'getUnreadNotifications');
        Route::post('/newNotification', 'store');
        Route::get('/showNotification/{id}', 'show');
        Route::get('/readAllNotifications/{id}', 'readAllNotifications');
        Route::get('/readNotification/{id}', 'readNotification');
        Route::delete('/deleteNotification/{id}', 'destroy');
    });

    Route::controller(CurrencyController::class)->group(function () {
        Route::get('/currencies', 'index');
        Route::post('/createCurrency', 'store');
        Route::post('/updateCurrency/{id}', 'update');
        Route::delete('/deleteCurrency/{id}', 'destroy');
        Route::get('/allCurrencies', 'getAllCurrencies');
    });

    Route::controller(OfflinePaymentController::class)->group(function () {
        Route::get('/offlineMethods', 'index');
        Route::post('/createOfflineMethod', 'store');
        Route::post('/updateOfflineMethod/{id}', 'update');
        Route::delete('/deleteOfflineMethod/{id}', 'destroy');
    });

    Route::controller(TimeZoneController::class)->group(function (){
        Route::get('/timezones', 'index');
        Route::post('/createTimezones', 'store');
        Route::post('/updateTimezones/{id}', 'update');
        Route::delete('/deleteTimezones/{id}', 'destroy');
    });


    Route::controller(LanguageController::class)->group(function (){
        Route::get('/languages', 'index');
        Route::post('/createLanguages', 'store');
        Route::post('/updateLanguages/{id}', 'update');
        Route::delete('/deleteLanguages/{id}', 'destroy');
    });

    Route::controller(WeekController::class)->group(function (){
        Route::get('/days', 'index');
    });

    Route::controller(StripeController::class)->group(function (){
        Route::post('/storeStripeInfo', 'store');
        Route::get('/stripeInfo', 'getStripeGatewayInformation');
        Route::post('/updateStripeInfo/{id}', 'update');
    });

    Route::controller(PaypalController::class)->group(function (){
        Route::get('/paypalInfo', 'getPaypalGatewayInformation');
        Route::post('/storePaypalInfo', 'store');
        Route::post('/updatePaypalInfo/{id}', 'update');
    });

    Route::controller(PackageController::class)->group(function (){
        Route::get('/packages', 'index');
        Route::post('/createPackages', 'store');
        Route::post('/updatePackages/{id}', 'update');
        Route::delete('/deletePackages/{id}', 'destroy');
        Route::get('/packageDetails/{id}', 'details');
        Route::get('/allPackages', 'allPackages');
    });

    Route::controller(ModuleController::class)->group(function (){
        Route::get('/modules', 'index');
    });

    Route::controller(PermissionGroupController::class)->group(function (){
        Route::get('/groups', 'index');
        Route::post('/createGroup', 'store');
        Route::post('/updateGroup/{id}', 'update');
        Route::delete('/deleteGroup/{id}', 'destroy');
    });

    Route::controller(CompanyController::class)->group(function (){
        Route::get('/companies', 'index');
        Route::post('/createCompany', 'store');
        Route::post('/updateCompany/{id}', 'update');
        Route::delete('/deleteCompany/{id}', 'destroy');
        Route::get('/showCompany/{id}', 'show');
        Route::post('/updateCompanyAdmin/{id}', 'updateCompany');
        Route::get('/showCompany', 'showCompany');
    });

    Route::controller(CountryController::class)->group(function (){
        Route::get('/countries', 'index');
        //Route::get('/responseTesting', 'responseTesting');
    });

    Route::controller(ResponseTestingController::class)->group(function (){
        Route::get('/responseTesting', 'index');
    });

    Route::controller(CompanyUserController::class)->group(function (){
        Route::get('/companyUsers', 'index');
        Route::post('/createCompanyUser', 'store');
        Route::post('/updateCompanyUser/{id}', 'update');
        Route::delete('/deleteCompanyUser/{id}', 'destroy');
    });

    Route::apiResources([
        'user' => UserController::class,
        'roles' => RolesController::class,
        // 'servicetaker' => ServiceTakerController::class,
    ]);
});
