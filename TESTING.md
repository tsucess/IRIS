# 🧪 Testing Guide

This guide covers testing strategies and procedures for the Community Development System.

---

## 📋 Table of Contents

- [Test Environment Setup](#test-environment-setup)
- [Running Tests](#running-tests)
- [Test Coverage](#test-coverage)
- [Writing Tests](#writing-tests)
- [Testing Best Practices](#testing-best-practices)
- [Continuous Integration](#continuous-integration)

---

## 🔧 Test Environment Setup

### 1. Configure Test Database

The test environment uses an in-memory SQLite database by default (configured in `phpunit.xml`):

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

### 2. Install Testing Dependencies

```bash
composer install --dev
```

### 3. Prepare Test Environment

```bash
# Copy environment file
cp .env.example .env.testing

# Set testing environment
php artisan config:clear
```

---

## 🚀 Running Tests

### Run All Tests
```bash
php artisan test
```

### Run Specific Test Suite
```bash
# Feature tests only
php artisan test --testsuite=Feature

# Unit tests only
php artisan test --testsuite=Unit
```

### Run Specific Test File
```bash
php artisan test tests/Feature/UserManagementTest.php
```

### Run Specific Test Method
```bash
php artisan test --filter test_admin_can_create_user
```

### Run Tests with Coverage
```bash
php artisan test --coverage
```

### Run Tests in Parallel
```bash
php artisan test --parallel
```

### Verbose Output
```bash
php artisan test --verbose
```

---

## 📊 Test Coverage

### Generate HTML Coverage Report
```bash
php artisan test --coverage-html coverage-report
```

Then open `coverage-report/index.html` in your browser.

### Coverage Thresholds
Aim for:
- **Overall**: 80%+ coverage
- **Controllers**: 90%+ coverage
- **Models**: 85%+ coverage
- **Critical paths**: 100% coverage

---

## ✍️ Writing Tests

### Feature Test Example

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
    }
}
```

### Unit Test Example

```php
<?php

namespace Tests\Unit;

use App\Enums\UserRole;
use PHPUnit\Framework\TestCase;

class UserRoleTest extends TestCase
{
    public function test_is_admin_returns_true_for_admin(): void
    {
        $this->assertTrue(UserRole::isAdmin('admin'));
        $this->assertTrue(UserRole::isAdmin('superadmin'));
        $this->assertFalse(UserRole::isAdmin('user'));
    }
}
```

---

## 📝 Test Checklist

### User Management Tests
- [x] Admin can view users index
- [x] Non-admin cannot view users index
- [x] Admin can create user
- [x] Admin can update user
- [x] Admin can delete user
- [x] User creation validates email
- [x] User creation requires password confirmation

### Project Management Tests
- [x] Authenticated user can view projects
- [x] Guest cannot view projects
- [x] User can create project
- [x] User can update project
- [x] User can delete project
- [x] Project requires name
- [x] Project end date must be after start date

### Resident Extended Tests
- [x] User can view extended profile form
- [x] User can update extended profile
- [x] Admin can view user extended profile
- [x] Non-admin cannot view other user profiles
- [x] Admin can update user extended profile
- [x] Extended profile validates email format
- [x] Extended profile validates date of birth
- [x] Extended profile validates gender enum

### Authentication Tests
- [x] Login screen can be rendered
- [x] Users can authenticate
- [x] Users cannot authenticate with invalid password
- [x] Registration screen can be rendered
- [x] New users can register

### Profile Tests
- [x] Profile page is displayed
- [x] Profile information can be updated
- [x] Email verification status unchanged when email unchanged
- [x] User can delete their account

---

## 🎯 Testing Best Practices

### 1. Use Factories
```php
// Good
$user = User::factory()->create(['role' => 'admin']);

// Avoid
$user = new User();
$user->firstname = 'Test';
$user->lastname = 'User';
// ... many more lines
```

### 2. Use Database Transactions
```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyTest extends TestCase
{
    use RefreshDatabase; // Rolls back after each test
}
```

### 3. Test One Thing Per Test
```php
// Good
public function test_user_can_login(): void { }
public function test_user_cannot_login_with_wrong_password(): void { }

// Avoid
public function test_login_functionality(): void {
    // Testing multiple scenarios in one test
}
```

### 4. Use Descriptive Test Names
```php
// Good
public function test_admin_can_delete_user(): void { }

// Avoid
public function test_delete(): void { }
```

### 5. Arrange, Act, Assert Pattern
```php
public function test_example(): void
{
    // Arrange: Set up test data
    $user = User::factory()->create();
    
    // Act: Perform the action
    $response = $this->actingAs($user)->get('/dashboard');
    
    // Assert: Verify the outcome
    $response->assertStatus(200);
}
```

---

## 🔄 Continuous Integration

### GitHub Actions Example

Create `.github/workflows/tests.yml`:

```yaml
name: Tests

on: [push, pull_request]

jobs:
  tests:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v3
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.2'
        extensions: mbstring, xml, ctype, json, bcmath, pdo_sqlite
        
    - name: Install Dependencies
      run: composer install --prefer-dist --no-progress
      
    - name: Run Tests
      run: php artisan test --coverage
```

---

## 🐛 Debugging Tests

### Enable Debug Mode
```bash
php artisan test --debug
```

### Dump Variables
```php
dump($variable);
dd($variable); // Dump and die
```

### Use Ray (Optional)
```bash
composer require spatie/laravel-ray --dev
```

```php
ray($user)->green();
```

---

**Happy Testing! 🎉**

