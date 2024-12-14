import {VersionApi} from "../../Api/Version/VersionApi";
import {Module} from "../../Core/Module/Module";

export class VersionModule extends Module {

    public constructor() {
        super();
        this.routes = [
            {
                route: "",
                controller: () => import("./VersionController")
                    .then((module) => module.VersionController)
                    .then((controller) => new controller(new VersionApi()))
            }
        ]
    }

}