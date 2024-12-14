import {TodoApi} from "../../Api/Todo/TodoApi";

export class TodoController {
    private todoApi: TodoApi;
    private readonly ul: HTMLUListElement;

    public constructor(todoApi: TodoApi) {
        this.todoApi = todoApi;
        this.ul = document.createElement("ul");
    }

    public deleteTodo(id: number) {
        console.log("delete todo: " + id);
        this.todoApi.delete(id)
            .then(() => this.render())
            .catch((error) => console.error(error));
    }

    public async render() {
        while (this.ul.lastElementChild) {
            this.ul.removeChild(this.ul.lastElementChild);
        }
        const todos = await this.getAllTodos();
        todos.forEach((todo) => {
            const button = document.createElement("button");
            button.innerText = "delete";
            button.onclick = () => this.deleteTodo(todo["id"]);
            const li = document.createElement("li");
            li.innerText = todo["title"];
            li.insertAdjacentElement("beforeend", button);
            this.ul.insertAdjacentElement("beforeend", li);
        });

        return this.ul;
    }

    private getAllTodos() {
        return this.todoApi.getAllTodos();
    }

}