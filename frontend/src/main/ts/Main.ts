import {MainClass} from "../resources/decorators/MainClass";
import "../resources/styles/style.scss";
import {RegisteredModulesApi} from "./Api/RegisteredModules/RegisteredModulesApi";
import {Module} from "./Core/Module/Module";

@MainClass
export class Main {

    public static async main(): Promise<void> {
        const moduleMap: { [key: string]: () => Promise<any> } = {
            "Mys\\Modules\\Version\\VersionModule": () => import("./Modules/Version/VersionModule"),
            "Mys\\Modules\\Todo\\TodoModule": () => import("./Modules/Todo/TodoModule"),
            "Mys\\Modules\\RegisteredModules\\RegisteredModulesModule": () => import("./Modules/RegisteredModules/RegisteredModulesModule"),
        };
        const registeredModulesApi = new RegisteredModulesApi();
        const modulesList = await registeredModulesApi.getRegisteredModulesList();
        for (const moduleKey of modulesList) {
            const moduleImport = moduleMap[moduleKey];
            if (moduleImport) {
                const importedModule = await moduleImport();
                const moduleConstructor: any = Object.values(importedModule)[0];
                if (moduleConstructor) {
                    const module = new moduleConstructor();
                    if (module instanceof Module) {
                        module.init();
                    }
                }
            }
        }
    }

}