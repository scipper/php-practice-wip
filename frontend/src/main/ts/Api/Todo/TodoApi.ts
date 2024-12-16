export class TodoApi {

    public async getAllTodos() {
        return await fetch("/api/todo")
            .then((response) => response.json() as Promise<any[]>)
    }

    public async create(title: string) {
        const requestOptions = {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({title})
        };

        return fetch("/api/todo", requestOptions)
            .then((response) => response.json() as Promise<any[]>)
    }

    public async delete(id: number) {
        return await fetch("/api/todo", {
            method: "DELETE",
            body: `${id}`,
            headers: {
                "Content-Type": "application/json"
            }
        });
    }

}