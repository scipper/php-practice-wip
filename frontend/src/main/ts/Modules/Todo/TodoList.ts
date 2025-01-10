import {Controller} from "../../Core/Module/Controller";
import {TodoApi} from "../../Api/Todo/TodoApi";
import {TodoItem} from "./TodoItem";

export class TodoList implements Controller {

    public constructor(private todoApi: TodoApi) {
    }

    async render(): Promise<HTMLElement> {
        const todosList = document.createElement("ul");
        const todos = await this.todoApi.getAllTodos();
        for (const todo of todos) {
            const todoItem = new TodoItem(todo, this.todoApi);
            todosList.insertAdjacentElement("beforeend", await todoItem.render());
        }

        return todosList;
    }

}