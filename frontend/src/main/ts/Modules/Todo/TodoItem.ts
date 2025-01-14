import {Renderable} from "../../Core/Module/Renderable";
import {TodoApi} from "../../Api/Todo/TodoApi";
import {IconButton} from "../../Components/Button/IconButton";

export class TodoItem implements Renderable {

    public constructor(private todo: any,
                       private todoApi: TodoApi,
                       private refresh: () => void) {
    }

    async render(): Promise<HTMLElement> {
        const deleteButton = new IconButton("X", () => {
            if (confirm(`Delete todo ${this.todo["title"]}?`)) {
                this.deleteTodo(this.todo["id"]);
            }
        });
        deleteButton.addClass("red");
        const li = document.createElement("li");
        li.innerText = this.todo["title"];
        li.insertAdjacentElement("beforeend", await deleteButton.render());
        return li;
    }

    private async deleteTodo(id: number) {
        try {
            await this.todoApi.delete(id);
            this.refresh();
        } catch (error) {
            console.error(error);
        }
    }

}