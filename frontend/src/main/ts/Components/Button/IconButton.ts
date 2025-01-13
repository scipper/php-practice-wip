import {Renderable} from "../../Core/Module/Renderable";
import "./IconButton.scss";

export class IconButton implements Renderable {

    private classList: string[];
    private iconButton: HTMLButtonElement;

    public constructor(private text: string, private clickFunction: () => void) {
        this.classList = [];
        this.iconButton = document.createElement("button");
    }

    addClass(className: string) {
        this.classList.push(className);
    }

    async render(): Promise<HTMLElement> {
        this.iconButton.classList.add("icon-button", ...this.classList);
        this.iconButton.innerText = this.text;
        this.iconButton.onclick = this.clickFunction;

        return this.iconButton;
    }
}