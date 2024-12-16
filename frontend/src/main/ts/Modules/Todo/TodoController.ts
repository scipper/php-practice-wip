import {TodoApi} from "../../Api/Todo/TodoApi";
import {Controller} from "../../Core/Module/Controller";
import "./Todo.scss";

export class TodoController extends Controller {
    private todoApi: TodoApi;
    private readonly todoController: HTMLDivElement;

    public constructor(todoApi: TodoApi) {
        super();
        this.todoApi = todoApi;
        this.todoController = document.createElement("div");
        this.todoController.classList.add("todo-controller");
    }

    public deleteTodo(id: number) {
        this.todoApi.delete(id)
            .then(() => this.render())
            .catch((error) => console.error(error));
    }

    public createTodo(title: string) {
        this.todoApi.create(title)
            .then(() => this.render())
            .catch((error) => console.error(error));
    }

    override async render() {
        while (this.todoController.lastElementChild) {
            this.todoController.removeChild(this.todoController.lastElementChild);
        }
        const todosList = document.createElement("ul");
        const todos = await this.todoApi.getAllTodos();
        todos.forEach((todo) => {
            const button = document.createElement("button");
            button.innerText = "X";
            button.onclick = () => {
                if (confirm(`Delete todo ${todo["title"]}?`)) {
                    this.deleteTodo(todo["id"]);
                }
            };
            const li = document.createElement("li");
            li.innerText = todo["title"];
            li.insertAdjacentElement("beforeend", button);
            todosList.insertAdjacentElement("beforeend", li);
        });
        this.todoController.insertAdjacentElement("beforeend", todosList);

        const inputContainer = document.createElement("div");
        inputContainer.classList.add("input-container");
        const input = document.createElement("input");
        inputContainer.insertAdjacentElement("beforeend", input);
        const saveNewTodoButton = document.createElement("button");
        saveNewTodoButton.innerText = ">";
        saveNewTodoButton.onclick = () => this.createTodo(input.value);
        inputContainer.insertAdjacentElement("beforeend", saveNewTodoButton);

        this.todoController.insertAdjacentElement("beforeend", inputContainer);
        return this.todoController;
    }
}