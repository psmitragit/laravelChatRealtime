import './bootstrap';

document.addEventListener('livewire:init', () => {
    var channel = Echo.private(`chat.${window.userId}`);
    channel.listen('.chat', function (data) {
        Livewire.dispatch('newMessageReceived', { data: data });
    });

    Echo.join(`group-chat.${roomId}`)
        .here((users) => {
            console.log('Current users in the room:', users);
        })
        .joining((user) => {
            console.log(`${user.name} has joined the chat`);
        })
        .leaving((user) => {
            console.log(`${user.name} has left the chat`);
        })
        .error((error) => {
            console.error('Error in the group chat:', error);
        })
        .listen('.group-chat', function (data) {
            console.log('New message received:', data);
            Livewire.dispatch('newMessageReceived', { data: data });
        });

    Livewire.on('reFocus', function () {
        document.getElementById('typeMessageId').focus();
    })

    Livewire.on('scrollBottom', function () {
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

