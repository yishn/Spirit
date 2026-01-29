import fs from "node:fs/promises";
import path from "node:path";

export async function loadTemplate(name: string): Promise<string> {
  const template = await fs.readFile(
    path.resolve(import.meta.dirname, `../../templates/${name}.html`),
    "utf-8",
  );

  return template;
}

export function extractTemplate(
  content: string,
  id: string,
): string | undefined {
  const match = content.match(
    new RegExp(`<template[^>]*id="${id}"[^>]*>([\\s\\S]*?)<\\/template>`),
  );

  return match?.[1];
}

export function renderTemplate(
  template: string,
  placeholders: Record<string, string>,
): string {
  for (const placeholder in placeholders) {
    template = template.replaceAll(
      `{{${placeholder}}}`,
      placeholders[placeholder],
    );
  }

  return template;
}
