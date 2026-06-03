<div class="otp-verification-container">
    <h2>Verify Your Mobile Number</h2>

    <p>
        We have sent a One-Time Password (OTP) to your registered mobile number.
        Please enter the code below to complete your registration.
    </p>

    <form action="{{route('customer.otp.verify')}}" method="POST">
        <!-- Laravel CSRF Token -->
        @csrf

        <div class="form-group">
            <label for="otp">Enter OTP</label>
            <input type="hidden" name="phone" value="{{ session()->get('customer_phone') }}">
            <input
                type="text"
                id="otp"
                name="otp"
                maxlength="6"
                placeholder="Enter 6-digit OTP"
                required>
        </div>

        <button type="submit">
            Verify OTP
        </button>
    </form>

    <div class="resend-section">
        <p>Didn't receive the OTP?</p>
        <a href="/resend-otp">Resend OTP</a>
    </div>
</div>

<style>
    .otp-verification-container {
        max-width: 450px;
        margin: 50px auto;
        padding: 30px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background: #fff;
        text-align: center;
    }

    .otp-verification-container h2 {
        margin-bottom: 15px;
    }

    .otp-verification-container p {
        color: #666;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
        text-align: left;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .form-group input {
        width: 100%;
        padding: 12px;
        font-size: 18px;
        text-align: center;
        letter-spacing: 4px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    button {
        width: 100%;
        padding: 12px;
        background: #2563eb;
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        opacity: 0.9;
    }

    .resend-section {
        margin-top: 15px;
    }

    .resend-section a {
        color: #2563eb;
        text-decoration: none;
    }
</style>