# Laravel Sync Attributes between models

## Installation
`composer require hbliang/laravel-attributes-replication`

## Configuration
`php artisan vendor:publish --provider="Hbliang\AttributesReplication\ReplicationServiceProvider"`

## Usage


companies

    id - integer
    name - string

users

    id - integer
    company_id - integer
    company- string


```PHP
use Hbliang\AttributesReplication\Contracts\AttributesReplicatable;
use Hbliang\AttributesReplication\Traits\HasAttributesReplication;
use Illuminate\Database\Eloquent\Model;

class Company extends Model implements AttributesReplicatable
{
    use HasAttributesReplication;
    protected $fillable = ['name'];

    public static function registerAttributesReplication()
    {
        self::addAttributesReplication()
            ->map([
                'name' => 'company',
            ])
            ->relation('users')
            ->event('saved');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
```