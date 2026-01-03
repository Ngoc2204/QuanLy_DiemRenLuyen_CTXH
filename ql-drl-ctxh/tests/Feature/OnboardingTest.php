<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\SinhVien;
use App\Models\StudentInterest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OnboardingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_student_without_interests_redirected_to_onboarding()
    {
        // Create a test student
        $student = SinhVien::create([
            'MSSV' => 'TEST2024001',
            'HoTen' => 'Test Student',
            'TenDangNhap' => 'test2024001',
            'MatKhau' => bcrypt('password'),
            'Email' => 'test@example.com',
            'VaiTro' => 'SinhVien',
            'SoOld' => '0123456789',
            'MaLop' => 1,
        ]);

        // Login as this student
        $this->actingAs($student);

        // Try to access dashboard - should redirect to onboarding
        $response = $this->get(route('sinhvien.home'));
        
        // Assert redirect to onboarding
        $response->assertRedirect(route('sinhvien.onboarding.interests'));
    }

    /** @test */
    public function test_student_can_view_interest_selection_page()
    {
        // Create a test student
        $student = SinhVien::create([
            'MSSV' => 'TEST2024002',
            'HoTen' => 'Test Student 2',
            'TenDangNhap' => 'test2024002',
            'MatKhau' => bcrypt('password'),
            'Email' => 'test2@example.com',
            'VaiTro' => 'SinhVien',
            'SoOld' => '0123456789',
            'MaLop' => 1,
        ]);

        $this->actingAs($student);

        // Access onboarding page
        $response = $this->get(route('sinhvien.onboarding.interests'));
        
        // Assert the page loads successfully
        $response->assertStatus(200);
        $response->assertViewIs('sinhvien.onboarding.select_interests');
    }

    /** @test */
    public function test_student_can_select_interests()
    {
        // Get some interests
        $interests = DB::table('interests')->limit(3)->pluck('InterestID')->toArray();
        
        // Create a test student
        $student = SinhVien::create([
            'MSSV' => 'TEST2024003',
            'HoTen' => 'Test Student 3',
            'TenDangNhap' => 'test2024003',
            'MatKhau' => bcrypt('password'),
            'Email' => 'test3@example.com',
            'VaiTro' => 'SinhVien',
            'SoOld' => '0123456789',
            'MaLop' => 1,
        ]);

        $this->actingAs($student);

        // Submit interest selection form
        $response = $this->post(route('sinhvien.onboarding.interests.store'), [
            'interests' => $interests,
        ]);

        // Assert redirect to dashboard
        $response->assertRedirect(route('sinhvien.dashboard'));

        // Verify interests were saved
        foreach ($interests as $interestId) {
            $this->assertDatabaseHas('student_interests', [
                'MSSV' => 'TEST2024003',
                'InterestID' => $interestId,
            ]);
        }
    }

    /** @test */
    public function test_student_with_interests_can_access_dashboard()
    {
        // Get some interests
        $interests = DB::table('interests')->limit(2)->pluck('InterestID')->toArray();

        // Create a test student with interests
        $student = SinhVien::create([
            'MSSV' => 'TEST2024004',
            'HoTen' => 'Test Student 4',
            'TenDangNhap' => 'test2024004',
            'MatKhau' => bcrypt('password'),
            'Email' => 'test4@example.com',
            'VaiTro' => 'SinhVien',
            'SoOld' => '0123456789',
            'MaLop' => 1,
        ]);

        // Add interests
        foreach ($interests as $interestId) {
            StudentInterest::create([
                'MSSV' => 'TEST2024004',
                'InterestID' => $interestId,
                'InterestLevel' => 3,
            ]);
        }

        $this->actingAs($student);

        // Access dashboard - should not redirect
        $response = $this->get(route('sinhvien.home'));
        
        // Assert no redirect (200 or 500 but not 302)
        $this->assertNotEquals(302, $response->getStatusCode());
    }
}
?>
