export class RegisteredModulesApi {
    public async getRegisteredModulesList() {
        const response = await fetch("http://localhost:8080/api/registeredmodules");
        return await response.json() as string[];
    }
}