export function storeUsers(state, users) {
    users.forEach(user => {
        state.users[user.id] = { name: user.name };
    });

    // state.users = users;
}

export function storeMessages(state, messages) {
    state.messages = messages;
}

export function updateUserInfo(state, payload) {
    const findUserIndex = state.users.findIndex(
        user => user.id === payload.user_id
    );

    // console.log({ payload });

    if (payload.typing_info) {
        state.users[findUserIndex].typing_info = payload.typing_info.message;
    }
}
