<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AdminatorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WebSettingController;
use App\Http\Controllers\DamagedItemController;
use App\Http\Controllers\ReportStockController;
use App\Http\Controllers\ItemChemicalController;
use App\Http\Controllers\ReportGoodsInController;
use App\Http\Controllers\TransactionInController;
use App\Http\Controllers\ConsumableItemController;
use App\Http\Controllers\ItemMechanicalController;
use App\Http\Controllers\ReportGoodsOutController;
use App\Http\Controllers\TransactionOutController;
use App\Http\Controllers\GoodsInChemicalController;
use App\Http\Controllers\ReportFinancialController;
use App\Http\Controllers\GoodsOutChemicalController;
use App\Http\Controllers\GoodsInMechanicalController;
use App\Http\Controllers\GoodsOutMechanicalController;
use App\Http\Controllers\ReportStockChemicalController;
use App\Http\Controllers\DamagedItemMechanicalController;
use App\Http\Controllers\ReportGoodsInChemicalController;
use App\Http\Controllers\ReportStockMechanicalController;
use App\Http\Controllers\ReportGoodsOutChemicalController;
use App\Http\Controllers\ReportGoodsInMechanicalController;
use App\Http\Controllers\ConsumableItemMechanicalController;
use App\Http\Controllers\ReportGoodsOutMechanicalController;

Route::middleware(["localization"])-> group(function(){
    Route::get('/',[LoginController::class,'index'])->name('login');
    Route::post('/',[LoginController::class,'auth'])->name('login.auth');
});

