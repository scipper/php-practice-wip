import {Module} from "../../Core/Module/Module";
import {RegisteredModulesController} from "./RegisteredModulesController";

export class RegisteredModulesModule extends Module {
    public init() {
        new RegisteredModulesController()
    }
}