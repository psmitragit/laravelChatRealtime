<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\GroupMessage;
use App\Events\GroupChat as EventGroupChat;
use Livewire\WithFileUploads;

class GroupChat extends Component
{
    use WithFileUploads;
    public $roomId;
    public $message = '';
    public $file;
    public $messages;
    public function mount()
    {
        $this->loadMessages();
    }
    public function loadMessages()
    {
        $this->messages = GroupMessage::where('room_id', $this->roomId)
            ->orderBy('created_at', 'asc')
            ->get();
        $this->dispatch('scrollBottom');
    }
    // public function updatedFile()
    // {
    //     $this->validate([
    //         'file' => 'nullable|file|max:4096',
    //     ]);
    // }
    public function sendMessage()
    {
        // $this->validate([
        //     'message' => 'required',
        //     'file' => 'nullable|file|max:4096',
        // ]);

        $customFileName = null;

        $directory = storage_path('app/public/chat-uploads');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        if ($this->file) {
            $fileExtension = $this->file->getClientOriginalExtension();
            $customFileName = rand(11111, 99999) . time() . '.' . $fileExtension;
            $this->file->storeAs('chat-uploads', $customFileName, 'public');
        }

        $data = [
            'user_id' => auth()->id(),
            'room_id' => $this->roomId,
            'message' => $this->message,
            'file' => $customFileName,
        ];

        GroupMessage::create($data);

        broadcast(new EventGroupChat($this->roomId));

        $this->message = '';
        $this->reset(['file']);
        $this->loadMessages();
    }
    #[On('newMessageReceived')]
    public function notifyNewMessage()
    {
        $this->loadMessages();
    }
    public function render()
    {
        return view('livewire.group-chat');
    }
}
