export class TodoApi {
    public async getAllTodos() {
        const response = await fetch("http://localhost:8080/api/todo");
        return await response.json() as any[];
    }
}