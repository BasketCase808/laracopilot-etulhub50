<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApiClient extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'email',
        'website',
        'description',
        'api_key',
        'api_secret',
        'allowed_domains',
        'allowed_ips',
        'active'
    ];
    
    protected $casts = [
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    protected $hidden = [
        'api_secret'
    ];
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    
    public function isDomainAllowed($domain)
    {
        if (empty($this->allowed_domains)) {
            return true;
        }
        
        $allowedDomains = array_map('trim', explode(',', $this->allowed_domains));
        
        foreach ($allowedDomains as $allowedDomain) {
            if (str_contains($domain, $allowedDomain) || str_contains($allowedDomain, $domain)) {
                return true;
            }
        }
        
        return false;
    }
    
    public function isIpAllowed($ip)
    {
        if (empty($this->allowed_ips)) {
            return true;
        }
        
        $allowedIps = array_map('trim', explode(',', $this->allowed_ips));
        
        foreach ($allowedIps as $allowedIp) {
            // Support CIDR notation
            if (str_contains($allowedIp, '/')) {
                if ($this->ipInCidr($ip, $allowedIp)) {
                    return true;
                }
            } else {
                if ($ip === $allowedIp) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    private function ipInCidr($ip, $cidr)
    {
        list($subnet, $mask) = explode('/', $cidr);
        
        if ((ip2long($ip) & ~((1 << (32 - $mask)) - 1)) == ip2long($subnet)) {
            return true;
        }
        
        return false;
    }
}