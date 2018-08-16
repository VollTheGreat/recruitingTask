<?php
namespace App\Domain\Device\Requests;


use Illuminate\Foundation\Http\FormRequest;

class DeviceStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type_id' => 'required|exists:types,id',
            'user_id' => 'required|exists:users,id',
            'model'=> 'required|max:255',
            'brand'=> 'required|max:255',
            'system' => 'required|max:255',
            'version' => 'required|max:255',
            'report_email' => 'required|email'
        ];
    }
}