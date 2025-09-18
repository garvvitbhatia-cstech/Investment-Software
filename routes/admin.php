<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\AccountsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\BranchesController;
use App\Http\Controllers\Admin\BranchAdminController;
use App\Http\Controllers\Admin\AllyTypesController;
use App\Http\Controllers\Admin\AllysController;
use App\Http\Controllers\Admin\ExportsController;
use App\Http\Controllers\Admin\CustomersController;
use App\Http\Controllers\Admin\CustomerAccountsController;
use App\Http\Controllers\Admin\InvestmentsController;
use App\Http\Controllers\Admin\BrsController;
use App\Http\Controllers\Admin\SchemeBookingsController;
use App\Http\Controllers\Admin\CashCollectionReportController;
use App\Http\Controllers\Admin\BankCollectionReportController;
use App\Http\Controllers\Admin\ClearedCollectionReportController;
use App\Http\Controllers\Admin\BouncedCollectionReportController;
use App\Http\Controllers\Admin\TodaysMaturityController;
use App\Http\Controllers\Admin\OverdueMaturityController;
use App\Http\Controllers\Admin\UpcomingOverdueMaturityController;
use App\Http\Controllers\Admin\HolidaysController;




Route::prefix('panel')->group(function(){
	
	#account setup
	Route::get('/',[AdminController::class, 'login'])->name('admin.login');
	Route::get('/login',[AdminController::class, 'login'])->name('admin.login');
	Route::post('/admin-login',[AdminController::class, 'admin_login'])->name('admin.admin_login');
	Route::get('/logout',[AdminController::class, 'logout'])->name('admin.logout');
		
	#dashboard setup
	Route::get('/dashboard',[AdminController::class, 'dashboard'])->name('admin.dashboard');
	Route::get('/profile',[ProfileController::class, 'profile'])->name('admin.profile');
	Route::post('/save-profile',[ProfileController::class, 'saveProfile'])->name('admin.saveProfile');
	
	#change password
    Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('admin.change-password');
    Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('admin.update-password');

	#ajax
	Route::post('/change-status',[AjaxController::class, 'changeStatus'])->name('admin.change-status');
    Route::post('/delete-record',[AjaxController::class, 'deleteRecord'])->name('admin.delete-record');
	
	#users
    Route::get('/customers',[CustomersController::class, 'getList'])->name('admin.customers');
    Route::any('/customers_paginate',[CustomersController::class, 'listPaginate'])->name('admin.customers_paginate');
	Route::any('/edit-customer/{row_id}',[CustomersController::class, 'editPage'])->name('admin.edit-customer');
	Route::any('/add-customer',[CustomersController::class, 'addPage'])->name('admin.add-customer');
	
	#bank-accounts
    Route::get('/bank-accounts/{row_id}',[CustomerAccountsController::class, 'getList'])->name('admin.bank-acccounts');
    Route::any('/bank_accounts_paginate',[CustomerAccountsController::class, 'listPaginate'])->name('admin.bank_accounts_paginate');
	Route::any('/edit-bank-account/{row_id}',[CustomerAccountsController::class, 'editPage'])->name('admin.edit-bank-account');
	Route::any('/add-bank-account/{row_id?}',[CustomerAccountsController::class, 'addPage'])->name('admin.add-bank-account');

	#brs
    Route::get('/brs',[BrsController::class, 'getList'])->name('admin.brs');
    Route::any('/brs_paginate',[BrsController::class, 'listPaginate'])->name('admin.brs_paginate');
	Route::any('/brs-update',[BrsController::class, 'editPage'])->name('admin.brs-update');
	
	#cash
    Route::get('/cash-collection-report',[CashCollectionReportController::class, 'getList'])->name('admin.cash-collection-report');
    Route::any('/cash_collection_report_paginate',[CashCollectionReportController::class, 'listPaginate'])->name('admin.cash_collection_report_paginate');
	
	#bank
    Route::get('/bank-collection-report',[BankCollectionReportController::class, 'getList'])->name('admin.bank-collection-report');
    Route::any('/bank_collection_report_paginate',[BankCollectionReportController::class, 'listPaginate'])->name('admin.bank_collection_report_paginate');
	
	#cleared
    Route::get('/cleared-collection-report',[ClearedCollectionReportController::class, 'getList'])->name('admin.cleared-collection-report');
    Route::any('/cleared_collection_report_paginate',[ClearedCollectionReportController::class, 'listPaginate'])->name('admin.cleared_collection_report_paginate');
	
	#cleared
    Route::get('/bounced-collection-report',[BouncedCollectionReportController::class, 'getList'])->name('admin.bounced-collection-report');
    Route::any('/bounced_collection_report_paginate',[BouncedCollectionReportController::class, 'listPaginate'])->name('admin.bounced_collection_report_paginate');
	
	#cleared
    Route::get('/todays-maturity-report',[TodaysMaturityController::class, 'getList'])->name('admin.todays-maturity-report');
    Route::any('/todays_maturity_report_paginate',[TodaysMaturityController::class, 'listPaginate'])->name('admin.todays_maturity_report_paginate');
	
	#upcoming-maturity-overdue
    Route::get('/upcoming-maturity-overdue-report',[UpcomingOverdueMaturityController::class, 'getList'])->name('admin.upcoming-maturity-overdue-report');
    Route::any('/upcoming_maturity_overdue_paginate',[UpcomingOverdueMaturityController::class, 'listPaginate'])->name('admin.upcoming_maturity_overdue_paginate');
	
	#cleared
    Route::get('/overdue-maturity-report',[OverdueMaturityController::class, 'getList'])->name('admin.overdue-maturity-report');
    Route::any('/overdue_maturity_report_paginate',[OverdueMaturityController::class, 'listPaginate'])->name('admin.overdue_maturity_report_paginate');
	Route::any('/fetchbank2',[OverdueMaturityController::class, 'fetchbank2'])->name('admin.fetchbank2');
	Route::any('/fetchbank3',[OverdueMaturityController::class, 'fetchbank3'])->name('admin.fetchbank3');

	#bookings
    Route::get('/scheme-bookings',[SchemeBookingsController ::class, 'getList'])->name('admin.scheme-bookings');
    Route::any('/scheme_bookings_paginate',[SchemeBookingsController ::class, 'listPaginate'])->name('admin.scheme-bookings_paginate');
	Route::any('/scheme-bookings-update',[SchemeBookingsController ::class, 'editPage'])->name('admin.scheme-bookings-update');
	Route::any('/re-investment/',[SchemeBookingsController::class, 'reInvestment'])->name('admin.re-investment');	
	Route::any('/booking-dashboard/',[SchemeBookingsController::class, 'bookingDashboard'])->name('admin.booking-dashboard');
	Route::any('/maturity-dashboard/',[SchemeBookingsController::class, 'maturityDashboard'])->name('admin.maturity-dashboard');
 
	#product
    Route::get('/products',[ProductsController::class, 'getList'])->name('admin.products');
    Route::any('/products_paginate',[ProductsController::class, 'listPaginate'])->name('admin.products_paginate');
	Route::any('/edit-product/{row_id}',[ProductsController::class, 'editPage'])->name('admin.edit-product');
	Route::any('/add-product',[ProductsController::class, 'addPage'])->name('admin.add-product');
	
	#holidays
    Route::get('/holidays',[HolidaysController::class, 'getList'])->name('admin.holidays');
    Route::any('/holidays_paginate',[HolidaysController::class, 'listPaginate'])->name('admin.holidays_paginate');
	Route::any('/edit-holiday/{row_id}',[HolidaysController::class, 'editPage'])->name('admin.edit-holiday');
	Route::any('/add-holiday',[HolidaysController::class, 'addPage'])->name('admin.add-holiday');

	#modifiers
    Route::get('/admins',[AccountsController::class, 'getList'])->name('admin.admins');
    Route::any('/admin_paginate',[AccountsController::class, 'listPaginate'])->name('admin.admins_paginate');
	Route::any('/edit-admin/{row_id}',[AccountsController::class, 'editPage'])->name('admin.edit-admin');
	Route::any('/add-admin',[AccountsController::class, 'addPage'])->name('admin.add-admin');

	#modifiers
    Route::get('/branches',[BranchesController::class, 'getList'])->name('admin.branches');
    Route::any('/branches_paginate',[BranchesController::class, 'listPaginate'])->name('admin.branches_paginate');
	Route::any('/edit-branch/{row_id}',[BranchesController::class, 'editPage'])->name('admin.edit-branch');
	Route::any('/add-branch',[BranchesController::class, 'addPage'])->name('admin.add-branch');

	#modifiers
    Route::get('/branch-admin',[BranchAdminController::class, 'getList'])->name('admin.branch-admin');
    Route::any('/branch_admin_paginate',[BranchAdminController::class, 'listPaginate'])->name('admin.branch_admin_paginate');
	Route::any('/edit-branch-admin/{row_id}',[BranchAdminController::class, 'editPage'])->name('admin.edit-branch-admin');
	Route::any('/add-branch-admin',[BranchAdminController::class, 'addPage'])->name('admin.add-branch-admin');

	#modifiers
    Route::get('/ally-types',[AllyTypesController::class, 'getList'])->name('admin.ally-types');
    Route::any('/ally_types_paginate',[AllyTypesController::class, 'listPaginate'])->name('admin.ally_types_paginate');
	Route::any('/edit-ally-type/{row_id}',[AllyTypesController::class, 'editPage'])->name('admin.edit-ally-type');
	Route::any('/add-ally-type',[AllyTypesController::class, 'addPage'])->name('admin.add-ally-type');	
	
	#modifiers
    Route::get('/allys',[AllysController::class, 'getList'])->name('admin.allys');
    Route::any('/allys_paginate',[AllysController::class, 'listPaginate'])->name('admin.allys_paginate');
	Route::any('/edit-ally/{row_id}',[AllysController::class, 'editPage'])->name('admin.edit-ally');
	Route::any('/add-ally',[AllysController::class, 'addPage'])->name('admin.add-ally');	

	#investment
	Route::any('/add-investment/{row_id?}',[InvestmentsController::class, 'addPage'])->name('admin.add-investment');
	Route::any('/get-product-details',[InvestmentsController::class, 'getPage'])->name('admin.get-product-details');
			
	#export
	Route::any('/export-maturity-report',[ExportsController::class, 'exportMaturityReport'])->name('admin.export-maturity-report');
	Route::any('/export-brs-report',[ExportsController::class, 'exportBrsReport'])->name('exports.export-brs-report');

});