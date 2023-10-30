<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CustomIPValidation implements Rule
{
    public function passes($attribute, $value)
    {
        // Проверяем, что значение является допустимым IPv4 или IPv6 адресом
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6) !== false;
    }

    public function message()
    {
        return 'Недопустимый формат IP-адреса.';
    }
}
