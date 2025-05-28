<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guardName = 'web';

        // Daftar Permissions
        $permissions = [
            'manage_products', 'manage_categories', 'create_orders',
            'view_own_orders', 'view_any_orders', 'update_order_status', 'cancel_orders',
            'view_any_customers', 'edit_any_customers', 'delete_any_customers',
            'create_testimonials', 'view_any_testimonials', 'edit_own_testimonials',
            'edit_any_testimonials', 'delete_any_testimonials', 'manage_users',
            'manage_articles', 'manage_promotions', 'manage_own_shipping_addresses',
            'manage_own_cart',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => $guardName]);
        }

        // Dapatkan atau Buat Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => $guardName]);
        $customerRole = Role::firstOrCreate(['name' => 'pelanggan', 'guard_name' => $guardName]);

        // Assign Permissions ke Role Admin
        $adminRole->givePermissionTo([
            'manage_products', 'manage_categories', 'view_any_orders',
            'update_order_status', 'cancel_orders', 'view_any_customers',
            'edit_any_customers', 'delete_any_customers', 'view_any_testimonials',
            'edit_any_testimonials', 'delete_any_testimonials', 'manage_users',
            'manage_articles', 'manage_promotions',
        ]);

        // Assign Permissions ke Role Pelanggan
        $customerRole->givePermissionTo([
            'create_orders', 'view_own_orders', 'create_testimonials',
            'edit_own_testimonials', 'manage_own_shipping_addresses',
            'manage_own_cart',
        ]);
    }
}
