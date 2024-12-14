export class TodoApi {

    public async getAllTodos() {
        const response = await fetch("/api/todo");
        return await response.json() as any[];
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