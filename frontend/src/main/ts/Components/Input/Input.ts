import {Renderable} from "../../Core/Module/Renderable";
import "./Input.scss";

export class Input implements Renderable {

    private readonly input: HTMLInputElement;

    public constructor() {
        this.input = document.createElement("input");
    }

    public getValue() {
        return this.input.value;
    }

    async render(): Promise<HTMLElement> {
        return this.input;
    }

}