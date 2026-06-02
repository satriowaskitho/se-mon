<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDailyReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Handled by policies and controllers
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'assignment_id' => ['required', 'exists:assignments,id'],
            'report_date' => ['required', 'date'],
            'usaha_today' => ['required', 'integer', 'min:0'],
            'ruta_today' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'assignment_id' => 'SubSLS Tugas',
            'report_date' => 'Tanggal Laporan',
            'usaha_today' => 'Jumlah Usaha Hari Ini',
            'ruta_today' => 'Jumlah Ruta Hari Ini',
            'notes' => 'Catatan',
        ];
    }
}
