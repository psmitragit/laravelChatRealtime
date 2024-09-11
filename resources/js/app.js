import './bootstrap';

document.addEventListener('livewire:init', () => {
    var channel = Echo.private(`chat.${window.userId}`);
    channel.listen('.chat', function (data) {
        Livewire.dispatch('newMessageReceived', { data: data });
        resetForm();
    });

    Echo.join(`group-chat.${roomId}`)
        .here((users) => {
            let userIds = users.map(user => user.id);
            // console.log('Current number of users:', userIds.length);
            Livewire.dispatch('updateUserList', { userIds });
        })
        .joining((user) => {
            // console.log(`${user.name} has joined the chat.`);
            let joinedUserId = user.id;
            Livewire.dispatch('userJoined', [joinedUserId]);
        })
        .leaving((user) => {
            // console.log(`${user.name} has left the chat.`);
            let leftUserId = user.id;
            Livewire.dispatch('userLeft', [leftUserId]);
        })
        .listen('.group-chat', function (data) {
            // console.log('New message received:', data);
            Livewire.dispatch('newMessageReceived');
            resetForm();
        });



    function resetForm() {
        try {
            $('.chatForm').each(function () {
                this.reset();
            });
        } catch (error) {
            console.error('Error:' + error);
        }
    }
});

