import {Renderable} from "../../Core/Module/Renderable";
import {TodoApi} from "../../Api/Todo/TodoApi";
import {IconButton} from "../../Components/Button/IconButton";
import {Input} from "../../Components/Input/Input";

export class AddTodo implements Renderable {

    public constructor(private todoApi: TodoApi,
                       private refresh: () => void) {
    }

    async render(): Promise<HTMLElement> {
        const inputContainer = document.createElement("div");
        inputContainer.classList.add("input-container");

        const input = new Input();
        inputContainer.insertAdjacentElement("beforeend", await input.render());

        const saveNewTodoButton = new IconButton(">", () => this.createTodo(input.getValue()));
        inputContainer.insertAdjacentElement("beforeend", await saveNewTodoButton.render());

        return inputContainer;
    }

    private async createTodo(title: string) {
        try {
            await this.todoApi.create(title);
            this.refresh();
        } catch (error) {
            console.error(error);
        }
    }

}