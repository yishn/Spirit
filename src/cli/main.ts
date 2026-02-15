import { compile } from "./compiler.ts";

async function main() {
  const args = process.argv.slice(2);
  const inputDir = args[0];

  if (!inputDir) {
    throw new Error("Input directory as first argument is required.");
  }

  await compile(inputDir);
}

try {
  await main();
} catch (err) {
  console.error(err);
  process.exit(1);
}
