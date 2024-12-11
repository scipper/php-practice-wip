export class VersionApi {
    public async getVersion() {
        const response = await fetch("http://localhost:8080/api/version");
        return await response.text();
    }
}