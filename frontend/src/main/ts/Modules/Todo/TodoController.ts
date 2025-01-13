import {TodoApi} from "../../Api/Todo/TodoApi";
import {Renderable} from "../../Core/Module/Renderable";
import "./Todo.scss";
import {AddTodo} from "./AddTodo";
import {TodoList} from "./TodoList";

export class TodoController implements Renderable {

    private readonly todoController: HTMLElement;

    public constructor(private todoApi: TodoApi) {
        this.todoController = document.createElement("todo-controller");
    }

    async render() {
        while (this.todoController.lastElementChild) {
            this.todoController.removeChild(this.todoController.lastElementChild);
        }
        const todosList = new TodoList(this.todoApi, () => this.render());
        this.todoController.insertAdjacentElement("beforeend", await todosList.render());

        const addTodo = new AddTodo(this.todoApi, () => this.render());
        this.todoController.insertAdjacentElement("beforeend", await addTodo.render());

        return this.todoController;
    }
}