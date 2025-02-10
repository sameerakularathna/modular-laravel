<?php
namespace Custom\GoldRate\Livewire;

use Livewire\Component;
use Custom\GoldRate\Models\GoldRate;
use Illuminate\Support\Facades\Session;

class GoldRateComponent extends Component
{
    public $metal, $with_duty_free, $duty_free, $goldRates;
    public $editingId = null;

    protected $rules = [
        'metal' => 'required',
        'with_duty_free' => 'required|numeric',
        'duty_free' => 'required|numeric',
    ];

    public function mount()
    {
        $this->goldRates = GoldRate::all();
    }

    public function store()
    { 
        $this->validate();
        GoldRate::create([
            'metal' => $this->metal,
            'with_duty_free' => $this->with_duty_free,
            'duty_free' => $this->duty_free,
            'created_by' => auth()->id(),
        ]);

        session()->flash('message', 'Gold rate added successfully.');
        $this->reset();
        $this->goldRates = GoldRate::all();
    }

    public function edit($id)
    {
        $goldRate = GoldRate::findOrFail($id);
        $this->editingId = $id;
        $this->metal = $goldRate->metal;
        $this->with_duty_free = $goldRate->with_duty_free;
        $this->duty_free = $goldRate->duty_free;
    }

    public function update()
    {
        $this->validate();
        GoldRate::find($this->editingId)->update([
            'mittle' => $this->metal,
            'with_duty_free' => $this->with_duty_free,
            'duty_free' => $this->duty_free,
        ]);

        session()->flash('message', 'Gold rate updated successfully.');
        $this->reset();
        $this->goldRates = GoldRate::all();
    }

    public function delete($id)
    {
        GoldRate::findOrFail($id)->delete();
        session()->flash('message', 'Gold rate deleted successfully.');
        $this->goldRates = GoldRate::all();
    }

    public function render()
    {
        // return view('goldrate::livewire.gold-rate-component');
        return view('goldrate::livewire.gold-rate-component')
        ->layout('goldrate::layouts.app'); // Use the package layout
        
    }
}
