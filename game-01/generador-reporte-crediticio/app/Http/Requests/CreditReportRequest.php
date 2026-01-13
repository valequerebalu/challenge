<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class CreditReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'from' => [
                'required',
                'date',
                'before_or_equal:to',
                function ($attribute, $value, $fail) {
                    $to = request('to'); // obtenemos la fecha 'to' enviada en el request
                    if ($to) {
                        $maxRange = Carbon::parse($to)->subMonths(6);
                        if (Carbon::parse($value)->lt($maxRange)) {
                            $fail("La fecha {$attribute} no puede ser más de 6 meses antes de la fecha 'to'.");
                        }
                    }
                }
            ],
            'to' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
        ];
    }
}
