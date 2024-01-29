<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Event;

class CalendarComponent extends Component
{

    protected $listeners = ['show'];

    public $all_events, $EventsDay, $selectedDay, $event;

    public function render()
    {

        $this->EventsDay = Event::query();
        $this->EventsDay = $this->EventsDay->where('start','like','%'.$this->selectedDay.'%');
        $this->EventsDay = $this->EventsDay->get();

        $this->all_events = Event::all();
        $this->all_events = json_encode($this->all_events);

        return view('livewire.calendar-component')->layout('components.layout-BaseElements');
    }

    public function show($selectedDay)
    {
        $this->selectedDay = (new \DateTime($selectedDay))->format('Y-m-d');
    }

    public function store($event)
    {
        $this->event = new Event();
        $this->event->title = $event['title'];
        $this->event->start = $event['start'];
        $this->event->end = $event['end'];
        $this->event->description = $event['description'];
        $this->event->save();

        $this->refresh();
    }

    public function update($event)
    {
        $this->event = Event::find($event['id']);
        $this->event->title = $event['title'];
        $this->event->start = $event['start'];
        $this->event->end = $event['end'];
        $this->event->description = $event['description'];
        $this->event->update();

        $this->refresh();

    }

    public function destroy($event)
    {
        Event::find($event['id'])->delete();
        $this->refresh();
    }

    public function refresh()
    {

        $this->emit('closeModal');
        $this->emit('refreshCalendar');

        $this->selectedDay = "";

    }

}
