{{-- <!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز التحقق OTP</title>
</head>
<body>
    <h2>رمز التحقق الخاص بك هو:</h2>
    <h3>{{ $otp }}</h3>
    <p>يُرجى إدخال هذا الرمز في الموقع لإكمال التسجيل. الرمز صالح لمدة 10 دقائق فقط.</p>
</body>
</html> --}}



<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كود التحقق OTP</title>
</head>

<body style="background-color: #f4f4f4; padding: 20px; font-family: Arial, sans-serif; text-align: center; direction: rtl;">
    <table align="center" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center">
                <table width="400px" bgcolor="white" cellspacing="0" cellpadding="20" style="border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center;">
                    <tr>
                        <td>
                            <h2 style="color: #333;">رمز التحقق OTP</h2>
                            <p style="color: #555; font-size: 16px;">
                                استخدم هذا الرمز لتأكيد حسابك، صالح لمدة 10 دقائق:
                            </p>
                            <div style="font-size: 24px; font-weight: bold; color: #4F46E5; background: #F3F4F6; display: inline-block; padding: 10px 20px; border-radius: 5px; margin: 10px 0;">
                                {{ $otp }}
                            </div>
                            <p style="color: #777; font-size: 14px;">
                                إذا لم تطلب هذا الكود، يمكنك تجاهل هذا البريد.
                            </p>
                            <a href="https://yourwebsite.com"
                                style="display: inline-block; background: #4F46E5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 10px;">
                                الذهاب للموقع
                            </a>
                            <p style="font-size: 12px; color: #999; margin-top: 15px;">
                                © 2025 جميع الحقوق محفوظة لموقعك.YAZAN
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
