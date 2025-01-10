import {Controller} from "../../Core/Module/Controller";
import {TodoApi} from "../../Api/Todo/TodoApi";

export class TodoItem implements Controller {

    public constructor(private todo: any,
                       private todoApi: TodoApi) {
    }

    async render(): Promise<HTMLElement> {
        const button = document.createElement("button");
        button.innerText = "X";
        button.onclick = () => {
            if (confirm(`Delete todo ${this.todo["title"]}?`)) {
                this.deleteTodo(this.todo["id"]);
            }
        };
        const li = document.createElement("li");
        li.innerText = this.todo["title"];
        li.insertAdjacentElement("beforeend", button);
        return li;
    }

    private deleteTodo(id: number) {
        this.todoApi.delete(id)
            .then(() => this.render())
            .catch((error) => console.error(error));
    }

}