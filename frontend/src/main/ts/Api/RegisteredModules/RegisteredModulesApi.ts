export class RegisteredModulesApi {
    public async getRegisteredModulesList() {
        const response = await fetch("/api/registeredmodules");
        return await response.json() as string[];
    }
}