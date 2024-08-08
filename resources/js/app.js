import './bootstrap';

document.addEventListener('livewire:init', () => {
    var channel = Echo.channel('chat');
    channel.listen('.chat', function (data) {
        // console.log(data);
        Livewire.dispatch('newMessageReceived', { data: data });
    });
});