Route::middleware(['auth', "localization"])-> group(function(){
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
    //getStockChart
    // Route::get('/getStockChart',[DashboardController::class,'getStockChart'])->name('getStockChart');

    // barang
    Route::controller(ItemController::class)->prefix("gudang-har-elektrik/barang")->group(function(){
        Route::get('/','index')->name('barang');
        Route::post('/kode','detailByCode')->name('barang.part_number');
        Route::get('/daftar-barang','list')->name('barang.list');

        Route::middleware(['employee.middleware'])->group(function(){
            Route::post('/simpan','save')->name('barang.save');
            Route::post('/info','detail')->name('barang.detail');
            Route::delete('/hapus','delete')->name('barang.delete');

            // Route untuk edit (GET)
            Route::get('/{id}/edit', 'edit')->name('barang.edit');

            // Route untuk update (PUT) dengan format /update/{id}
            Route::put('/update/{id}', 'update')->name('barang.update');

            //delete by id
            Route::delete('/delete/{id}', 'destroy')->name('barang.destroy');

            //show by id
            Route::get('/show/{id}', 'show')->name('barang.show');

            //export
            Route::get('/export', 'export')->name('barang.export');

            //templateExport
            Route::get('/template', 'templateExport')->name('barang.template');

            //import
            Route::post('/import', 'import')->name('barang.import');
        });
    });

    //barang-rusak DamagedItemController
    Route::controller(DamagedItemController::class)->prefix("gudang-har-elektrik/barang-rusak")->group(function(){
        Route::get('/','index')->name('barang-rusak');
        Route::get('/daftar-barang-rusak','list')->name('barang-rusak.list');

        Route::middleware(['employee.middleware'])->group(function(){
            Route::post('/simpan','store')->name('barang-rusak.save');
            Route::post('/info','detail')->name('barang-rusak.detail');
            Route::delete('/hapus','delete')->name('barang-rusak.delete');

            // Route untuk edit (GET)
            Route::get('/{id}/edit', 'edit')->name('barang-rusak.edit');

            // Route untuk update (PUT) dengan format /update/{id}
            Route::put('/update/{id}', 'update')->name('barang-rusak.update');

            //delete by id
            Route::delete('/delete/{id}', 'destroy')->name('barang-rusak.destroy');

            //show by id
            Route::get('/show/{id}', 'show')->name('barang-rusak.show');

            //export
            Route::get('/export', 'export')->name('barang-rusak.export');

        });
    });

    //barang-bekas-pakai ConsumableItemController
    Route::controller(ConsumableItemController::class)->prefix("gudang-har-elektrik/barang-bekas-pakai")->group(function(){
        Route::get('/','index')->name('barang-bekas-pakai');
        Route::get('/daftar-barang-bekas-pakai','list')->name('barang-bekas-pakai.list');

        Route::middleware(['employee.middleware'])->group(function(){
            Route::post('/simpan','store')->name('barang-bekas-pakai.save');
            Route::post('/info','detail')->name('barang-bekas-pakai.detail');
            Route::delete('/hapus','delete')->name('barang-bekas-pakai.delete');

            // Route untuk edit (GET)
            Route::get('/{id}/edit', 'edit')->name('barang-bekas-pakai.edit');

            // Route untuk update (PUT) dengan format /update/{id}
            Route::put('/update/{id}', 'update')->name('barang-bekas-pakai.update');

            //delete by id
            Route::delete('/delete/{id}', 'destroy')->name('barang-bekas-pakai.destroy');

            //show by id
            Route::get('/show/{id}', 'show')->name('barang-bekas-pakai.show');

            //export
            Route::get('/export', 'export')->name('barang-bekas-pakai.export');

        });
    });

    //barang-mekanik ItemMechanicalController
    Route::controller(ItemMechanicalController::class)->prefix("gudang-har-mekanik/barang-mekanik")->group(function(){
        Route::get('/','index')->name('barang-mekanik');
        Route::get('/daftar-barang-mekanik','list')->name('barang-mekanik.list');
        //barang.part_number
        Route::post('/kode','detailByCode')->name('barang-mekanik.part_number');

        Route::middleware(['employee.middleware'])->group(function(){
            Route::post('/simpan','store')->name('barang-mekanik.save');
            Route::post('/info','detail')->name('barang-mekanik.detail');
            Route::delete('/hapus','delete')->name('barang-mekanik.delete');

            // Route untuk edit (GET)
            Route::get('/{id}/edit', 'edit')->name('barang-mekanik.edit');

            // Route untuk update (PUT) dengan format /update/{id}
            Route::put('/update/{id}', 'update')->name('barang-mekanik.update');

            //delete by id
            Route::delete('/delete/{id}', 'destroy')->name('barang-mekanik.destroy');

            //show by id
            Route::get('/show/{id}', 'show')->name('barang-mekanik.show');

            //import
            Route::post('/import', 'import')->name('barang-mekanik.import');

            //export
            Route::get('/export', 'templateExport')->name('barang-mekanik.template');

        });
    });

    //barang-kimia
    Route::controller(ItemChemicalController::class)->prefix("gudang-kimia/barang-kimia")->group(function(){
        Route::get('/','index')->name('barang-kimia');
        Route::get('/daftar-barang-kimia','list')->name('barang-kimia.list');
        //barang-kimia.part_number
        Route::post('/kode','detailByCode')->name('barang-kimia.part_number');


        Route::middleware(['employee.middleware'])->group(function(){
            Route::post('/simpan','store')->name('barang-kimia.save');
            Route::post('/info','detail')->name('barang-kimia.detail');
            Route::delete('/hapus','delete')->name('barang-kimia.delete');

            // Route untuk edit (GET)
            Route::get('/{id}/edit', 'edit')->name('barang-kimia.edit');

            // Route untuk update (PUT) dengan format /update/{id}
            Route::put('/update/{id}', 'update')->name('barang-kimia.update');

            //delete by id
            Route::delete('/delete/{id}', 'destroy')->name('barang-kimia.destroy');

            //show by id
            Route::get('/show/{id}', 'show')->name('barang-kimia.show');

            //import
            Route::post('/import', 'import')->name('barang-kimia.import');

            //export
            Route::get('/export', 'templateExport')->name('barang-kimia.template');
        });
    });

    //barang-rusak-mekanik DamagedItemMechanicalController
    Route::controller(DamagedItemMechanicalController::class)->prefix("gudang-har-mekanik/barang-rusak")->group(function(){
        Route::get('/','index')->name('barang-rusak-mekanik');
        Route::get('/daftar-barang-rusak-mekanik','list')->name('barang-rusak-mekanik.list');

        Route::middleware(['employee.middleware'])->group(function(){
            Route::post('/simpan','store')->name('barang-rusak-mekanik.save');
            Route::post('/info','detail')->name('barang-rusak-mekanik.detail');
            Route::delete('/hapus','delete')->name('barang-rusak-mekanik.delete');

            // Route untuk edit (GET)
            Route::get('/{id}/edit', 'edit')->name('barang-rusak-mekanik.edit');

            // Route untuk update (PUT) dengan format /update/{id}
            Route::put('/update/{id}', 'update')->name('barang-rusak-mekanik.update');

            //delete by id
            Route::delete('/delete/{id}', 'destroy')->name('barang-rusak-mekanik.destroy');

            //show by id
            Route::get('/show/{id}', 'show')->name('barang-rusak-mekanik.show');

            //export
            Route::get('/export', 'export')->name('barang-rusak-mekanik.export');

        });
    });

    //barang-bekas-pakai-mekanik ConsumableItemMechanicalController
    Route::controller(ConsumableItemMechanicalController::class)->prefix("gudang-har-mekanik/barang-bekas-pakai")->group(function(){
        Route::get('/','index')->name('barang-bekas-pakai-mekanik');
        Route::get('/daftar-barang-bekas-pakai-mekanik','list')->name('barang-bekas-pakai-mekanik.list');

        Route::middleware(['employee.middleware'])->group(function(){
            Route::post('/simpan','store')->name('barang-bekas-pakai-mekanik.save');
            Route::post('/info','detail')->name('barang-bekas-pakai-mekanik.detail');
            Route::delete('/hapus','delete')->name('barang-bekas-pakai-mekanik.delete');

            // Route untuk edit (GET)
            Route::get('/{id}/edit', 'edit')->name('barang-bekas-pakai-mekanik.edit');

            // Route untuk update (PUT) dengan format /update/{id}
            Route::put('/update/{id}', 'update')->name('barang-bekas-pakai-mekanik.update');

            //delete by id
            Route::delete('/delete/{id}', 'destroy')->name('barang-bekas-pakai-mekanik.destroy');

            //show by id
            Route::get('/show/{id}', 'show')->name('barang-bekas-pakai-mekanik.show');

            //export
            Route::get('/export', 'export')->name('barang-bekas-pakai-mekanik.export');

        });
    });

    

    // jenis barang
    Route::controller(CategoryController::class)->prefix("barang/jenis")->group(function(){
        Route::get('/','index')->name('barang.jenis');
        Route::get('/daftar','list')->name('barang.jenis.list');
        Route::middleware(['employee.middleware'])->group(function(){
            Route::post('/simpan','save')->name('barang.jenis.save');
            Route::post('/info','detail')->name('barang.jenis.detail');
            Route::put('/ubah','update')->name('barang.jenis.update');
            Route::delete('/hapus','delete')->name('barang.jenis.delete');
        });
    });




    // satuan barang
    Route::controller(UnitController::class)->prefix('/barang/satuan')->group(function(){
        Route::get('/','index')->name('barang.satuan');
        Route::get('/daftar','list')->name('barang.satuan.list');
        Route::middleware(['employee.middleware'])->group(function(){
            Route::post('/simpan','save')->name('barang.satuan.save');
            Route::post('/info','detail')->name('barang.satuan.detail');
            Route::put('/ubah','update')->name('barang.satuan.update');
            Route::delete('/hapus','delete')->name('barang.satuan.delete');
        });
    });



    // merk barang
    Route::controller(BrandController::class)->prefix("/barang/merk")->group(function(){
        Route::get('/','index')->name('barang.merk');
        Route::get('/daftar','list')->name('barang.merk.list');
        Route::middleware(['employee.middleware'])->group(function(){
            Route::post('/simpan','save')->name('barang.merk.save');
            Route::post('/info','detail')->name('barang.merk.detail');
            Route::put('/ubah','update')->name('barang.merk.update');
            Route::delete('/hapus','delete')->name('barang.merk.delete');
        });
    });


    // customer (izin untuk staff hanya read)
    Route::controller(CustomerController::class)->prefix('/customer')->group(function(){
        Route::get('/','index')->name('customer');
        Route::get('/daftar','list')->name('customer.list');
        Route::middleware(['employee.middleware'])->group(function(){
            Route::post('/simpan','save')->name('customer.save');
            Route::post('/info','detail')->name('customer.detail');
            Route::put('/ubah','update')->name('customer.update');
            Route::delete('/hapus','delete')->name('customer.delete');
        });
    });


    // supplier (izin untuk staff hanya read)
    Route::controller(SupplierController::class)->prefix('/supplier')->group(function(){
        Route::get('/','index')->name('supplier');
        Route::get('/daftar','list')->name('supplier.list');
        Route::middleware(['employee.middleware'])->group(function(){
            Route::post('/simpan','save')->name('supplier.save');
            Route::post('/info','detail')->name('supplier.detail');
            Route::put('/ubah','update')->name('supplier.update');
            Route::delete('/hapus','delete')->name('supplier.delete');
        });
    });

    // Transaksi  masuk
    Route::controller(TransactionInController::class)->prefix('gudang-har-elektrik/transaksi/masuk')->group(function(){
        Route::get('/','index')->name('transaksi.masuk');
        Route::get('/list','list')->name('transaksi.masuk.list');
        Route::post('/save','save')->name('transaksi.masuk.save');
        Route::post('/detail','detail')->name('transaksi.masuk.detail');
        Route::post('/update','update')->name('transaksi.masuk.update');
        Route::delete('/delete','delete')->name('transaksi.masuk.delete');
        Route::get('/barang/list/in','listIn')->name('barang.list.in');
        
    });

    //gudang-har-mekanik/transaksi/masuk GoodsInMechanicalController
    Route::controller(GoodsInMechanicalController::class)->prefix('gudang-har-mekanik/transaksi/masuk')->group(function(){
        Route::get('/','index')->name('transaksi.masuk.mekanik');
        Route::get('/list','list')->name('transaksi.masuk.mekanik.list');
        Route::post('/save','save')->name('transaksi.masuk.mekanik.save');
        Route::post('/detail','detail')->name('transaksi.masuk.mekanik.detail');
        Route::post('/update','update')->name('transaksi.masuk.mekanik.update');
        Route::delete('/delete','delete')->name('transaksi.masuk.mekanik.delete');
        Route::get('/barang/list/in','listIn')->name('barang.transaksi.masuk.list.in');

    });

    //gudang-har-mekanik/transaksi/keluar GoodsOutMechanicalController
    Route::controller(GoodsOutMechanicalController::class)->prefix('gudang-har-mekanik/transaksi/keluar')->group(function(){
        Route::get('/','index')->name('transaksi.keluar.mekanik');
        Route::get('/list','list')->name('transaksi.keluar.mekanik.list');
        Route::post('/save','save')->name('transaksi.keluar.mekanik.save');
        Route::post('/detail','detail')->name('transaksi.keluar.mekanik.detail');
        Route::post('/update','update')->name('transaksi.keluar.mekanik.update');
        Route::delete('/delete','delete')->name('transaksi.keluar.mekanik.delete');
        Route::get('/barang/list/out','list')->name('barang.transaksi.keluar.list.out');
    });

    //gudang-kimia/transaksi/keluar GoodsOutChemicalController
    Route::controller(GoodsOutChemicalController::class)->prefix('gudang-kimia/transaksi/keluar')->group(function(){
        Route::get('/','index')->name('transaksi.keluar.kimia');
        Route::get('/list','list')->name('transaksi.keluar.kimia.list');
        Route::post('/save','save')->name('transaksi.keluar.kimia.save');
        Route::post('/detail','detail')->name('transaksi.keluar.kimia.detail');
        Route::post('/update','update')->name('transaksi.keluar.kimia.update');
        Route::delete('/delete','delete')->name('transaksi.keluar.kimia.delete');
        Route::get('/barang/list/out','list')->name('barang.transaksi.keluar.kimia.list.out');
    });


    //gudang-kimia/transaksi/masuk GoodsInChemicalController
    Route::controller(GoodsInChemicalController::class)->prefix('gudang-kimia/transaksi/masuk')->group(function(){
        Route::get('/','index')->name('transaksi.masuk.kimia');
        Route::get('/list','list')->name('transaksi.masuk.kimia.list');
        Route::post('/save','save')->name('transaksi.masuk.kimia.save');
        Route::post('/detail','detail')->name('transaksi.masuk.kimia.detail');
        Route::post('/update','update')->name('transaksi.masuk.kimia.update');
        Route::delete('/delete','delete')->name('transaksi.masuk.kimia.delete');
        Route::get('/barang/list/in','listIn')->name('barang.transaksi.masuk.kimia.list.in');
    });




    // Transaksi keluar
    Route::controller(TransactionOutController::class)->prefix('gudang-har-elektrik/transaksi/keluar')->group(function(){
        Route::get('/','index')->name('transaksi.keluar');
        Route::get('/list','list')->name('transaksi.keluar.list');
        Route::post('/simpan','save')->name('transaksi.keluar.save');
        Route::post('/info','detail')->name('transaksi.keluar.detail');
        Route::post('/ubah','update')->name('transaksi.keluar.update');
        Route::delete('/hapus','delete')->name('transaksi.keluar.delete');
    });

    // laporan barang masuk
    Route::controller(ReportGoodsInController::class)->prefix('/laporan-elektrik/masuk')->group(function(){
        Route::get('/','index')->name('laporan.masuk');
        Route::get('/list','list')->name('laporan.masuk.list');
    });

    // laporan barang masuk ReportGoodsInMechanicalController
    Route::controller(ReportGoodsInMechanicalController::class)->prefix('/laporan-mekanik/masuk')->group(function(){
        Route::get('/','index')->name('laporan.masuk.mekanik');
        Route::get('/list','list')->name('laporan.masuk.mekanik.list');
    });

    // laporan barang masuk ReportGoodsInChemicalController
    Route::controller(ReportGoodsInChemicalController::class)->prefix('/laporan-kimia/masuk')->group(function(){
        Route::get('/','index')->name('laporan.masuk.kimia');
        Route::get('/list','list')->name('laporan.masuk.kimia.list');
    });


    // laporan barang keluar
    Route::controller(ReportGoodsOutController::class)->prefix('/laporan-elektrik/keluar')->group(function(){
        Route::get('/','index')->name('laporan.keluar');
        Route::get('/list','list')->name('laporan.keluar.list');
    });

    // laporan barang keluar ReportGoodsOutMechanicalController
    Route::controller(ReportGoodsOutMechanicalController::class)->prefix('/laporan-mekanik/keluar')->group(function(){
        Route::get('/','index')->name('laporan.keluar.mekanik');
        Route::get('/list','list')->name('laporan.keluar.mekanik.list');
    });

    // laporan barang keluar ReportGoodsOutChemicalController
    Route::controller(ReportGoodsOutChemicalController::class)->prefix('/laporan-kimia/keluar')->group(function(){
        Route::get('/','index')->name('laporan.keluar.kimia');
        Route::get('/list','list')->name('laporan.keluar.kimia.list');
    });

    // laporan stok barang
    Route::controller(ReportStockController::class)->prefix('/laporan-elektrik/stok')->group(function(){
        Route::get('/','index')->name('laporan.stok');
        Route::get('/list','list')->name('laporan.stok.list');
    });

    // laporan stok barang ReportStockMechanicalController
    Route::controller(ReportStockMechanicalController::class)->prefix('/laporan-mekanik/stok')->group(function(){
        Route::get('/','index')->name('laporan.stok.mekanik');
        Route::get('/list','list')->name('laporan.stok.mekanik.list');
    });

    // laporan stok barang ReportStockChemicalController
    Route::controller(ReportStockChemicalController::class)->prefix('/laporan-kimia/stok')->group(function(){
        Route::get('/','index')->name('laporan.stok.kimia');
        Route::get('/list','list')->name('laporan.stok.kimia.list');
    });
    

    // laporan penghasilan
    Route::get('/report/income',[ReportFinancialController::class,'income'])->name('laporan.pendapatan');

    // pengaturan pengguna
    Route::middleware(['employee.middleware'])->group(function(){
        Route::controller(EmployeeController::class)->prefix('/settings/employee')->group(function(){
            Route::get('/','index')->name('settings.employee');
            Route::get('/list','list')->name('settings.employee.list');
            Route::post('/save','save')->name('settings.employee.save');
            Route::post('/detail','detail')->name('settings.employee.detail');
            Route::put('/update','update')->name('settings.employee.update');
            Route::delete('/delete','delete')->name('settings.employee.delete');
            //destory by id
            Route::delete('/delete/{id}', 'destroy')->name('settings.employee.destroy');
        });
    });

    // Route::get('/pengaturan/web',[WebSettingController::class,'index'])->name('settings.web');
    // Route::get('/pengaturan/web/detail',[WebSettingController::class,'detail'])->name('settings.web.detail');
    // Route::post('/pengaturan/web/detail/role',[WebSettingController::class,'detailRole'])->name('settings.web.detail.role');
    // Route::put('/pengaturan/web/update',[WebSettingController::class,'update'])->name('settings.web.update');

    // pengaturan profile
    Route::get('/settings/profile',[ProfileController::class,'index'])->name('settings.profile');
    Route::post('/settings/profile',[ProfileController::class,'update'])->name('settings.profile.update');

    // logout
    Route::get('/logout',[LoginController::class,'logout'])->name('login.delete');
});
