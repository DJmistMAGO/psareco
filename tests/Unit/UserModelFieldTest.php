<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserModelFieldTest extends TestCase
{
    public function test_removed_role_fields_are_not_in_model_fillable(): void
    {
        $fillable = (new User())->getFillable();

        $this->assertNotContains('rsbsa_number', $fillable);
        $this->assertNotContains('farm_size_hectares', $fillable);
        $this->assertNotContains('primary_crop', $fillable);
        $this->assertNotContains('employee_id', $fillable);
        $this->assertNotContains('department', $fillable);
    }
}
