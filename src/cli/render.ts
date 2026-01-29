import { loadTemplate } from "./template.ts";

export async function renderIndex(): Promise<string> {
  const template = await loadTemplate("index");

  return template;
}

console.log(await renderIndex());
