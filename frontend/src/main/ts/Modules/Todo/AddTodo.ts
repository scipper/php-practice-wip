import {Controller} from "../../Core/Module/Controller";
import {TodoApi} from "../../Api/Todo/TodoApi";

export class AddTodo implements Controller {

    public constructor(private todoApi: TodoApi,
                       private refresh: () => void) {
    }

    async render(): Promise<HTMLElement> {
        const inputContainer = document.createElement("div");
        inputContainer.classList.add("input-container");
        const input = document.createElement("input");
        inputContainer.insertAdjacentElement("beforeend", input);
        const saveNewTodoButton = document.createElement("button");
        saveNewTodoButton.innerText = ">";
        saveNewTodoButton.onclick = () => this.createTodo(input.value);
        inputContainer.insertAdjacentElement("beforeend", saveNewTodoButton);

        return inputContainer;
    }

    private createTodo(title: string) {
        this.todoApi.create(title)
            .then(() => this.refresh())
            .catch((error) => console.error(error));
    }

}