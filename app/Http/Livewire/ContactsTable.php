<?php

namespace App\Http\Livewire;

use App\Models\Contact;
use Livewire\Component;
use Livewire\WithPagination;

class ContactsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $contactId;
    public $name, $email, $phone;
    public $modalTitle = 'Create Contact';

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'phone' => 'required'
    ];

    protected $listeners = ['deleteConfirmed' => 'delete'];

    public function render()
    {
        $contacts = Contact::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.contacts-table', compact('contacts'));
    }

    public function sortBy($field)
    {
        $this->sortDirection = ($this->sortField === $field && $this->sortDirection === 'asc') ? 'desc' : 'asc';
        $this->sortField = $field;
    }

    public function create()
    {
        $this->resetInput();
        $this->modalTitle = 'Create Contact';
        $this->dispatchBrowserEvent('show-modal');
    }

    public function edit($id)
    {
        $contact = Contact::findOrFail($id);
        $this->contactId = $contact->id;
        $this->name = $contact->name;
        $this->email = $contact->email;
        $this->phone = $contact->phone;
        $this->modalTitle = 'Update Contact';
        $this->dispatchBrowserEvent('show-modal');
    }

    public function store()
    {
        $this->validate();

        Contact::updateOrCreate(
            ['id' => $this->contactId],
            ['name' => $this->name, 'email' => $this->email, 'phone' => $this->phone]
        );

        session()->flash('message', $this->contactId ? 'Contact updated successfully.' : 'Contact created successfully.');
        $this->dispatchBrowserEvent('hide-modal');
        $this->dispatchBrowserEvent('show-success', ['message' => session('message')]);

        $this->resetInput();
    }

    public function confirmDelete($id)
    {
        $this->contactId = $id;
        $this->dispatchBrowserEvent('show-delete-modal');
    }

    public function delete()
    {
        Contact::destroy($this->contactId);
        session()->flash('message', 'Contact deleted successfully.');
        $this->dispatchBrowserEvent('hide-delete-modal');
        $this->dispatchBrowserEvent('show-success', ['message' => session('message')]);
    }

    private function resetInput()
    {
        $this->contactId = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
    }
}
