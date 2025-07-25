<?php

namespace App\Http\Livewire;

use App\Models\Contact;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

/**
 *
 */
class ContactsTable extends Component
{
    use WithPagination;

    /**
     * @var string
     */
    public $search = '';
    /**
     * @var string
     */
    public $sortField = 'name';
    /**
     * @var string
     */
    public $sortDirection = 'asc';
    /**
     * @var
     */
    public $contactId;
    /**
     * @var
     */
    /**
     * @var
     */
    /**
     * @var
     */
    public $name, $email, $phone;
    /**
     * @var string
     */
    public $modalTitle = 'Create Contact';

    /**
     * @var string[]
     */
    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'phone' => 'required'
    ];

    /**
     * @var string[]
     */
    protected $listeners = ['deleteConfirmed' => 'delete'];

    /**
     * @return Application|Factory|View
     */
    public function render()
    {
        $contacts = Contact::where('name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        return view('livewire.contacts-table', compact('contacts'));
    }

    /**
     * @param $field
     * @return void
     */
    public function sortBy($field)
    {
        $this->sortDirection = ($this->sortField === $field && $this->sortDirection === 'asc') ? 'desc' : 'asc';
        $this->sortField = $field;
    }

    /**
     * @return void
     */
    public function create()
    {
        $this->resetInput();
        $this->modalTitle = 'Create Contact';
        $this->dispatchBrowserEvent('show-modal');
    }

    /**
     * @param $id
     * @return void
     */
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

    /**
     * @return void
     */
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

    /**
     * @param $id
     * @return void
     */
    public function confirmDelete($id)
    {
        $this->contactId = $id;
        $this->dispatchBrowserEvent('show-delete-modal');
    }

    public function resetForm()
    {
        $this->reset(['name', 'email', 'phone', 'contactId']);
        $this->modalTitle = 'Add Contact'; // Optional
    }

    /**
     * @return void
     */
    public function delete()
    {
        Contact::destroy($this->contactId);
        session()->flash('message', 'Contact deleted successfully.');
        $this->dispatchBrowserEvent('hide-delete-modal');
        $this->dispatchBrowserEvent('show-success', ['message' => session('message')]);
    }

    public function cancelDelete()
    {
        $this->contactId = null;
    }

    /**
     * @return void
     */
    private function resetInput()
    {
        $this->contactId = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
    }
}
