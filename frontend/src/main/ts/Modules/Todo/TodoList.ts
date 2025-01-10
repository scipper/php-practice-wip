import {TodoApi} from "../../Api/Todo/TodoApi";
import {Controller} from "../../Core/Module/Controller";
import "./Todo.scss";
import {TodoItem} from "./TodoItem";

export class TodoList implements Controller {

    private readonly todoList: HTMLDivElement;

    public constructor(private todoApi: TodoApi) {
        this.todoList = document.createElement("div");
        this.todoList.classList.add("todo-list");
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

    async render() {
        while (this.todoList.lastElementChild) {
            this.todoList.removeChild(this.todoList.lastElementChild);
        }
        const todosList = document.createElement("ul");
        const todos = await this.todoApi.getAllTodos();
        for (const todo of todos) {
            const todoItem = new TodoItem(todo, this.todoApi);
            todosList.insertAdjacentElement("beforeend", await todoItem.render());
        }
        this.todoList.insertAdjacentElement("beforeend", todosList);

        const inputContainer = document.createElement("div");
        inputContainer.classList.add("input-container");
        const input = document.createElement("input");
        inputContainer.insertAdjacentElement("beforeend", input);
        const saveNewTodoButton = document.createElement("button");
        saveNewTodoButton.innerText = ">";
        saveNewTodoButton.onclick = () => this.createTodo(input.value);
        inputContainer.insertAdjacentElement("beforeend", saveNewTodoButton);

        this.todoList.insertAdjacentElement("beforeend", inputContainer);
        return this.todoList;
    }
}