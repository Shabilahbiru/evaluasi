<h2>Reset Password</h2>
<p>Halo {{ $user->name }},</p>
<p>Klik tombol di bawah ini untuk reset password kamu:</p>
<a href="{{ url('/reset-password/' . $token) }}" style="background: #D32F2F; padding: 10px 20px; color: white; border-radius: 5px; text-decoration: none;">
Reset Password</a>
<p>Jika kamu tidak merasa meminta ini, abaikan saja pesan email ini.</p>