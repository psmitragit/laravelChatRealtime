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
                    <p>{{ $msg['message'] }}</p>
                </div>
            @endforeach
        </div>

        <form wire:submit.prevent='sendMessage'>
            <div class="message-input">
                <input id="typeMessageId" type="text" wire:model="message" placeholder="Type a message...">
                <button type="submit">Send</button>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('reFocus', function() {
            document.getElementById('typeMessageId').focus();
        })
    })
</script>