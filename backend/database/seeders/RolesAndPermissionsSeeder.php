<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Company permissions
            ['name' => 'companies.create', 'display_name' => 'Create Companies', 'resource' => 'companies', 'action' => 'create'],
            ['name' => 'companies.read', 'display_name' => 'View Companies', 'resource' => 'companies', 'action' => 'read'],
            ['name' => 'companies.update', 'display_name' => 'Edit Companies', 'resource' => 'companies', 'action' => 'update'],
            ['name' => 'companies.delete', 'display_name' => 'Delete Companies', 'resource' => 'companies', 'action' => 'delete'],

            // Service permissions
            ['name' => 'services.create', 'display_name' => 'Create Services', 'resource' => 'services', 'action' => 'create'],
            ['name' => 'services.read', 'display_name' => 'View Services', 'resource' => 'services', 'action' => 'read'],
            ['name' => 'services.update', 'display_name' => 'Edit Services', 'resource' => 'services', 'action' => 'update'],
            ['name' => 'services.delete', 'display_name' => 'Delete Services', 'resource' => 'services', 'action' => 'delete'],

            // Project permissions
            ['name' => 'projects.create', 'display_name' => 'Create Projects', 'resource' => 'projects', 'action' => 'create'],
            ['name' => 'projects.read', 'display_name' => 'View Projects', 'resource' => 'projects', 'action' => 'read'],
            ['name' => 'projects.update', 'display_name' => 'Edit Projects', 'resource' => 'projects', 'action' => 'update'],
            ['name' => 'projects.delete', 'display_name' => 'Delete Projects', 'resource' => 'projects', 'action' => 'delete'],

            // Partner permissions
            ['name' => 'partners.create', 'display_name' => 'Create Partners', 'resource' => 'partners', 'action' => 'create'],
            ['name' => 'partners.read', 'display_name' => 'View Partners', 'resource' => 'partners', 'action' => 'read'],
            ['name' => 'partners.update', 'display_name' => 'Edit Partners', 'resource' => 'partners', 'action' => 'update'],
            ['name' => 'partners.delete', 'display_name' => 'Delete Partners', 'resource' => 'partners', 'action' => 'delete'],

            // Testimonial permissions
            ['name' => 'testimonials.create', 'display_name' => 'Create Testimonials', 'resource' => 'testimonials', 'action' => 'create'],
            ['name' => 'testimonials.read', 'display_name' => 'View Testimonials', 'resource' => 'testimonials', 'action' => 'read'],
            ['name' => 'testimonials.update', 'display_name' => 'Edit Testimonials', 'resource' => 'testimonials', 'action' => 'update'],
            ['name' => 'testimonials.delete', 'display_name' => 'Delete Testimonials', 'resource' => 'testimonials', 'action' => 'delete'],
            ['name' => 'testimonials.approve', 'display_name' => 'Approve Testimonials', 'resource' => 'testimonials', 'action' => 'approve'],

            // Page permissions
            ['name' => 'pages.create', 'display_name' => 'Create Pages', 'resource' => 'pages', 'action' => 'create'],
            ['name' => 'pages.read', 'display_name' => 'View Pages', 'resource' => 'pages', 'action' => 'read'],
            ['name' => 'pages.update', 'display_name' => 'Edit Pages', 'resource' => 'pages', 'action' => 'update'],
            ['name' => 'pages.delete', 'display_name' => 'Delete Pages', 'resource' => 'pages', 'action' => 'delete'],
            ['name' => 'pages.publish', 'display_name' => 'Publish Pages', 'resource' => 'pages', 'action' => 'publish'],

            // Contact submission permissions
            ['name' => 'contact_submissions.read', 'display_name' => 'View Contact Submissions', 'resource' => 'contact_submissions', 'action' => 'read'],
            ['name' => 'contact_submissions.update', 'display_name' => 'Update Contact Submissions', 'resource' => 'contact_submissions', 'action' => 'update'],
            ['name' => 'contact_submissions.delete', 'display_name' => 'Delete Contact Submissions', 'resource' => 'contact_submissions', 'action' => 'delete'],

            // User management permissions
            ['name' => 'users.create', 'display_name' => 'Create Users', 'resource' => 'users', 'action' => 'create'],
            ['name' => 'users.read', 'display_name' => 'View Users', 'resource' => 'users', 'action' => 'read'],
            ['name' => 'users.update', 'display_name' => 'Edit Users', 'resource' => 'users', 'action' => 'update'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'resource' => 'users', 'action' => 'delete'],

            // Role and permission management
            ['name' => 'roles.manage', 'display_name' => 'Manage Roles', 'resource' => 'roles', 'action' => 'manage'],
            ['name' => 'permissions.manage', 'display_name' => 'Manage Permissions', 'resource' => 'permissions', 'action' => 'manage'],
            
            // Audit Logs
            ['name' => 'audit_logs.read', 'display_name' => 'Read Audit Logs', 'resource' => 'audit_logs', 'action' => 'read'],
            ['name' => 'audit_logs.cleanup', 'display_name' => 'Cleanup Audit Logs', 'resource' => 'audit_logs', 'action' => 'cleanup'],
            
            // Stats
            ['name' => 'stats.create', 'display_name' => 'Create Stats', 'resource' => 'stats', 'action' => 'create'],
            ['name' => 'stats.read', 'display_name' => 'Read Stats', 'resource' => 'stats', 'action' => 'read'],
            ['name' => 'stats.update', 'display_name' => 'Update Stats', 'resource' => 'stats', 'action' => 'update'],
            ['name' => 'stats.delete', 'display_name' => 'Delete Stats', 'resource' => 'stats', 'action' => 'delete'],
            
            // Designs
            ['name' => 'designs.create', 'display_name' => 'Create Designs', 'resource' => 'designs', 'action' => 'create'],
            ['name' => 'designs.read', 'display_name' => 'Read Designs', 'resource' => 'designs', 'action' => 'read'],
            ['name' => 'designs.update', 'display_name' => 'Update Designs', 'resource' => 'designs', 'action' => 'update'],
            ['name' => 'designs.delete', 'display_name' => 'Delete Designs', 'resource' => 'designs', 'action' => 'delete'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Administrator',
                'description' => 'Full access to all system features and settings'
            ]
        );

        $contentManagerRole = Role::firstOrCreate(
            ['name' => 'content_manager'],
            [
                'display_name' => 'Content Manager',
                'description' => 'Can manage all content but not user accounts or system settings'
            ]
        );

        $editorRole = Role::firstOrCreate(
            ['name' => 'editor'],
            [
                'display_name' => 'Editor',
                'description' => 'Can create and edit content but cannot delete or publish'
            ]
        );

        $viewerRole = Role::firstOrCreate(
            ['name' => 'viewer'],
            [
                'display_name' => 'Viewer',
                'description' => 'Read-only access to content'
            ]
        );

        // Assign permissions to roles
        
        // Admin gets all permissions
        $adminRole->permissions()->sync(Permission::all()->pluck('id'));

        // Content Manager gets content-related permissions
        $contentManagerPermissions = Permission::whereIn('resource', [
            'companies', 'services', 'projects', 'partners', 'testimonials', 'pages', 'contact_submissions', 'stats'
        ])->pluck('id');
        $contentManagerRole->permissions()->sync($contentManagerPermissions);

        // Editor gets create, read, update permissions (no delete, no publish, no approve)
        $editorPermissions = Permission::whereIn('action', ['create', 'read', 'update'])
            ->whereIn('resource', ['companies', 'services', 'projects', 'partners', 'testimonials', 'pages', 'stats'])
            ->pluck('id');
        $editorRole->permissions()->sync($editorPermissions);

        // Viewer gets only read permissions
        $viewerPermissions = Permission::where('action', 'read')
            ->whereIn('resource', ['companies', 'services', 'projects', 'partners', 'testimonials', 'pages', 'contact_submissions', 'stats'])
            ->pluck('id');
        $viewerRole->permissions()->sync($viewerPermissions);

        // Create default admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@futurehomes.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('admin123'),
            ]
        );

        $adminUser->assignRole($adminRole);

        $this->command->info('Roles and permissions seeded successfully!');
        $this->command->info('Default admin user created: admin@futurehomes.com / admin123');
    }
}