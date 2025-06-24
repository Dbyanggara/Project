<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kantin;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;

class OrderTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada seller dan kantin
        $seller = User::where('email', 'seller@example.com')->first();
        if (!$seller) {
            $seller = User::factory()->create([
                'name' => 'Test Seller',
                'email' => 'seller@example.com',
                'password' => bcrypt('password'),
            ]);
            $seller->assignRole('seller');
        }

        $kantin = Kantin::where('user_id', $seller->id)->first();
        if (!$kantin) {
            $kantin = Kantin::create([
                'name' => 'Kantin Test',
                'location' => 'Gedung A Lantai 1',
                'user_id' => $seller->id,
                'description' => 'Kantin untuk testing laporan penjualan',
                'operating_hours' => '08:00-17:00',
                'is_open' => true,
                'phone' => '08123456789',
                'email' => 'kantin@example.com',
                'address' => 'Jl. Test No. 123'
            ]);
        }

        // Buat menu jika belum ada
        $menus = [];
        $menuNames = ['Nasi Goreng', 'Mie Goreng', 'Ayam Goreng', 'Es Teh', 'Kopi'];
        $menuPrices = [15000, 12000, 18000, 5000, 8000];

        for ($i = 0; $i < count($menuNames); $i++) {
            $menu = Menu::where('name', $menuNames[$i])->where('kantin_id', $kantin->id)->first();
            if (!$menu) {
                $menu = Menu::create([
                    'name' => $menuNames[$i],
                    'description' => 'Menu ' . $menuNames[$i],
                    'price' => $menuPrices[$i],
                    'stock' => 100,
                    'kantin_id' => $kantin->id,
                ]);
            }
            $menus[] = $menu;
        }

        // Pastikan ada user untuk order
        $user = User::where('email', 'user@example.com')->first();
        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Test User',
                'email' => 'user@example.com',
                'password' => bcrypt('password'),
            ]);
            $user->assignRole('user');
        }

        // Hapus order lama untuk testing
        Order::where('user_id', $user->id)->delete();

        // Buat order untuk berbagai periode waktu
        $this->createOrdersForPeriod($user, $menus, 'today');
        $this->createOrdersForPeriod($user, $menus, 'week');
        $this->createOrdersForPeriod($user, $menus, 'month');
    }

    private function createOrdersForPeriod($user, $menus, $period)
    {
        $startDate = null;
        $endDate = Carbon::now();

        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                $numOrders = rand(3, 8);
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $numOrders = rand(10, 25);
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $numOrders = rand(30, 80);
                break;
        }

        for ($i = 0; $i < $numOrders; $i++) {
            $orderDate = Carbon::createFromTimestamp(rand($startDate->timestamp, $endDate->timestamp));

            $order = Order::create([
                'user_id' => $user->id,
                'status' => $this->getRandomStatus(),
                'total' => 0, // Akan diupdate setelah order items
                'shipping_address' => [
                    'address' => 'Jl. Test No. 123',
                    'city' => 'Jakarta',
                    'postal_code' => '12345'
                ],
                'payment_method' => 'cash',
                'notes' => 'Order test untuk laporan',
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);

            // Buat order items
            $total = 0;
            $numItems = rand(1, 3);
            $selectedMenus = array_rand($menus, min($numItems, count($menus)));
            if (!is_array($selectedMenus)) {
                $selectedMenus = [$selectedMenus];
            }

            foreach ($selectedMenus as $menuIndex) {
                $menu = $menus[$menuIndex];
                $quantity = rand(1, 3);
                $price = $menu->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);

                $total += $price * $quantity;
            }

            // Update total order
            $order->update(['total' => $total]);
        }
    }

    private function getRandomStatus()
    {
        $statuses = ['pending', 'processing', 'completed', 'paid', 'shipped', 'delivered'];
        return $statuses[array_rand($statuses)];
    }
}
