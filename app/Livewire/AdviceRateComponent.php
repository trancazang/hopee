<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AdviceRequest;
use App\Models\AdviceRating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Livewire\Redirector;

class AdviceRateComponent extends Component
{   
    public AdviceRequest $request;
    public int $rating = 5;
    public string $comment = '';

    protected function rules(): array
    {
        return [
            'rating'  => ['required','integer','between:1,5'],
            'comment' => ['nullable','string','max:1000'],
        ];
    }

    public function mount(AdviceRequest $advice): void
    {
        abort_unless($advice->user_id === Auth::id(), 403);
        abort_unless($advice->status === AdviceRequest::COMPLETED, 403);
        abort_if($advice->rating()->exists(), 404);

        $this->request = $advice;
    }

    public function submit()
    {
        $this->validate();
    
        AdviceRating::create([
            'advice_request_id' => $this->request->id,
            'rating'            => $this->rating,
            'comment'           => $this->comment,
        ]);
    
        session()->flash('success','Cảm ơn bạn đã đánh giá!');
        return redirect()->route('advice.history');
    }
    public function render()
    {
        return view('livewire.advice.rate');
    }
}
