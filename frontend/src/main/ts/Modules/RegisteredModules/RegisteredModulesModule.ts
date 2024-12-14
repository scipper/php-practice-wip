import {Module} from "../../Core/Module/Module";

export class RegisteredModulesModule extends Module {

    public constructor() {
        super();
        this.routes = [
            {
                route: "#/registeredmodules",
                controller: () => import("./RegisteredModulesController")
                    .then((module) => module.RegisteredModulesController)
                    .then((controller) => new controller())
            }
        ]
        this.navigation = "Modules";
    }

}