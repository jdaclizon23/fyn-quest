# MakeModule Command

## Usage

Create a new module with Clean Architecture structure:

```bash
php artisan make:module ModuleName
```

### Example

```bash
php artisan make:module Product
```

This will create:

```
app/Modules/Product/
├── Domain/
│   ├── Models/
│   │   └── Product.php
│   ├── Repositories/
│   │   └── ProductRepositoryInterface.php
│   └── Services/
│
├── Application/
│   ├── Actions/
│   └── DTO/
│
├── Infrastructure/
│   ├── Repositories/
│   │   └── EloquentProductRepository.php
│   ├── Services/
│   └── Providers/
│       └── ProductServiceProvider.php
│
├── Presentation/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Requests/
│   │   └── Resources/
│   └── Routes/
│       └── product_api.php
│
├── Flow.md
└── README.md
```

## What It Does

1. ✅ Creates complete folder structure
2. ✅ Generates Model with Eloquent setup
3. ✅ Generates Repository Interface
4. ✅ Generates Repository Implementation
5. ✅ Generates Service Provider
6. ✅ Generates Routes file
7. ✅ Generates Documentation (Flow.md, README.md)
8. ✅ **Automatically registers Service Provider** in `bootstrap/providers.php`

## Generated Files

- **Model**: `Domain/Models/{ModelName}.php` (Eloquent model with business logic structure)
- **Repository Interface**: `Domain/Repositories/{ModelName}RepositoryInterface.php`
- **Repository Implementation**: `Infrastructure/Repositories/Eloquent{ModelName}Repository.php`
- **Service Provider**: `Infrastructure/Providers/{ModuleName}ServiceProvider.php`
- **Routes**: `Presentation/Routes/{module_name}_api.php`
- **Documentation**: `Flow.md` and `README.md`

## Service Provider Registration

The command automatically adds the service provider to `bootstrap/providers.php`:

```php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\FortifyServiceProvider::class,
    App\Modules\Product\Infrastructure\Providers\ProductServiceProvider::class, // ← Auto-added
];
```

## Next Steps

After creating a module:

1. **Update the Model**: Add fillable fields, casts, and business logic methods
2. **Update Repository Interface**: Add custom methods if needed
3. **Create Actions**: Add your actions in `Application/Actions/`
4. **Create DTOs**: Add DTOs in `Application/DTO/`
5. **Create Controllers**: Add controllers in `Presentation/Http/Controllers/`
6. **Create Requests**: Add form requests in `Presentation/Http/Requests/`
7. **Register Routes**: Update routes file and load in service provider if needed

## Notes

- Module name should be singular or plural (e.g., `Product` or `Products`)
- Model name is automatically singularized (e.g., `Products` → `Product`)
- Table name is automatically pluralized and snake_cased (e.g., `Product` → `products`)
- Service provider includes repository binding by default
