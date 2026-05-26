<?php

namespace App\Models;

use App\Constants\VisitType;
use App\Support\Enum\UserTypes;
use Modules\Orders\Models\Cart;
use Modules\Orders\Models\Order;
use Laravel\Sanctum\HasApiTokens;
use Modules\Locations\Models\Location;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Modules\Doctors\Models\Appointment;
use Illuminate\Notifications\Notifiable;
use Modules\Assurance\Models\FamilyMember;
use Modules\Transactions\Models\Transaction;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable implements HasMedia
{
  use HasApiTokens, HasFactory, Notifiable, HasRoles, InteractsWithMedia;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */

  const string CONTRACT = 'contract';

  protected $guard_name = 'web';

  protected $fillable = [
    'firstname',
    'lastname',
    'email',
    'phone',
    'avatar',
    'password',
    'type',
    'gender',
    'blood_type',
    'birthdate',
    'full_address',
    'city_id',
    'status',
    'is_insured',
    'insurance_date',
    'passport_number',
    'insurance_number',
    'wallet_balance',
    'device_token',
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
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'birthdate' => 'date',
    'email_verified_at' => 'datetime',
    'is_insured' => 'boolean',
    'insurance_date' => 'date',
  ];

  public function city(): BelongsTo
  {
    return $this->belongsTo(City::class);
  }
  public function appointments(): HasMany
  {
    return $this->hasMany(Appointment::class);
  }

  public function familyMembers(): HasMany
  {
    return $this->hasMany(FamilyMember::class);
  }

  public function transactions(): MorphMany
  {
    return $this->morphMany(Transaction::class, 'transactionable');
  }

  public function notifications(): MorphMany
  {
    return $this->morphMany(\App\Models\Notification::class, 'notifiable');
  }

  public function unreadNotifications()
  {
    return $this->notifications()->whereNull('read_at');
  }


  public function getFullnameAttribute()
  {
    return $this->firstname . ' ' . $this->lastname;
  }

  public function getAvatarUrlAttribute()
  {
    return $this->avatar && Storage::disk('public')->exists($this->avatar)
      ? Storage::disk('public')->url($this->avatar)
      : $this->avatar;
  }

  public function scopeAdmins($query)
  {
    return $query->where('type', UserTypes::ADMIN);
  }

  public function cart(){
    $attributes = [
      'user_id' => $this->id,
      'status' => 'active'
    ];
    return Cart::firstOrCreate($attributes);
  }

  public function locations(){
    return $this->hasMany(Location::class);
  }

  public function orders(){
    return $this->hasMany(Order::class);
  }

  public function visits(){
    return $this->hasMany(Visit::class);
  }

  public static function generateUniqueInsuranceNumber(): string
  {
    do {
      // Example format: 202506180001 (date + random number)
      $insuranceNumber = now()->format('Ymd') . random_int(1000, 9999);
    } while (self::where('insurance_number', $insuranceNumber)->exists());

    return $insuranceNumber;
  }

  public function getLastAppointementsVisitAttribute(){
    return $this->visits()->where('type', VisitType::APPOINTMENT)->latest()->first()?->created_at;
  }

  public function getLastOrdersVisitAttribute(){
    return $this->visits()->where('type', VisitType::ORDER)->latest()->first()?->created_at;
  }

  public function getOrderCountAttribute(){
    return $this->orders()
      ->where('status', '!=', 'pending')
      ->where('created_at', '>=', now()->subWeek())
      ->where('updated_at', '>', $this->last_orders_visit ?? '1970-1-1 0:0:0')
      ->count();
  }

  public function getAppointmentCountAttribute(){
    return 0;
  }

  public function registerMediaCollections(): void
  {
    $this->addMediaCollection(self::CONTRACT)
      ->singleFile()
      ->useDisk('public');
  }
}
