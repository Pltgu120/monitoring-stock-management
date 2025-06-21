<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Item;
use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use App\Models\Brand;
use App\Models\GoodsIn;
use App\Models\Category;
use App\Models\Customer;
use App\Models\GoodsOut;
use App\Models\Supplier;
use Illuminate\View\View;
use App\Models\DamagedItem;
use App\Models\ItemChemical;
use Illuminate\Http\Request;
use App\Models\ConsumableItem;
use App\Models\ItemMechanical;
use App\Models\GoodsInChemical;
use App\Models\GoodsOutChemical;
use App\Models\GoodsInMechanical;
use App\Models\GoodsOutMechanical;
use App\Models\DamagedItemMechanical;
use App\Models\ConsumableItemMechanical;

class DashboardController extends Controller
{
    // Method to display the dashboard view
    public function index(): View
    {
        $product_count = Item::count();
        $product_count_mechanical = ItemMechanical::count();
        $product_count_chemical = ItemChemical::count();
        $category_count = Category::count();
        $unit_count = Unit::count();
        $brand_count = Brand::count();
        $goodsin = GoodsIn::count();
        $goodsout = GoodsOut::count();
        $customer = Customer::count();
        $supplier = Supplier::count();

        // Fetch consumable items data
        $consumable_items_data = ConsumableItem::select('part_name', 'quantity')->get();

        $damaged_items_data = DamagedItem::select('part_name', 'quantity')->get();

        $sparepart_items_data = Item::select('part_name', 'quantity')->get();

        $activeCount = User::where('status', 1)->count();  // Active users
        $inactiveCount = User::where('status', 0)->count();  // Inactive users
        

        // Staff, consumable item, and damaged item counts
        $staffCount = User::count();
        $consumable_item = ConsumableItem::count();
        $consumable_item_mechanical = ConsumableItemMechanical::count();
        $damaged_item = DamagedItem::count();
        $damaged_item_mechanical = DamagedItemMechanical::count();

        //sum Consumable Items
        $sumConsumableItems = ConsumableItem::sum('quantity');
        $sumConsumableItemsMechanical = ConsumableItemMechanical::sum('quantity');

        //sum Damaged Items
        $sumDamagedItems = DamagedItem::sum('quantity');
        $sumDamagedItemsMechanical = DamagedItemMechanical::sum('quantity');

        //sum Item
        $sumItem = Item::sum('quantity');
        $sumItemMechanical = ItemMechanical::sum('quantity');
        $sumItemChemical = ItemChemical::sum('quantity');

        //GoodsIn sum quantity
        $goodsInSum = GoodsIn::sum('quantity');
        $goodsInSumMechanical = GoodsInMechanical::sum('quantity');
        $goodsInSumChemical = GoodsInChemical::sum('quantity');

        //GoodsOut sum quantity
        $goodsOutSum = GoodsOut::sum('quantity');
        $goodsOutSumMechanical = GoodsOutMechanical::sum('quantity');
        $goodsOutSumChemical = GoodsOutChemical::sum('quantity');

        $lowestItemsElectric = Item::orderBy('quantity', 'asc')
        ->take(3)
        ->get(['part_name', 'quantity']);

        $lowestItemsMechanical = ItemMechanical::orderBy('quantity', 'asc')
        ->take(3)
        ->get(['part_name', 'quantity']);

        return view('admin.dashboard', compact(
            'product_count',
            'category_count',
            'unit_count',
            'brand_count',
            'goodsin',
            'goodsout',
            'customer',
            'supplier',
            'staffCount', 
            'consumable_item', 
            'damaged_item',
            'consumable_items_data',
            'damaged_items_data',
            'sparepart_items_data',
            'activeCount',
            'inactiveCount',
            'sumConsumableItems',
            'sumDamagedItems',
            'sumItem',
            'goodsInSum',
            'goodsOutSum',
            'product_count_mechanical',
            'product_count_chemical',
            'consumable_item_mechanical',
            'damaged_item_mechanical',
            'sumConsumableItemsMechanical',
            'sumDamagedItemsMechanical',
            'sumItemMechanical',
            'goodsInSumMechanical',
            'goodsOutSumMechanical',
            'sumItemChemical',
            'goodsInSumChemical',
            'goodsOutSumChemical',
            'lowestItemsElectric',
            'lowestItemsMechanical'
        ));
    }
    
}
