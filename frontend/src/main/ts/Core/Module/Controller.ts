export interface Controller {
    render(): Promise<HTMLElement | undefined>;
}