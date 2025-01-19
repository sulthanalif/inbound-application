<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\LogActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
// use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Notifications\Notifiable;
use Creagia\LaravelSignPad\SignaturePosition;
use Spatie\Activitylog\Traits\CausesActivity;
use Creagia\LaravelSignPad\Contracts\CanBeSigned;
use Creagia\LaravelSignPad\SignatureDocumentTemplate;
use Creagia\LaravelSignPad\Concerns\RequiresSignature;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Creagia\LaravelSignPad\Templates\PdfDocumentTemplate;
use Creagia\LaravelSignPad\Templates\BladeDocumentTemplate;
use Creagia\LaravelSignPad\Contracts\ShouldGenerateSignatureDocument;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, RequiresSignature;

    use LogActivity, CausesActivity;

    // Opsi log
    protected $logName = 'master_user';

    // Atribut tambahan untuk di-ignore jika dibutuhkan
    protected array $logAttributesToIgnore = ['password'];
    protected array $logAttributes = [
        'name',
        'nip',
        'position',
        'address',
        'company',
        'phone',
        'email',
        'password',
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nip',
        'position',
        'address',
        'company',
        'phone',
        'email',
        'password',
        'is_active'
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



    public function goods()
    {
        return $this->hasMany(Goods::class);
    }

    public function outbounds()
    {
        return $this->hasMany(Outbound::class);
    }

    public function getSignatureDocumentTemplate(): SignatureDocumentTemplate
    {
        return new SignatureDocumentTemplate(
            outputPdfPrefix: 'document', // optional
            template: new BladeDocumentTemplate('pdf/my-pdf-blade-template'),
            signaturePositions: [
                 new SignaturePosition(
                     signaturePage: 1,
                     signatureX: 20,
                     signatureY: 25,
                 ),
                 new SignaturePosition(
                     signaturePage: 2,
                     signatureX: 25,
                     signatureY: 50,
                 ),
            ]
        );
    }
}
