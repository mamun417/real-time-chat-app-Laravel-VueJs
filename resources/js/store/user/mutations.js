import Vue from "vue";

export function storeUsers(state, users) {
    users.forEach(user => {
        Vue.set(state.users, user.id, { ...state.users[user.id], ...user });
    });
}

export function storeMessages(state, messages) {
    state.messages = messages;
}

export function updateUserInfo(state, payload) {
    const userId = payload.user.id;
    const user = state.users[userId];

    if (!userId) {
        console.log("invalid user id");
        return;
    }

    if (!user) {
        Vue.set(state.users, userId, payload.user);
    }

    if (payload.hasOwnProperty("typing_info")) {
        Vue.set(state.users, userId, {
            ...user,
            typing_info: payload.typing_info
        });
    }

    if (payload.hasOwnProperty("online_status")) {
        Vue.set(state.users, userId, {
            ...user,
            online_status: payload.online_status
        });
    }
}
