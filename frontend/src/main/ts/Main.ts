import {MainClass} from "../resources/decorators/MainClass";
import "../resources/styles/style.scss";
import {VersionController} from "./Version/VersionController";
import {VersionApi} from "./Api/Version/VersionApi";

@MainClass
export class Main {

    public static main(): void {
        const h1 = document.querySelector("h1");
        if (h1) {
            h1.innerText = "It's working!";
        }

        new VersionController(new VersionApi());
    }

}