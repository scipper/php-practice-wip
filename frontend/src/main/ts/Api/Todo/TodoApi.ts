import {TodoEntry} from "./TodoEntry";

export class TodoApi {

    public async getAllTodos() {
        return await fetch("/api/todo")
            .then((response) => response.json() as Promise<TodoEntry[]>)
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
            .then((response) => response.json() as Promise<TodoEntry>)
    }

    public async done(id: number, done: boolean) {
        const requestOptions = {
            method: "PATCH",
            body: JSON.stringify({id, done}),
            headers: {
                "Content-Type": "application/json"
            },
        };

        return fetch("/api/todo", requestOptions)
            .then((response) => response.json() as Promise<TodoEntry>)
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