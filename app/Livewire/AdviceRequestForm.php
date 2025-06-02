<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AdviceRequest;
use Illuminate\Support\Facades\Auth;

class AdviceRequestForm extends Component
{   

    public string $preferred_time;   
    public string $notes = '';

    protected function rules(): array
    {
        return [
            'preferred_time'=> ['required','date','after:now'],
            'notes'         => ['nullable','string','max:1000'],
        ];
    }

    public function submit(): void
    {
        $this->validate();

        AdviceRequest::create([
            'user_id'        => Auth::id(),
            'preferred_time' => $this->preferred_time,
            'notes'          => $this->notes,
            'status'         => AdviceRequest::PENDING,
        ]);

        session()->flash('success','Đăng ký tư vấn thành công! Hệ thống sẽ liên hệ sớm nhất.');
        $this->reset(['preferred_time','notes']);
    }

    public function render()
    {
        return view('livewire.advice.request' );
    }
}
