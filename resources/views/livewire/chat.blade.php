<div class="chat-container">
    <div class="chat-sidebar">
        <ul>
            @foreach ($users as $user)
                <li wire:click="switchRecipient({{ $user->id }})"
                    class="{{ $user->id == $recipientId ? 'active' : '' }}">
                    {{ $user->name }}
                    @if (isset($unreadCounts[$user->id]) && $unreadCounts[$user->id] > 0)
                        <span class="badge">{{ $unreadCounts[$user->id] }}</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    <div class="chat-content">
        <div class="messages">
            @foreach ($messages as $msg)
                <div class="message {{ $msg['from'] == auth()->id() ? 'sent' : 'received' }}">
                    @if (!empty($msg['file']))
                        <div>
                            <a href="{{ asset('storage/chat-uploads/' . $msg['file']) }}" target="_blank">See
                                attachment</a>
                        </div>
                    @endif
                    <p>{{ $msg['message'] }}</p>
                </div>
            @endforeach
        </div>

        <form wire:submit.prevent='sendMessage' id="scrollToDiv">
            <div class="message-input">
                <input id="typeMessageId" type="text" wire:model="message" placeholder="Type a message..."
                    {{ !$recipientId ? 'disabled' : '' }}>
                <div>
                    <input type="file" wire:model='file'>
                    @error('file')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" {{ !$recipientId ? 'disabled' : '' }}>Send</button>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('reFocus', function() {
            document.getElementById('typeMessageId').focus();
        })

        Livewire.on('scrollBottom', function() {
            setTimeout(() => {
                const messages = document.querySelectorAll('.message');
                if (messages.length > 0) {
                    const lastMessage = messages[messages.length - 1];
                    lastMessage.scrollIntoView({
                        behavior: 'smooth',
                        block: 'end'
                    });
                }
            }, 100);
        })
    });
</script>
