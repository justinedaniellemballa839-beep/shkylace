<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Market;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'Shkyplace',
            'email' => 'admin@shkyplace.com',
            'phone' => '690000001',
            'password' => Hash::make('password123'),
            'pin_hash' => Hash::make('1234'),
            'role' => 'administrateur',
            'ville' => 'Douala',
            'quartier' => 'Akwa',
            'is_verified' => true,
        ]);

        $vendeur = User::create([
            'first_name' => 'Marie',
            'last_name' => 'Nkodo',
            'email' => 'vendeur@shkyplace.com',
            'phone' => '690000002',
            'password' => Hash::make('password123'),
            'pin_hash' => Hash::make('1234'),
            'role' => 'vendeur',
            'ville' => 'Douala',
            'quartier' => 'Bonamoussadi',
            'is_verified' => true,
        ]);

        User::create([
            'first_name' => 'Jean',
            'last_name' => 'Mbarga',
            'email' => 'client@shkyplace.com',
            'phone' => '690000003',
            'password' => Hash::make('password123'),
            'pin_hash' => Hash::make('1234'),
            'role' => 'client',
            'ville' => 'Douala',
            'quartier' => 'Deido',
        ]);

        User::create([
            'first_name' => 'Paul',
            'last_name' => 'Essomba',
            'email' => 'livreur@shkyplace.com',
            'phone' => '690000004',
            'password' => Hash::make('password123'),
            'role' => 'livreur',
            'ville' => 'Douala',
            'quartier' => 'Makepe',
            'latitude' => 4.0511,
            'longitude' => 9.7679,
        ]);

        $categories = [
            ['name' => 'Aliments comestibles', 'slug' => 'aliments', 'icon' => 'bi-basket'],
            ['name' => 'Fruits & Légumes', 'slug' => 'fruits-legumes', 'icon' => 'bi-apple'],
            ['name' => 'Chambres & Studios', 'slug' => 'logement', 'icon' => 'bi-house'],
            ['name' => 'Voitures', 'slug' => 'voitures', 'icon' => 'bi-car-front'],
            ['name' => 'Repas', 'slug' => 'repas', 'icon' => 'bi-cup-hot'],
            ['name' => 'Sacs de riz', 'slug' => 'riz', 'icon' => 'bi-bag'],
            ['name' => 'Mode & Sacs', 'slug' => 'mode', 'icon' => 'bi-handbag'],
            ['name' => 'Chaussures', 'slug' => 'chaussures', 'icon' => 'bi-boot'],
            ['name' => 'Vêtements de marque', 'slug' => 'vetements', 'icon' => 'bi-hand-index'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        $markets = [
            ['name' => 'Marché Central Douala', 'quartier' => 'Akwa', 'ville' => 'Douala', 'latitude' => 4.0483, 'longitude' => 9.7043],
            ['name' => 'Marché Congo', 'quartier' => 'Deido', 'ville' => 'Douala', 'latitude' => 4.0601, 'longitude' => 9.7123],
            ['name' => 'Marché Mokolo', 'quartier' => 'Yaoundé', 'ville' => 'Yaoundé', 'latitude' => 3.8667, 'longitude' => 11.5167],
        ];

        foreach ($markets as $m) {
            Market::create(array_merge($m, [
                'description' => 'Marché local animé avec de nombreux vendeurs.',
                'is_active' => true,
            ]));
        }

        $shop = Shop::create([
            'user_id' => $vendeur->id,
            'market_id' => 1,
            'name' => 'Boutique Marie - Produits Frais',
            'description' => 'Fruits, légumes et produits alimentaires frais du marché.',
            'address' => 'Rue du Marché Central',
            'quartier' => 'Akwa',
            'ville' => 'Douala',
            'latitude' => 4.0485,
            'longitude' => 9.7045,
            'phone' => '690000002',
            'status' => 'approved',
            'rating' => 4.8,
        ]);

        $products = [
            ['name' => 'Tomates fraîches', 'description' => 'Tomates rouges du marché, 1kg', 'category_id' => 2, 'price' => 500, 'quantity' => 100, 'unit' => 'kg'],
            ['name' => 'Oignons', 'description' => 'Oignons locaux, sac de 5kg', 'category_id' => 2, 'price' => 2500, 'quantity' => 50, 'unit' => 'sac'],
            ['name' => 'Ignames', 'description' => 'Ignames fraîches du village', 'category_id' => 2, 'price' => 800, 'quantity' => 80, 'unit' => 'kg'],
            ['name' => 'Choux', 'description' => 'Choux verts croquants', 'category_id' => 2, 'price' => 300, 'quantity' => 60, 'unit' => 'pièce'],
            ['name' => 'Patates douces', 'description' => 'Patates douces locales', 'category_id' => 2, 'price' => 400, 'quantity' => 70, 'unit' => 'kg'],
            ['name' => 'Sac de riz 25kg', 'description' => 'Riz parfumé importé', 'category_id' => 6, 'price' => 15000, 'quantity' => 30, 'unit' => 'sac'],
            ['name' => 'Repas complet', 'description' => 'Riz, sauce, viande et légumes', 'category_id' => 5, 'price' => 1500, 'quantity' => 20, 'unit' => 'plat'],
            ['name' => 'Condiments verts', 'description' => 'Persil, céleri, basilic frais', 'category_id' => 1, 'price' => 200, 'quantity' => 40, 'unit' => 'botte'],
        ];

        foreach ($products as $p) {
            Product::create(array_merge($p, ['shop_id' => $shop->id, 'is_available' => true]));
        }
    }
}
