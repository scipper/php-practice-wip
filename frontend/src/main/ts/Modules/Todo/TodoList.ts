import {Renderable} from "../../Core/Module/Renderable";
import {TodoApi} from "../../Api/Todo/TodoApi";
import {TodoItem} from "./TodoItem";

export class TodoList implements Renderable {

    public constructor(private todoApi: TodoApi,
                       private refresh: () => void) {
    }

    async render(): Promise<HTMLElement> {
        const todosList = document.createElement("ul");
        const todos = await this.todoApi.getAllTodos();
        for (const todo of todos) {
            const todoItem = new TodoItem(todo, this.todoApi, this.refresh);
            todosList.insertAdjacentElement("beforeend", await todoItem.render());
        }

        return todosList;
    }

}