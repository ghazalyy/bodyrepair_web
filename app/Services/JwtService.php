<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use UnexpectedValueException;

class JwtService
{
    private string $secret;
    private string $algo = 'HS256';

    public function __construct()
    {
        // Gunakan APP_KEY sebagai secret (strip prefix 'base64:' jika ada)
        $key = config('app.key');
        if (str_starts_with($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }
        $this->secret = $key;
    }

    /**
     * Generate JWT token untuk user tertentu.
     * Lifetime: 2 jam (sinkron dengan SESSION_LIFETIME default).
     */
    public function generate(User $user): string
    {
        $now = time();

        $payload = [
            'iss' => config('app.url'),         // Issuer
            'sub' => $user->getKey(),            // Subject (user id) — pakai getKey() agar support custom primaryKey
            'role' => $user->role,               // Role untuk cek otorisasi
            'email' => $user->email,             // Email hint
            'iat' => $now,                       // Issued at
            'nbf' => $now,                       // Not before
            'exp' => $now + (60 * 120),          // Expire: 2 jam
            'jti' => bin2hex(random_bytes(16)),  // Unique ID per token
        ];

        return JWT::encode($payload, $this->secret, $this->algo);
    }

    /**
     * Decode & validasi JWT token.
     * Return: object payload atau null jika tidak valid.
     */
    public function validate(string $token): ?object
    {
        try {
            return JWT::decode($token, new Key($this->secret, $this->algo));
        } catch (ExpiredException $e) {
            Log::debug('JWT expired', ['msg' => $e->getMessage()]);
            return null;
        } catch (SignatureInvalidException $e) {
            Log::warning('JWT signature invalid', ['msg' => $e->getMessage()]);
            return null;
        } catch (UnexpectedValueException $e) {
            Log::warning('JWT decode error', ['msg' => $e->getMessage()]);
            return null;
        } catch (\Exception $e) {
            Log::warning('JWT unknown error', ['msg' => $e->getMessage()]);
            return null;
        }
    }
}
