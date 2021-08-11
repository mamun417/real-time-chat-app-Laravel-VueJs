export function getUsers(context, payload) {
    return new Promise((resolve, reject) => {
        axios
            .get("users")
            .then(res => {
                console.log(res.data);
                context.commit("storeUsers", res.data);
                resolve(res);
            })
            .catch(err => {
                reject(err);
            });
    });
}
