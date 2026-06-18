<?php

namespace Database\Seeders;

use App\Models\EntryExitLog;
use App\Models\Notification;
use App\Models\ParkingSpot;
use App\Models\ParkingZone;
use App\Models\Reservation;
use App\Models\RolePermission;
use App\Models\User;
use App\Models\Violation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPermissions();
        $this->seedUsers();
        $this->seedParkingZones();
        $this->seedParkingSpots();
        $this->seedReservations();
        $this->seedNotifications();
        $this->seedViolations();
        $this->seedEntryExitLogs();
    }

    private function seedPermissions(): void
    {
        $permissions = [
            // Student Permissions
            ['role' => 'student', 'module' => 'Parking', 'permission' => 'view_parking_map', 'is_granted' => true],
            ['role' => 'student', 'module' => 'Reservation', 'permission' => 'create_reservation', 'is_granted' => true],
            ['role' => 'student', 'module' => 'Reservation', 'permission' => 'view_own_reservations', 'is_granted' => true],
            ['role' => 'student', 'module' => 'Reservation', 'permission' => 'cancel_reservation', 'is_granted' => true],
            ['role' => 'student', 'module' => 'Profile', 'permission' => 'edit_own_profile', 'is_granted' => true],
            ['role' => 'student', 'module' => 'Notification', 'permission' => 'view_notifications', 'is_granted' => true],
            ['role' => 'student', 'module' => 'History', 'permission' => 'view_own_history', 'is_granted' => true],
            ['role' => 'student', 'module' => 'Admin', 'permission' => 'access_admin_panel', 'is_granted' => false],
            ['role' => 'student', 'module' => 'Admin', 'permission' => 'manage_users', 'is_granted' => false],
            ['role' => 'student', 'module' => 'Admin', 'permission' => 'manage_zones', 'is_granted' => false],
            ['role' => 'student', 'module' => 'Admin', 'permission' => 'view_all_reservations', 'is_granted' => false],
            ['role' => 'student', 'module' => 'Report', 'permission' => 'generate_reports', 'is_granted' => false],
            // Staff Permissions
            ['role' => 'staff', 'module' => 'Parking', 'permission' => 'view_parking_map', 'is_granted' => true],
            ['role' => 'staff', 'module' => 'Reservation', 'permission' => 'create_reservation', 'is_granted' => true],
            ['role' => 'staff', 'module' => 'Reservation', 'permission' => 'view_own_reservations', 'is_granted' => true],
            ['role' => 'staff', 'module' => 'Reservation', 'permission' => 'cancel_reservation', 'is_granted' => true],
            ['role' => 'staff', 'module' => 'Profile', 'permission' => 'edit_own_profile', 'is_granted' => true],
            ['role' => 'staff', 'module' => 'Notification', 'permission' => 'view_notifications', 'is_granted' => true],
            ['role' => 'staff', 'module' => 'History', 'permission' => 'view_own_history', 'is_granted' => true],
            ['role' => 'staff', 'module' => 'Admin', 'permission' => 'access_admin_panel', 'is_granted' => false],
            ['role' => 'staff', 'module' => 'Admin', 'permission' => 'manage_users', 'is_granted' => false],
            ['role' => 'staff', 'module' => 'Admin', 'permission' => 'manage_zones', 'is_granted' => false],
            ['role' => 'staff', 'module' => 'Admin', 'permission' => 'view_all_reservations', 'is_granted' => true],
            ['role' => 'staff', 'module' => 'Report', 'permission' => 'generate_reports', 'is_granted' => false],
            // Admin Permissions
            ['role' => 'admin', 'module' => 'Parking', 'permission' => 'view_parking_map', 'is_granted' => true],
            ['role' => 'admin', 'module' => 'Reservation', 'permission' => 'create_reservation', 'is_granted' => true],
            ['role' => 'admin', 'module' => 'Reservation', 'permission' => 'view_own_reservations', 'is_granted' => true],
            ['role' => 'admin', 'module' => 'Reservation', 'permission' => 'cancel_reservation', 'is_granted' => true],
            ['role' => 'admin', 'module' => 'Profile', 'permission' => 'edit_own_profile', 'is_granted' => true],
            ['role' => 'admin', 'module' => 'Notification', 'permission' => 'view_notifications', 'is_granted' => true],
            ['role' => 'admin', 'module' => 'History', 'permission' => 'view_own_history', 'is_granted' => true],
            ['role' => 'admin', 'module' => 'Admin', 'permission' => 'access_admin_panel', 'is_granted' => true],
            ['role' => 'admin', 'module' => 'Admin', 'permission' => 'manage_users', 'is_granted' => true],
            ['role' => 'admin', 'module' => 'Admin', 'permission' => 'manage_zones', 'is_granted' => true],
            ['role' => 'admin', 'module' => 'Admin', 'permission' => 'view_all_reservations', 'is_granted' => true],
            ['role' => 'admin', 'module' => 'Report', 'permission' => 'generate_reports', 'is_granted' => true],
        ];

        foreach ($permissions as $perm) {
            RolePermission::updateOrCreate(
                ['role' => $perm['role'], 'permission' => $perm['permission']],
                $perm
            );
        }
    }

    private function seedUsers(): void
    {
        User::updateOrCreate(['email' => 'admin@uoh.edu.sa'], [
            'name' => 'System Administrator',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'university_id' => 'ADM-0001',
            'phone' => '+966-17-000-0001',
            'vehicle_plate' => 'ADM 001',
            'vehicle_model' => 'Toyota Land Cruiser',
            'vehicle_color' => 'White',
            'is_active' => true,
        ]);

        User::updateOrCreate(['email' => 'staff@uoh.edu.sa'], [
            'name' => 'Dr. Ahmed Al-Rashidi',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'university_id' => 'STF-1001',
            'phone' => '+966-17-000-0002',
            'vehicle_plate' => 'XYZ 234',
            'vehicle_model' => 'Honda Accord',
            'vehicle_color' => 'Black',
            'is_active' => true,
        ]);

        $students = [
            ['name' => 'Mohammed Al-Harbi', 'email' => 'student1@uoh.edu.sa', 'uid' => 'STU-4001', 'plate' => 'ABC 111', 'model' => 'Toyota Camry', 'color' => 'Silver'],
            ['name' => 'Sara Al-Otaibi', 'email' => 'student2@uoh.edu.sa', 'uid' => 'STU-4002', 'plate' => 'DEF 222', 'model' => 'Hyundai Accent', 'color' => 'Red'],
            ['name' => 'Omar Al-Ghamdi', 'email' => 'student3@uoh.edu.sa', 'uid' => 'STU-4003', 'plate' => 'GHI 333', 'model' => 'Kia Sportage', 'color' => 'Blue'],
            ['name' => 'Fatima Al-Zahrani', 'email' => 'student4@uoh.edu.sa', 'uid' => 'STU-4004', 'plate' => 'JKL 444', 'model' => 'Nissan Sunny', 'color' => 'White'],
        ];

        foreach ($students as $student) {
            User::updateOrCreate(['email' => $student['email']], [
                'name' => $student['name'],
                'password' => Hash::make('password'),
                'role' => 'student',
                'university_id' => $student['uid'],
                'vehicle_plate' => $student['plate'],
                'vehicle_model' => $student['model'],
                'vehicle_color' => $student['color'],
                'is_active' => true,
            ]);
        }
    }

    private function seedParkingZones(): void
    {
        $zones = [
            ['name' => 'Zone A - Main Gate', 'code' => 'ZA', 'description' => 'Main entrance parking area near admin buildings', 'total_spots' => 30, 'floor' => 'Ground', 'location' => 'Main Gate North', 'latitude' => 27.5114, 'longitude' => 41.7208],
            ['name' => 'Zone B - Faculty Building', 'code' => 'ZB', 'description' => 'Staff and faculty dedicated parking zone', 'total_spots' => 20, 'floor' => 'Ground', 'location' => 'Faculty Building East', 'latitude' => 27.5124, 'longitude' => 41.7218],
            ['name' => 'Zone C - Student Center', 'code' => 'ZC', 'description' => 'Student parking near the student activity center', 'total_spots' => 40, 'floor' => 'Ground', 'location' => 'Student Center West', 'latitude' => 27.5104, 'longitude' => 41.7198],
            ['name' => 'Zone D - Library', 'code' => 'ZD', 'description' => 'Accessible and general parking near the university library', 'total_spots' => 25, 'floor' => 'Ground', 'location' => 'Library South', 'latitude' => 27.5134, 'longitude' => 41.7228],
        ];

        foreach ($zones as $zone) {
            ParkingZone::updateOrCreate(['code' => $zone['code']], $zone);
        }
    }

    private function seedParkingSpots(): void
    {
        $zones = ParkingZone::all();
        foreach ($zones as $zone) {
            $total = $zone->total_spots;
            for ($i = 1; $i <= $total; $i++) {
                $type = 'standard';
                if ($i % 10 === 0) $type = 'disabled';
                elseif ($zone->code === 'ZB' && $i <= 10) $type = 'staff';
                elseif ($i % 7 === 0) $type = 'vip';

                $rand = rand(1, 10);
                $status = 'available';
                if ($rand <= 3) $status = 'occupied';
                elseif ($rand <= 5) $status = 'reserved';

                ParkingSpot::updateOrCreate(
                    ['zone_id' => $zone->id, 'spot_number' => str_pad($i, 2, '0', STR_PAD_LEFT)],
                    ['type' => $type, 'status' => $status, 'is_active' => true]
                );
            }
        }
    }

    private function seedReservations(): void
    {
        $users = User::whereIn('role', ['student', 'staff'])->get();
        $spots = ParkingSpot::where('status', 'available')->take(5)->get();

        foreach ($users->take(3) as $index => $user) {
            if ($spots->get($index)) {
                $spot = $spots->get($index);
                Reservation::create([
                    'user_id' => $user->id,
                    'spot_id' => $spot->id,
                    'start_time' => now()->addHours(1),
                    'end_time' => now()->addHours(3),
                    'status' => 'active',
                    'vehicle_plate' => $user->vehicle_plate,
                    'notes' => 'Regular daily reservation',
                ]);
                $spot->update(['status' => 'reserved']);
            }
        }
    }

    private function seedNotifications(): void
    {
        $users = User::all();
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Welcome to UniPark!',
                'message' => 'Your account has been successfully created. Start booking your parking spot now.',
                'type' => 'success',
                'icon' => 'check-circle',
                'is_read' => false,
            ]);

            if ($user->role !== 'admin') {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Parking Map Updated',
                    'message' => 'New parking zones have been added. Check the map for available spots.',
                    'type' => 'info',
                    'icon' => 'map',
                    'is_read' => false,
                ]);
            }
        }
    }

    private function seedViolations(): void
    {
        $users = User::whereIn('role', ['student', 'staff'])->take(2)->get();
        $spots = ParkingSpot::take(2)->get();

        foreach ($users as $index => $user) {
            if ($spots->get($index)) {
                Violation::create([
                    'user_id' => $user->id,
                    'spot_id' => $spots->get($index)->id,
                    'type' => 'overstay',
                    'description' => 'Vehicle remained in the spot past reservation end time.',
                    'vehicle_plate' => $user->vehicle_plate,
                    'status' => 'pending',
                    'fine_amount' => 50.00,
                    'fine_paid' => false,
                    'violation_time' => now()->subHours(2),
                ]);
            }
        }
    }

    private function seedEntryExitLogs(): void
    {
        $users = User::whereIn('role', ['student', 'staff'])->get();
        $spots = ParkingSpot::take(3)->get();

        foreach ($users->take(3) as $index => $user) {
            if ($spots->get($index)) {
                EntryExitLog::create([
                    'user_id' => $user->id,
                    'spot_id' => $spots->get($index)->id,
                    'event_type' => 'entry',
                    'vehicle_plate' => $user->vehicle_plate,
                    'vehicle_model' => $user->vehicle_model,
                    'event_time' => now()->subHours(2),
                    'notes' => 'Normal entry',
                    'recorded_by' => 'System',
                ]);
            }
        }
    }
}
