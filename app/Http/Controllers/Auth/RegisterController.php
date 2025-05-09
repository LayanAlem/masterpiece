<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ReferralService;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(middleware: 'guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'referral_code' => [
                'nullable',
                'string',
                'max:20',
                function ($attribute, $value, $fail) {
                    // Skip validation if no referral code provided
                    if (empty($value)) {
                        return;
                    }

                    // Validate referral code format and existence
                    $referralService = app(ReferralService::class);
                    if (!$referralService->isValidReferralCode($value)) {
                        $fail('The provided referral code is invalid.');
                        return;
                    }

                    // Check if referral code has reached maximum usage
                    $referrer = \App\Models\User::where('referral_code', $value)->first();
                    if ($referrer) {
                        $maxUses = (int) \App\Models\Setting::get('referral_max_uses', 5);
                        $currentUses = $referrer->referrals()->count();

                        if ($currentUses >= $maxUses) {
                            $fail("This referral code has reached its maximum usage limit ($maxUses).");
                        }
                    }
                }
            ],
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $userData = [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'loyalty_points' => 0,
            'referral_balance' => 0
        ];

        // Handle profile image if uploaded
        if (isset($data['profile_image'])) {
            $imagePath = $data['profile_image']->store('profile-images', 'public');
            $userData['profile_image'] = $imagePath;
        }

        // Create the user (referral_code will be automatically generated in the User model boot method)
        $user = User::create($userData);

        // Apply referral code if provided
        if (!empty($data['referral_code'])) {
            app(ReferralService::class)->applyReferralCode($user, $data['referral_code']);
        }

        return $user;
    }

    // Add this method to override the default behavior
    protected function registered(Request $request, $user)
    {
        // For debugging
        Log::info('User registered, redirecting to: ' . $this->redirectTo);

        return redirect($this->redirectTo);
    }
}
