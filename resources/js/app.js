import './bootstrap';

document.addEventListener('livewire:init', () => {
    var channel = Echo.private(`chat.${window.userId}`);
    channel.listen('.chat', function (data) {
        Livewire.dispatch('newMessageReceived', { data: data });
    });
});

