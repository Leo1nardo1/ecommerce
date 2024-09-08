<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function orders(){
        return $this->hasMany(Order::class);
    }

    //Função que decide quais usuários terão acesso ao painel de admin. O return comentado dá acesso aos usuários que tem email que termina com @yourdomain.com (pode ser mudado de acordo com sua preferencia) e verificou o seu e-mail. A segunda opção é mais direta e também não requer verificação de email (mas pode ser adicionada também)
    public function canAccessPanel(Panel $panel): bool
    {
       // return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();
       return $this->email === 'admin@admin.com';
    }
}
